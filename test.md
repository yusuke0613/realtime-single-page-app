# VS Code × MCP × GraphQL でコード変換APIエージェントを作る

ユーザーが「東京都の出荷準備中の注文を見せて」と話しかけると、VS Code → MCPサーバー → GraphQLゲートウェイ → 元APIの順に処理が走り、コード値の変換をすべて裏側で済ませた結果が返ってくる、という構成を一から作ります。

実際のAPIがなくても試せるようモックAPIも含めます。最後まで進めれば、本物のAPIに差し替えるだけで本番運用に持っていける構成になります。

---

## 1. 前提条件

次の3つが必要です。

- **Node.js 20以上** (`node --version` で確認)
- **VS Code 1.99以上** (`Help → About` で確認)
- **GitHub Copilotの有効なサブスクリプション** (無料枠でもAgent Modeは使えます)

VS CodeのMCPサポートはGitHub CopilotのAgent Modeを通して動きます。Copilot Chatの入力欄上部に「Ask / Edit / Agent」のドロップダウンがあるか確認してください。なければVS Codeをアップデートします。

---

## 2. プロジェクトの作成

任意の場所にプロジェクト用フォルダを作って初期化します。

```bash
mkdir api-agent-mcp
cd api-agent-mcp
npm init -y
```

`package.json` を以下に置き換えます。

```json
{
  "name": "api-agent-mcp",
  "version": "0.1.0",
  "type": "module",
  "scripts": {
    "dev:apis": "concurrently \"tsx watch src/mock-order-api.ts\" \"tsx watch src/mock-code-api.ts\"",
    "dev:gateway": "tsx watch src/gateway.ts",
    "dev:all": "concurrently -n APIS,GW \"npm:dev:apis\" \"npm:dev:gateway\"",
    "test:gql": "tsx scripts/test-gql.ts"
  },
  "dependencies": {
    "@apollo/server": "^4.11.0",
    "@modelcontextprotocol/sdk": "^1.0.0",
    "dataloader": "^2.2.3",
    "express": "^4.21.0",
    "graphql": "^16.9.0",
    "graphql-tag": "^2.12.6"
  },
  "devDependencies": {
    "@types/express": "^5.0.0",
    "@types/node": "^22.0.0",
    "concurrently": "^9.0.0",
    "tsx": "^4.19.0",
    "typescript": "^5.6.0"
  }
}
```

`tsconfig.json` を作成します。

```json
{
  "compilerOptions": {
    "target": "ES2022",
    "module": "ESNext",
    "moduleResolution": "Bundler",
    "esModuleInterop": true,
    "strict": true,
    "skipLibCheck": true,
    "resolveJsonModule": true
  }
}
```

依存関係をインストール。

```bash
npm install
```

ディレクトリ構造を作ります。

```bash
mkdir -p src .vscode scripts
```

---

## 3. モックAPIを2つ作る

まずは「元API」と「コード変換API」をモックで用意します。本物のAPIが手元にあるなら、ここはスキップしてURLだけ後で書き換えればOKです。

### 3.1 注文検索API (元API)

`src/mock-order-api.ts` を作成します。

```typescript
import express from "express";

const app = express();
app.use(express.json());

const orders = [
  { id: "O001", prefectureCode: "13", statusCode: "S01", total: 5000 },
  { id: "O002", prefectureCode: "27", statusCode: "S02", total: 12000 },
  { id: "O003", prefectureCode: "13", statusCode: "S03", total: 8000 },
  { id: "O004", prefectureCode: "14", statusCode: "S01", total: 3200 },
  { id: "O005", prefectureCode: "13", statusCode: "S01", total: 9800 },
];

app.get("/orders", (req, res) => {
  const { prefectureCode, statusCode } = req.query;
  let result = orders;
  if (prefectureCode) result = result.filter(o => o.prefectureCode === prefectureCode);
  if (statusCode) result = result.filter(o => o.statusCode === statusCode);
  res.json(result);
});

app.listen(3001, () => {
  console.log("Order API: http://localhost:3001");
});
```

このAPIはコード値しか返しません。「13」「S01」のような値だけです。

