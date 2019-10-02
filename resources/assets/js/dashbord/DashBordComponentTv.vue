<template>
  <v-container grid-list-md style="padding:0">
    <v-layout row wrap >
      <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" lg4 md6 xs12>
        <v-card v-bind:class="{ 'green-box': dashboarduser. status === 0, 'yerrow-box': dashboarduser.status === 1 , 'red-box': dashboarduser. status === 2}">
            <div style="display: flex; justify-content: space-between; padding:1px;font-size:20px; background-color:#fff;">
              <div style="text-align:center;font-size:18px;font-weight: bold; ">{{dashboarduser.displayName}}
              <span class="sankou-badge">在席</span>
              </div>
              <P  style="font-size:12px;">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</P>
            </div>
          
          <p style="font-size:14px; padding:1px; margin:0; color:#fff"><v-icon style="font-size:14px; padding:1px; margin:0; color:#fff">chat</v-icon> {{dashboarduser.location}}</p>
          <v-divider color="white"></v-divider>
          <p style="bacground-color:red; font-size:14px; padding:1px; margin:0; color:#fff">{{dashboarduser.location}}</p>
          

        </v-card>
      </v-flex>
    </v-layout> 
  </v-container>  
</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: {},
                comments:[],
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                isModal: false,                        // Modak表示フラグ
                items: [],  
                newComment:'',                           // すべてのアイテム
                search: '',
                headers: [
                { text: 'ID', value: 'locationId' },
                { text: 'LOCATION', value: 'locationName1' },
                { text: 'NAME', value: 'locationName2' },
                { text: 'PHONE', value: 'phoneNo' },
                ],
                commentheaders: [
                  { text: 'Comment', value: 'comment' ,align: 'center', width: '50%'},
                  { text: 'CreateAt', value: 'created_at'  ,width: '30%',align: 'center'},
                  { align: 'center', sortable: false, text: 'delete' ,width: '10%'},
                ],
                desserts: [],
                location: [],
            }
        },

        created() {
          Echo.channel("dashBordChannel")
          .listen("DashBordEvent", (e) => {
             this.getDashbordUser();
          })
          this.getDashbordUser();
        },
      
       computed: {
            // 選択されているオプションは非表示
            filteredItems() {
                let data = this.items;
                let selects = this.selectItems;
                data = data.filter(function (row) {
                    for (var i=0; i<selects.length; i++) {
                        if (selects[i].id == row['id']) {
                            return false;
                            break;
                        }
                    }
                    return true;
                });
                
                return data;
            }
        },
        methods: {
          getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },
        } 
    }
</script>

<style>

.green-box {
  padding:3px;
  background-color: #4CAF50 !important;
}

.yerrow-box {
  padding:3Px;
  background-color: #FF9800 !important;
}

.red-box {
  padding:3px;
  background-color: #E91E63 !important;
}

.zero-box {
  font-size:16px  !important;
  padding: 4px; 
  color:"white" !important;
}

.first-box {
  font-size:16px  !important;
  padding: 4px; 
  color:#E91E63 !important;
}
.second-box {
  font-size:16px  !important;
  padding: 4px; 
  color:rgb(40, 53, 147) !important;
}

.third-box {
  font-size:16px  !important;
  padding: 4px; 
  color:#009688 !important;
}

.color-nomal {
  font-size:12px;
  color: #fff !important;
}

.color-orange {
  font-size:12px;
  color: orange !important;
}

.container fluid fill-height  {
  margin:  0 !important;
  padding: 0 !important;
}

.container.grid-list-md .layout .flex {
    padding: 2px !important;
}

.sankou-badge, .link-badge {
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  font-size: 12px;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
}

.link-badge {
  background-color: #58ACFA; /*青*/
}

.sankou-badge {
  background-color: #4CAF50; /*緑*/
}
</style>
