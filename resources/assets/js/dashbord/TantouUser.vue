<template>
    <v-container grid-list-md style="padding:0">
  
      <v-card>
          <v-data-table 
              :headers="headers"
              :items="tantou"
              :pagination.sync="pagination"
              class="elevation-1"
              :sort-by="['ID']"
          >
            <v-progress-linear v-slot:progress indeterminate></v-progress-linear>
            <template v-slot:items="tantou">
                <tr @click="updateSelectTantou(tantou.item)">
                  <td class="text-xs" >{{ tantou.item.id }}</td>
                  <td class="text-xs" >
                    <span v-if="tantou.item['id']         == 1" class="zaiseki-badge">ゴミ捨て</span>
                    <span v-if="tantou.item['id']         == 2" class="riseki-badge">掃除機</span>
                    <span v-if="tantou.item['id']         == 3" class="torikomi-badge">サーバ/ミーディング部屋</span>
                    <span v-if="tantou.item['id']         == 4" class="renraku-badge">棚拭き</span>
                    <span v-if="tantou.item['id']         == 5" class="taiseki-badge">火元管理</span>
                  </td>
                  <td class="text-xs" >{{ tantou.item.userName }}</td>
                </tr>
            </template>
          </v-data-table>
      </v-card>

      <v-dialog v-model="showTantouModal" >
        <v-card >
          <v-card-title>
            <p v-if="this.type =='0'"><v-icon  style="color:#4CAF50  !important;">delete_sweep</v-icon>ゴミ捨て当番</p>  
            <p v-if="this.type =='1'"><v-icon  style="color:#FF9800  !important;">transfer_within_a_station</v-icon>  掃除機当番</p> 
            <p v-if="this.type =='2'"><v-icon  style="color:#2196F3  !important;">router</v-icon>  サーバ/ミーディング掃除当番</p> 
            <p v-if="this.type =='3'"><v-icon  style="color:#9C27B0  !important;">blur_on</v-icon>  棚拭き当番</p> 
            <p v-if="this.type =='4'"><v-icon  style="color:#E91E63  !important;">power</v-icon>  火元管理当番</p> 
          
          <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details  
            >
          </v-text-field>
          </v-card-title>
          <v-data-table 
              :headers="header"
              :items="tantouUser"
              :search="search"
              :pagination.sync="pagination"
              class="elevation-1"
              :sort-by="['ID']"
          >
            <v-progress-linear v-slot:progress indeterminate></v-progress-linear>
            <template v-slot:items="tantou">
                <tr @click="updateTantou(tantou.item)">
                  <td class="text-xs" >{{ tantou.item.id }}</td>
                  <td class="text-xs" >{{ tantou.item.userName }}</td>
                </tr>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
          </v-data-table>
        </v-card>
　　  </v-dialog>
    </v-container>




</template>

<script>
    export default {
        data() {
            return {
                tantou:[],
                tantouUser:[],
                selectTantou:[],
                showwTantouModal:false,
                type:'',
                comment:'',
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                showTantouModal:false,
                search: '',
                himotoUser:'',
                items: [],  
 
                headers: [
                  { text: 'ID'        , value: 'id'       , width: '5%' },
                  { text: '当番'      , value: 'userName' , width: '25%'},
                  { text: '担当' 　, value: 'gomiFlag'    , width: '70%'},
                ],
                header:[
                  { text: 'ID'        , value: 'id'       , width: '5%' },
                  { text: '氏名'      , value: 'userName' , width: '95%'},
                ],
                pagination: {
                  rowsPerPage: 25
                },
            }
        },
        created() {
          this.getTantou();
        },
        methods: {
          getTantouUser() {
             axios.get('/api/tantouUser')
            .then(res => this.tantouUser = res.data.data)
            .catch(error => console.log(error.res.data))
          },
          getTantou() {
             axios.get('/api/tantou')
            .then(res => this.tantou = res.data.data)
            .catch(error => console.log(error.res.data))
          },

          updateSelectTantou(tantou) {
            this.getTantou();
            this.getTantouUser();
            this.type = tantou['id'];
            this.showTantouModal = true;
          },
          updateTantou(u) {
            var id       = this.type;
            var userName = u['userName'];

            if (u['userName'] == '削除') {
              userName = "-";
            }
 
            const userProfile = {
              id:id,
              userName:userName
            };
            this.update(userProfile); 
            this.showTantouModal = false;
          },
          update(userProfile) {
              axios.patch(`/api/tantou/${userProfile.id}`, userProfile)
              .then(this.getTantou())
              .catch(error => console.log(error.res))
          },
        }
    }
</script>

<style>
  .zaiseki-badge {
    background-color: #4CAF50; 
  }
  .riseki-badge {
    background-color: #FF9800; 
  }
  .torikomi-badge {
    background-color: #2196F3; 
  }
  .renraku-badge {
    background-color: #9C27B0; 
  }
  .taiseki-badge {
    background-color: #E91E63; 
  }
  .zaiseki-list {
  padding:3px;
  color: #fff;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #4CAF50 !important;
}
.riseki-list {
  padding:3Px;
  color: #fff;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #FF9800 !important;
}
.torikomi-list {
  margin: auto;
  padding:3px;
  text-align: center;
  color: #fff;
  font-weight: bold; 
  cursor: pointer;
  background-color: #2196F3 !important;
}
.renraku-list {
  padding:3Px;
  text-align: center;
  color: #fff;
  font-weight: bold; 
  cursor: pointer;
  background-color: #9C27B0 !important;
}
.taiseki-list {
  padding:3px;
  color: #fff;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #E91E63 !important;
}
.zaiseki-list:hover {
opacity: 0.5 ;
}
.riseki-list:hover {
opacity: 0.5 ;
}
.torikomi-list:hover {
opacity: 0.5 ;
}
.renraku-list:hover {
opacity: 0.5 ;
}
.taiseki-list:hover {
opacity: 0.5 ;
}
</style>