### 3.2 コード変換API

`src/mock-code-api.ts` を作成します。

```typescript
import express from "express";

const app = express();
app.use(express.json());

const masters: Record<string, Record<string, string>> = {
  prefecture: {
    "13": "東京都",
    "14": "神奈川県",
    "27": "大阪府",
  },
  orderStatus: {
    "S01": "出荷準備中",
    "S02": "出荷済み",
    "S03": "配達完了",
  },
};

// コード→名称 (バッチ対応)
app.post("/code-to-name", (req, res) => {
  const { kind, codes } = req.body as { kind: string; codes: string[] };
  const dict = masters[kind] ?? {};
  const result = Object.fromEntries(codes.map(c => [c, dict[c] ?? null]));
  res.json(result);
});

// 名称→コード
app.post("/name-to-code", (req, res) => {
  const { kind, name } = req.body as { kind: string; name: string };
  const dict = masters[kind] ?? {};
  const found = Object.entries(dict).find(([_, n]) => n === name);
  if (!found) {
    return res.status(404).json({ error: "NOT_FOUND", input: name });
  }
  res.json({ code: found[0] });
});

app.listen(3002, () => {
  console.log("Code API: http://localhost:3002");
});
```

### 3.3 動作確認

ターミナルでモックAPIを起動します。

```bash
npm run dev:apis
```

別ターミナルで叩いてみます。

```bash
# コード値で注文検索
curl "http://localhost:3001/orders?prefectureCode=13"

# 名称→コード変換
curl -X POST http://localhost:3002/name-to-code \
  -H "Content-Type: application/json" \
  -d '{"kind":"prefecture","name":"東京都"}'

# コード→名称変換 (バッチ)
curl -X POST http://localhost:3002/code-to-name \
  -H "Content-Type: application/json" \
  -d '{"kind":"prefecture","codes":["13","27"]}'
```

期待通りのレスポンスが返ってくれば成功です。

---

## 4. GraphQLゲートウェイの実装

ここが今回の中核です。GraphQLスキーマで「コード」と「名称」を兄弟フィールドとして定義し、名称側のresolverがコード変換APIを自動で呼ぶようにします。さらに DataLoader でバッチ化してN+1問題を防ぎます。

`src/gateway.ts` を作成します。

```typescript
import { ApolloServer } from "@apollo/server";
import { startStandaloneServer } from "@apollo/server/standalone";
import DataLoader from "dataloader";
import gql from "graphql-tag";

const ORDER_API = "http://localhost:3001";
const CODE_API = "http://localhost:3002";

// --- スキーマ定義 -----------------------------------------
const typeDefs = gql`
  type Order {
    id: ID!
    prefectureCode: String!
    prefectureName: String!
    statusCode: String!
    statusName: String!
    total: Int!
  }

  type Query {
    """名称をコードに変換 (見つからなければ null)"""
    resolveCode(kind: String!, name: String!): String

    """注文を検索 (コード・名称どちらの引数でも可)"""
    searchOrders(
      prefectureCode: String
      prefectureName: String
      statusCode: String
      statusName: String
    ): [Order!]!
  }
