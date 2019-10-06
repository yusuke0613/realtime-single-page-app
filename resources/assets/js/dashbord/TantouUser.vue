<template>
    <v-container grid-list-md style="padding:0">
  
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
              :headers="headers"
              :items="tantou"
              :search="search"
              :pagination.sync="pagination"
              :loading="true"
              class="elevation-1"
              :sort-by="['ID']"
          >
            <v-progress-linear v-slot:progress indeterminate></v-progress-linear>
            <template v-slot:items="tantou">
                <tr @click="updateSelectTantou(tantou.item)">
                  <td class="text-xs" >{{ tantou.item.id }}</td>
                  <td class="text-xs" >{{ tantou.item.userName }}</td>
                  <td class="'text-xs">
                    <span v-if="tantou.item['gomiFlag']          == 1" class="zaiseki-badge">ゴミ捨て当番</span>
                    <span v-if="tantou.item['souziFlag']         == 1" class="riseki-badge">掃除機当番</span>
                    <span v-if="tantou.item['serverSoujiFlag']   == 1" class="torikomi-badge">サーバ/ミーディング掃除当番</span>
                    <span v-if="tantou.item['seisouFlag']        == 1" class="renraku-badge">棚拭き当番</span>
                    <span v-if="tantou.item['hinomotoFlag']      == 1" class="taiseki-badge">火元管理当番</span>
                  </td> 
                </tr>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
          </v-data-table>
        </v-card>
        <v-dialog v-model="showwTantouModal" >
            <v-card >
              <div class="zaiseki-list"   @click="updateTantou(0)">ゴミ捨て当番</div>
              <div class="riseki-list"    @click="updateTantou(1)">掃除機当番</div>
              <div class="torikomi-list"  @click="updateTantou(2)">サーバ/ミーディング掃除当番</div>
              <div class="renraku-list"   @click="updateTantou(3)">棚拭き当番</div>
              <div class="taiseki-list"   @click="updateTantou(4)">火元管理当番</div>
            </v-card>
  　　    </v-dialog>

     
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
              :headers="headers"
              :items="tantou"
              :search="search"
              :pagination.sync="pagination"
              class="elevation-1"
              :sort-by="['ID']"
          >
            <v-progress-linear v-slot:progress indeterminate></v-progress-linear>
            <template v-slot:items="tantou">
                <tr @click="updateSelectLocation(tantou.item)">
                  <td class="text-xs" >{{ tantou.item.id }}</td>
                  <td class="text-xs" >{{ tantou.item.userName }}</td>
                  <td class="'text-xs">
                    <span v-if="tantou.item['gomiFlag']          == 1" class="zaiseki-badge">ゴミ捨て当番</span>
                    <span v-if="tantou.item['souziFlag']         == 1" class="riseki-badge">掃除機当番</span>
                    <span v-if="tantou.item['serverSoujiFlag']   == 1" class="torikomi-badge">サーバ/ミーディング掃除当番</span>
                    <span v-if="tantou.item['seisouFlag']        == 1" class="renraku-badge">棚拭き当番</span>
                    <span v-if="tantou.item['hinomotoFlag']      == 1" class="taiseki-badge">火元管理当番</span>
                  </td> 
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
                dashboardusers: {},
                tantou:[],
                selectTantou:[],
                showwTantouModal:false,
                type:'',
                comment:'',
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                showTantouModal:false,
                gomiUser:'',
                seisouUser:'',
                serverUser:'',
                tanahukiUser:'',
                search: '',
                himotoUser:'',
                items: [],  

                newComment:'', 
                headers: [
                  { text: 'ID'        , value: 'id' },
                  { text: '氏名'      , value: 'userName' },
                  { text: '当番' 　, value: 'gomiFlag' },
                ],
                pagination: {
                  rowsPerPage: 25
                },
            }
        },

        created() {
          this.getTantouUser();
        },
        mounted :function(){
             axios.get('/api/tantou')
            .then(res => this.tantou = res.data.data)
            .catch(error => console.log(error.res.data))

        },
        methods: {
          getTantouUser() {
             axios.get('/api/tantou')
            .then(res => this.tantou = res.data.data)
            .catch(error => console.log(error.res.data))
          },
          openTantouModal(type) {
            this.type = type;
            this.getTantouUser();
            this.showTantouModal= true;
          },
          updateSelectTantou(tantou) {
            this.selectTantou = tantou;
            this.showwTantouModal = true;
          },

          updateTantou (u) {
            var gomiUser = this.tantou.filter(function(item, index){
              if (item.gomiFlag == 1) return true;
            });

            var souziUser = this.tantou.filter(function(item, index){
              if (item.souziFlag == 1) return true;
            });

            var seisouUser = this.tantou.filter(function(item, index){
              if (item.seisouFlag == 1) return true;
            });

            var hinomotoUser = this.tantou.filter(function(item, index){
              if (item.hinomotoFlag == 1) return true;
            });

            var serverSoujiUser = this.tantou.filter(function(item, index){
              if (item.serverSoujiFlag == 1) return true;
            });

            var id           = 0;
            var gomiFlag     = 0;
            var souziFlag    = 0;
            var seisouFlag   = 0;
            var serverSoujiFlag = 0;
            var hinomotoFlag = 0;

  
            if (gomiUser.length != 0) {
              if (gomiUser[0]['id'] == this.selectTantou['id']) {
                gomiFlag = 1;
              }
            }
            if (souziUser.length != 0) {
              if (souziUser[0]['id'] == this.selectTantou['id']) {
                souziFlag    = 1;
              }
            }
            if (seisouUser.length != 0) {
              if (seisouUser['id'] == this.selectTantou['id']) {
                seisouFlag   = 1;
              }
            }
            if (serverSoujiUser.length != 0) {
              if (serverSoujiUser['id'] == this.selectTantou['id']) {
                serverSoujiFlag = 1;
              }
            }
            if (hinomotoUser.length != 0) {
              if (hinomotoUser['id'] == this.selectTantou['id']) {
                hinomotoFlag = 0;
              }
            }
            
            if (u == 0) {
              id = gomiUser[0]['id'];
              var userProfile = {
                id:id,
                gomiFlag:gomiFlag,
                souziFlag:souziFlag,
                seisouFlag:seisouFlag,
                hinomotoFlag:hinomotoFlag,
                serverSoujiFlag:serverSoujiFlag
              };
        
              this.update(userProfile);
              userProfile = {
                id:this.selectTantou['id'],
                gomiFlag:1,
                souziFlag:souziFlag,
                seisouFlag:seisouFlag,
                hinomotoFlag:hinomotoFlag,
                serverSoujiFlag:serverSoujiFlag
              };
              this.update(userProfile);
              console.log(userProfile);
            }

             if (u == 1) {
              id = souziUser[0]['id'];
              var userProfile = {
                id:id,
                gomiFlag:gomiFlag,
                souziFlag:souziFlag,
                seisouFlag:seisouFlag,
                hinomotoFlag:hinomotoFlag,
                serverSoujiFlag:serverSoujiFlag
              };
              this.update(userProfile);
              userProfile = {
                id:this.selectTantou['id'],
                gomiFlag:gomiFlag,
                souziFlag:1,
                seisouFlag:seisouFlag,
                hinomotoFlag:hinomotoFlag,
                serverSoujiFlag:serverSoujiFlag
              };
              this.update(userProfile);
              console.log(userProfile);
            }
            this.getTantouUser();   
          },

          update(userProfile) {
              axios.patch(`/api/tantou/${userProfile.id}`, userProfile)
              .then(res =>  console.log(res.data))
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