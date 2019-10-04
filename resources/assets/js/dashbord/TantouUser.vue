<template>
      <v-flex>
        <v-card xs12 class="m-3 px-3">
  
        <v-flex lg3 md12 xs12 style="padding:0 !important">
        <v-icon style="cursor:pointer;　color:#4CAF50 !important;" @click="openTantouModal(0)">delete_sweep</v-icon>  ゴミ捨て当番
        </v-flex>
        <v-flex lg3 md12 xs12 style="padding:0 !important">
          <v-text-field
            v-model="gomiUser"
            disabled=disabled 
          ></v-text-field>
        </v-flex>

        <v-flex lg3 md12 xs12 style="padding:0 !important">
        <v-icon style=" cursor:pointer; color:#FF9800  !important;" @click="openTantouModal(1)">transfer_within_a_station</v-icon>  掃除機当番
        </v-flex>
        <v-flex lg3 md12 xs12 style="padding:0 !important">
          <v-text-field
            v-model="seisouUser"
            disabled=disabled 
          ></v-text-field>
        </v-flex>

        <v-flex lg3 md12 xs12 style="padding:0 !important">
          <v-icon style=" cursor:pointer;　color:#2196F3  !important;" @click="openTantouModal(2)">router</v-icon>  サーバ/ミーディング掃除当番
          </v-flex>
          <v-flex lg3 md12 xs12 style="padding:0 !important">
            <v-text-field
              v-model="serverUser"
              disabled=disabled 
            ></v-text-field>
        </v-flex>

         <v-flex lg3 md12 xs12 style="padding:0 !important">
          <v-icon style=" cursor:pointer;　color:#9C27B0  !important;" @click="openTantouModal(3)">blur_on</v-icon>  棚拭き当番
          </v-flex>
          <v-flex lg3 md12 xs12 style="padding:0 !important">
            <v-text-field
              v-model="tanahukiUser"
              disabled=disabled 
            ></v-text-field>
        </v-flex>

         <v-flex lg3 md12 xs12 style="padding:0 !important">
          <v-icon style=" cursor:pointer;　color:#E91E63  !important;" @click="openTantouModal(4)">power</v-icon>  火元管理当番
          </v-flex>
          <v-flex lg3 md12 xs12 style="padding:0 !important">
            <v-text-field
              v-model="himotoUser"
              disabled=disabled 
            ></v-text-field>
        </v-flex>
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
                <tr @click="updateSelectLocation(tantou.item)">
                  <td class="text-xs" >{{ tantou.item.id }}</td>
                  <td class="text-xs" >{{ tantou.item.userName }}</td>
                  <td class="'text-xs">
                    <span v-if="tantou.item['gomiFlag']          == 1" class="zaiseki-badge">ゴミ捨て当番</span>
                    <span v-if="tantou.item['souziFlag']         == 1" class="riseki-badge">掃除機当番</span>
                    <span v-if="tantou.item['serverSoujiFlag']   == 1" class="torikomi-badge">サーバ/ミーディング掃除当番</span>
                    <span v-if="tantou.item['seisouFlag']             == 1" class="renraku-badge">棚拭き当番</span>
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
              :loading="true"
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
                    <span v-if="tantou.item['seisouFlag']             == 1" class="renraku-badge">棚拭き当番</span>
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
        </v-card>
      </v-flex>
  




</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: [],
                tantou:[],
                tantous:{},
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
      
          console.log(this.tantous);
        },
        mounted :function(){
             axios.get('/api/tantou')
            .then(res => this.tantou = res.data.data)
            .catch(error => console.log(error.res.data))
         
        },
        methods: {

           getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },

          getTantouUser() {
             axios.get('/api/tantou')
            .then(res => this.tantous = res.data.data)
            .catch(error => console.log(error.res.data))
             
             /*
            var gomiUser = this.tantou['data'].filter(function(item, index){
              if (item.gomiFlag == 1) return true;
            });
            console.log(gomiUser);
            console.log(this.tantou);
            var seisouUser = this.tantou.filter(function(item, index){
              if (item.souziFlag == '1') return true;
            });

            var serverUser = this.tantou.filter(function(item, index){
              if (item.serverSoujiFlag == '1') return true;
            });

            var tanahukiUser = this.tantou.filter(function(item, index){
              if (item.seisouFlag == '1') return true;
            });

            var himotoUser = this.tantou.filter(function(item, index){
              if (item.hinomotoFlag == '1') return true;
            });
      
            this.gomiUser     = gomiUser;
            this.seisouUser   = seisouUser;
            this.serverUser   = serverUser;
            this.tanahukiUser = tanahukiUser;
            this.himotoUser   = himotoUser;
            */
          },
          openTantouModal(type) {
            this.type = type;
            this.getTantouUser();
            this.showTantouModal= true;
            console.log(this.tantous);
          },


          updateSelectLocation (u) {
            var id              = this.dashboarduser.id;
            var status          = this.dashboarduser.status;
            var displayId       = this.dashboarduser.displayId;
            var displayName     = this.dashboarduser.displayName;
            var status          = this.dashboarduser.status;
            var firstName       = this.dashboarduser.firstName;
            var lastName        = this.dashboarduser.lastName;
            var rankNo          = this.dashboarduser.rankNo;
            var rankName        = this.dashboarduser.rankName;
            var phoneNo         = this.dashboarduser.phoneNo;
            var belongsId       = this.dashboarduser.belongsId;
            var belongsName     = this.dashboarduser.belongsName;
            var mail            = this.dashboarduser.mail;
            var locationId      = u.locationId;
            var location        = u.locationName2;
            var locationPhon    = u.phoneNo;
            var comentNum       = this.dashboarduser.comentNum;
            var comment      = this.dashboarduser.comment;

            const userProfile = {
              id:id,
              displayId:displayId,
              displayName:displayName,
              status:status,
              firstName:firstName,
              lastName:lastName,
              rankNo:rankNo,
              rankName:rankName,
              phoneNo:phoneNo,
              belongsId:belongsId,
              belongsName:belongsName,
              mail:mail,
              locationId:locationId,
              location:location,
              locationPhon:locationPhon,
              comentNum:comentNum,
              comment:comment,

            }
            console.log(userProfile);
            this.update(userProfile)
            this.showLocationModal = false;
          },

          update(userProfile) {
              axios.patch(`/api/dashboarduser/${userProfile.id}`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))
              this.getDashbordUser();
              this.showUpdateUserModal = false
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
</style>