`;

// --- DataLoader: コード→名称をバッチ化 ---------------------
type CodeKey = { kind: string; code: string };

function createCodeLoader() {
  return new DataLoader<CodeKey, string | null>(async (keys) => {
    // kindごとに集約
    const grouped: Record<string, Set<string>> = {};
    for (const k of keys) {
      grouped[k.kind] ??= new Set();
      grouped[k.kind].add(k.code);
    }

    // kindごとに1リクエスト
    const results: Record<string, Record<string, string>> = {};
    await Promise.all(
      Object.entries(grouped).map(async ([kind, codeSet]) => {
        const res = await fetch(`${CODE_API}/code-to-name`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ kind, codes: [...codeSet] }),
        });
        results[kind] = await res.json();
      })
    );

    // keysと同じ順序で結果を返す (DataLoaderの規約)
    return keys.map(k => results[k.kind]?.[k.code] ?? null);
  });
}

// --- 名称→コード変換のヘルパー -----------------------------
async function nameToCode(kind: string, name: string): Promise<string | null> {
  const res = await fetch(`${CODE_API}/name-to-code`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ kind, name }),
  });
  if (!res.ok) return null;
  const data = await res.json();
  return data.code;
}

// --- Resolver --------------------------------------------
interface Context {
  codeLoader: ReturnType<typeof createCodeLoader>;
}

const resolvers = {
  // 注文オブジェクトのフィールドresolver
  // prefectureName / statusName はAPIレスポンスに無いので、ここで自動補完
  Order: {
    prefectureName: (parent: any, _: unknown, ctx: Context) =>
      ctx.codeLoader.load({ kind: "prefecture", code: parent.prefectureCode }),
    statusName: (parent: any, _: unknown, ctx: Context) =>
      ctx.codeLoader.load({ kind: "orderStatus", code: parent.statusCode }),
  },

  Query: {
    resolveCode: (_: unknown, args: { kind: string; name: string }) =>
      nameToCode(args.kind, args.name),

    searchOrders: async (_: unknown, args: any) => {
      // 入力が名称ならコードに変換
      const prefCode =
        args.prefectureCode ??
        (args.prefectureName ? await nameToCode("prefecture", args.prefectureName) : null);
      const statusCode =
        args.statusCode ??
        (args.statusName ? await nameToCode("orderStatus", args.statusName) : null);

      // 元APIを呼ぶ
      const params = new URLSearchParams();
      if (prefCode) params.set("prefectureCode", prefCode);
      if (statusCode) params.set("statusCode", statusCode);
      const res = await fetch(`${ORDER_API}/orders?${params}`);
      return res.json();
    },
  },
};

// --- サーバー起動 -----------------------------------------
const server = new ApolloServer<Context>({ typeDefs, resolvers });

const { url } = await startStandaloneServer(server, {
  listen: { port: 4000 },
  // リクエストごとにDataLoaderを作る (キャッシュをリクエストスコープに)
  context: async () => ({ codeLoader: createCodeLoader() }),
});

console.log(`GraphQL Gateway: ${url}`);
```

重要なポイントを3つ補足します。

**Resolverの呼び出し順序**: クライアントが `searchOrders` を叩くと、まず `Query.searchOrders` が動いて元APIから注文配列を取得します。その後、各注文の `prefectureName` / `statusName` フィールドが要求されると、`Order.prefectureName` / `Order.statusName` resolverが呼ばれて初めてコード変換APIが叩かれる、という流れです。

**DataLoaderの効果**: 上のコードでは、注文5件すべてに `prefectureName` を要求しても、コード変換APIへのリクエストは「prefecture種別で1回」だけにまとめられます。`new Set()` でユニーク化もしているので、無駄も出ません。

**リクエストスコープ**: `context` 関数はリクエストのたびに呼ばれるので、`codeLoader` も毎回新しいものが作られます。これによりリクエスト間でキャッシュが混ざりません。本番でマスタ全体をキャッシュしたい場合は、別途プロセスローカルなキャッシュ層を追加します。

### 4.1 ゲートウェイの起動と動作確認

別ターミナルで起動します。

```bash
npm run dev:gateway
```

ブラウザで http://localhost:4000/ を開くとApollo Sandboxが立ち上がります。次のクエリを実行してみてください。

```graphql
query {
  searchOrders(prefectureName: "東京都", statusName: "出荷準備中") {
    id
    prefectureCode
    prefectureName
    statusCode
    statusName
    total
  }
}
```

期待されるレスポンス:

```json
{
  "data": {
    "searchOrders": [
      {
        "id": "O001",
        "prefectureCode": "13",
        "prefectureName": "東京都",
        "statusCode": "S01",
        "statusName": "出荷準備中",
        "total": 5000
      },
      {
        "id": "O005",
        "prefectureCode": "13",
        "prefectureName": "東京都",
        "statusCode": "S01",
        "statusName": "出荷準備中",
        "total": 9800
      }
    ]
  }
}
```

入力は名称、出力はコードと名称の両方が含まれていれば成功です。

---

## 5. MCPサーバーの実装

GraphQLゲートウェイをMCPツールとしてVS Codeに公開します。

`src/mcp-server.ts` を作成します。

```typescript
import { Server } from "@modelcontextprotocol/sdk/server/index.js";
import { StdioServerTransport } from "@modelcontextprotocol/sdk/server/stdio.js";
import {
  CallToolRequestSchema,
  ListToolsRequestSchema,
} from "@modelcontextprotocol/sdk/types.js";

const GRAPHQL_URL = process.env.GRAPHQL_URL ?? "http://localhost:4000/";

async function gqlRequest(query: string, variables?: Record<string, unknown>) {
  const res = await fetch(GRAPHQL_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ query, variables }),
  });
  if (!res.ok) {
    throw new Error(`GraphQL request failed: ${res.status} ${res.statusText}`);
  }
  return res.json();
}

const server = new Server(
  { name: "api-agent", version: "0.1.0" },
  { capabilities: { tools: {} } }
);

// ツール一覧
server.setRequestHandler(ListToolsRequestSchema, async () => ({
  tools: [
    {
      name: "search_orders",
      description:
        "注文を検索します。都道府県名やステータス名 (例: 東京都、出荷準備中) を指定でき、内部で自動的にコードに変換されます。レスポンスにはコードと名称の両方が含まれます。",
      inputSchema: {
        type: "object",
        properties: {
          prefectureName: {
            type: "string",
            description: "都道府県名 (例: 東京都、神奈川県、大阪府)",
          },
          statusName: {
            type: "string",
            description: "注文ステータス名 (例: 出荷準備中、出荷済み、配達完了)",
          },
        },
      },
    },
    {
      name: "execute_graphql",
      description:
        "任意のGraphQLクエリを実行します。スキーマを確認したい時や、定義済みツールでカバーできない問い合わせ向け。",
      inputSchema: {
        type: "object",
        properties: {
          query: { type: "string", description: "GraphQLクエリ文字列" },
          variables: { type: "object", description: "クエリ変数 (任意)" },
        },
        required: ["query"],
      },
    },
  ],
}));

// ツール呼び出し
server.setRequestHandler(CallToolRequestSchema, async (req) => {
  const { name, arguments: args } = req.params;

  if (name === "search_orders") {
    const { prefectureName, statusName } = (args ?? {}) as {
      prefectureName?: string;
      statusName?: string;
    };
    const result = await gqlRequest(
      `query($pref: String, $status: String) {
        searchOrders(prefectureName: $pref, statusName: $status) {
          id
          prefectureCode prefectureName
          statusCode statusName
          total
        }
      }`,
      { pref: prefectureName, status: statusName }
    );
    return {
      content: [{ type: "text", text: JSON.stringify(result, null, 2) }],
    };
  }

  if (name === "execute_graphql") {
    const { query, variables } = (args ?? {}) as {
      query: string;
      variables?: Record<string, unknown>;
    };
    const result = await gqlRequest(query, variables);
    return {
      content: [{ type: "text", text: JSON.stringify(result, null, 2) }],
    };
  }

  throw new Error(`Unknown tool: ${name}`);
});

// stdioで起動
const transport = new StdioServerTransport();
await server.connect(transport);

// 注意: console.log は stdout を汚染してMCP通信を壊す
// 起動メッセージは必ず console.error (stderr) を使う
console.error("MCP server started");
```

MCPサーバー実装で**最も重要な落とし穴**: `console.log` は使わないこと。MCPはstdioで通信するので、stdoutにJSON以外を書くと通信が壊れます。ログを出すなら必ず `console.error` (stderr側) を使ってください。

---

## 6. VS Codeの設定

ワークスペース直下の `.vscode/mcp.json` を作成します。

```json
{
  "servers": {
    "api-agent": {
      "type": "stdio",
      "command": "npx",
      "args": ["tsx", "${workspaceFolder}/src/mcp-server.ts"],
      "env": {
        "GRAPHQL_URL": "http://localhost:4000/"
      }
    }
  }
}
```

VS Code固有の注意点として、ルートキーは `servers` です (Claude Desktopは `mcpServers` を使うのでコピペ時に間違えやすい)。

---

## 7. 動作確認

ここまでで全パーツが揃ったので、エンドツーエンドで動かします。

### 7.1 サーバー群の起動

ターミナル1でモックAPIとゲートウェイを同時起動します。

```bash
npm run dev:all
```

3つのプロセスが立ち上がり、以下の3行が出力されればOKです。

```
Order API: http://localhost:3001
Code API: http://localhost:3002
GraphQL Gateway: http://localhost:4000/
```

### 7.2 VS CodeからMCPサーバーを起動

VS Codeでプロジェクトフォルダを開き、コマンドパレット (Cmd/Ctrl+Shift+P) で `MCP: List Servers` を実行します。`api-agent` が表示されたら選択して `Start` を押します。

エラーが出る場合は `Show Output` でログを確認してください。

### 7.3 Copilot Chatで質問してみる

Copilot Chatを開き、モードドロップダウンから `Agent` を選択します。チャット入力欄上部の `Configure Tools` から `api-agent` のツールが有効になっていることを確認します。

次のように質問します。

> 東京都の出荷準備中の注文を教えて

Agentが `search_orders` ツールを呼び、結果を整形して回答してくれるはずです。実行確認のダイアログが出たら `Continue` を押します。

---

## 8. トラブルシューティング

**MCPサーバーが起動しない**: `MCP: List Servers` → 該当サーバー選択 → `Show Output` でログを確認します。`tsx` が見つからないエラーなら `npm install` が完了しているか確認。

**ツールがCopilot Chatに表示されない**: モードが `Agent` になっているか確認。`Ask` や `Edit` モードではMCPツールは見えません。

**通信が壊れる/レスポンスが空**: MCPサーバー側で `console.log` を使っていないか確認。stdoutに何かを書くと壊れます。

**コード変換結果が `null` になる**: 名称のスペル・全角半角を確認。マスタにない値だと `null` が返ります。本番ではエラー応答を改善する余地があります (後述)。

**ポートが既に使われている**: macOS/Linuxなら `lsof -i :3001` で使用中プロセスを確認して終了するか、コードのポート番号を変更します。

---

## 9. 次のステップ

ここまでで動く土台ができました。ここから先の発展ポイントを示します。

**本物のAPIへの差し替え**: `gateway.ts` の `ORDER_API` / `CODE_API` 定数を実際のエンドポイントに変えるだけです。認証ヘッダが必要ならfetch呼び出しに `headers` を追加してください。

**コードマスタのプロセスキャッシュ**: 都道府県のような不変マスタは、起動時に丸ごとロードしてメモリに持つと体感速度が劇的に上がります。`createCodeLoader` の前段に `Map` ベースの長期キャッシュを置く実装が定番です。

**変換失敗時の親切なエラー**: `nameToCode` が `null` を返した時に、近い候補をfuzzy matchで提案するとAIが自己修正できます。`fast-fuzzy` などのライブラリを挟むと簡単です。

**ツールの粒度を増やす**: `search_orders` だけでなく `get_order_detail` `update_order_status` のように業務ごとに専用ツールを追加していきます。AIは専用ツールの方が誤呼び出しが少なくなります。

**エラーハンドリングの強化**: 現在は元APIが落ちると `searchOrders` 全体がエラーになります。GraphQLは部分結果+errorsを返せるので、resolverで try/catch して片方だけ成功した状態を返す設計にすると、AIが状況を理解して次の手を打ちやすくなります。

**GraphQL Mesh への移行**: API数が増えてresolverを手書きするのが辛くなったら、GraphQL MeshでOpenAPI定義から自動生成する方式に切り替えると保守がラクになります。スキーマ設計の考え方は同じです。

---

## 参考: ファイル一覧

最終的にこの構成になっていれば完成です。

```
api-agent-mcp/
├── .vscode/
│   └── mcp.json
├── src/
│   ├── mock-order-api.ts
│   ├── mock-code-api.ts
│   ├── gateway.ts
│   └── mcp-server.ts
├── package.json
└── tsconfig.json
```
