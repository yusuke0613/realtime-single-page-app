<template>

    <v-container grid-list-md style="padding:0">
        <v-layout row wrap>
          <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" xl3 lg4 md6 xs12>
             <v-card v-bind:class="{ 
              'zaiseki-box-d' : dashboarduser.status === 0, 
              'riseki-box-d'  : dashboarduser.status === 1, 
              'taiseki-box-d' : dashboarduser.status === 4,
              }"
            >
                <div style="display: flex; justify-content: space-between; padding:3px;font-size:24px; background-color:#fff;" >
                  <div style="text-align:center;font-size:20px;font-weight: bold; ">{{dashboarduser.displayName}}
                  <span v-if="dashboarduser.gomiFlag==1"     class="zaiseki-badge">ゴミ</span>
                  <span v-if="dashboarduser.souziFlag==1"    class="riseki-badge">掃除</span>
                  <span v-if="dashboarduser.seisouFlag==1"   class="torikomi-badge">棚拭き</span>
                  <span v-if="dashboarduser.serverFlag==1"   class="renraku-badge">サーバ</span>
                  <span v-if="dashboarduser.hinomotoFlag==1" class="taiseki-badge">火元</span>
                  </div>
                  <span v-if="dashboarduser.belongsId === 0" class="zero-badge-i">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 1" class="first-badge-i">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 2" class="second-badge-i">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 3" class="third-badge-i">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                </div>
                
               <div>
              </div>
              <v-divider color="white"></v-divider>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-icon  @click="openStatusModal(dashboarduser)" v-on="on" style="padding-bottom:3px !important;font-size:10px !important; color:#fff;margin:0 !important;padding-left:20px !important;">refresh</v-icon>
                </template>
                <span color:orange>在席状態</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-icon @click="openLocationModal(dashboarduser);" v-on="on" style="padding-bottom:3px !important;font-size:10px !important; color:#fff;padding-left:20px !important;">transfer_within_a_station</v-icon>
                </template>
                <span color:orange>行先変更</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-icon @click="openCommentModal(dashboarduser);" v-on="on" style="padding-bottom:3px !important;font-size:10px !important; color:#fff;padding-left:20px !important;">chat</v-icon>
                </template>
                <span color:orange>メモ</span>
              </v-tooltip>
               <v-tooltip top>
                <template v-if="dashboarduser.rankNo<5" v-slot:activator="{ on }">
                  <v-icon @click="openToubanModal(dashboarduser);" v-on="on" style="padding-bottom:3px !important;font-size:10px !important; color:#fff;padding-left:20px !important;">event</v-icon>
                </template>
                <span color:orange>当番</span>
              </v-tooltip>
              <v-divider color="white"></v-divider>
              <p style="font-size:16px; padding:1px; margin:0; color:#fff">{{dashboarduser.location}}</p>
              <v-divider color="white"></v-divider>
              <p style="font-size:16px; padding:1px; margin:0; color:#fff; text-overflow:  overflow: hidden; height:22px"> {{dashboarduser.comment}}</p>
            </v-card>
          </v-flex>
        </v-layout>


      <v-dialog v-model="showLocationModal" >
            <v-card >
              <v-card-title>
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
              <v-data-table color:orange
                  :headers="headers"
                  :items="locations"
                  :search="search"
                  :rows-per-page-items='[25,50,{"text":"All","value":-1}]'
                  :loading="true"
                  class="elevation-1"
                  :sort-by="['ID']"
              >
                <v-progress-linear v-slot:progress indeterminate></v-progress-linear>
                <template v-slot:items="location">
                    <tr @click="updateSelectLocation(location.item)">
                    <td class="text-xs" >{{ location.item.locationId }}</td>
                    <td class="text-xs" >{{ location.item.locationName1 }}</td>
                    <td class="text-xs" >{{ location.item.locationName2 }}</td>
                    <td class="text-xs" >{{ location.item.phoneNo }}</td>
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
          <v-dialog v-model="showStatusModal" width="500">
            <v-card>
                <div class="zaiseki-list"   @click="updateStatus(0)"><v-icon style="color:#fff;font-size:30px;">accessibility_new</v-icon> 在席</div>
                <div class="riseki-list"    @click="updateStatus(1)"><v-icon style="color:#fff;font-size:30px;">transfer_within_a_station</v-icon>離席</div>
                <div class="taiseki-list"   @click="updateStatus(4)"><v-icon style="color:#fff;font-size:30px;">home</v-icon>退席</div>
            </v-card>
  　　    </v-dialog>

           <v-dialog v-model="showCommentModal" >
             <v-card style="padding:10px !important" @click.stop>
            <div style="text-overflow: ellipsis !important; height: 80px !important;"> 
            <v-text-field
              v-model="comment"
              :counter="30"
              overflow-y-hidden
            ></v-text-field>
            </div>
            <v-btn 
                color="blue-grey darken-4"
                type="button"
                style="color:#fff"
                @click="updateComment();"
            >Update</v-btn>
             </v-card>
  　　    </v-dialog>

          <v-dialog v-model="toubanModal" width="500">
            <v-card>
                <div class="zaiseki-list"    @click="gomi()"><v-icon style="color:#fff;font-size:30px;">delete_sweep</v-icon> ゴミ当番</div>
                <div class="torikomi-list"   @click="tanafuki()"><v-icon style="color:#fff;font-size:30px;">transfer_within_a_station</v-icon>棚拭き</div>
                <div class="renraku-list"    @click="server()"><v-icon style="color:#fff;font-size:30px;">router</v-icon>サーバ掃除</div>
                <div class="riseki-list"     @click="souziki()"><v-icon style="color:#fff;font-size:30px;">blur_on</v-icon>掃除機</div>
                <div class="taiseki-list"    @click="himoto()"><v-icon style="color:#fff;font-size:30px;">power</v-icon>火元当番</div>
            </v-card>
  　　    </v-dialog>

    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: {},
                comments:[],
                comment:'',
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                showStatusModal:false,
                isModal: false,   
                showCommentModal:false,   
                toubanModal:false,
                items: [],  
                locations:[],
                newComment:'', 
                search:'',
                headers: [
                  { text: 'ID', value: 'locationId' },
                  { text: 'LOCATION', value: 'locationName1' },
                  { text: 'NAME', value: 'locationName2' },
                  { text: 'PHONE', value: 'phoneNo' },
                ],
            }
        },

        created() {
          Echo.channel("dashBordChannel")
          .listen("DashBordEvent", (e) => {
             this.getDashbordUser();
          })
          this.getDashbordUser();
        },
        methods: {
          getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },
          getLocation() {
             axios.get('/api/location')
            .then(res => this.locations = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },
          openLocationModal(dashboarduser) {
            this.dashboarduser = dashboarduser;
            this.getLocation();
            this.showLocationModal= true;
          },


          openToubanModal(dashboarduser) {
            this.dashboarduser = dashboarduser;
            this.toubanModal = true;
          },

          openStatusModal(dashboarduser){
            this.dashboarduser = dashboarduser;
            this.showStatusModal= true;
          },

          openCommentModal(dashboarduser) {
            this.dashboarduser    = dashboarduser;
            this.comment          = dashboarduser.comment;
            this.showCommentModal = true;
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
            var comment         = this.dashboarduser.comment;
            var gomiFlag        = this.dashboarduser.gomiFlag;
            var souziFlag       = this.dashboarduser.souziFlag;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            var serverFlag      = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,
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

          updateStatus(status) {
            var id              = this.dashboarduser.id;
            var displayId       = this.dashboarduser.displayId;
            var displayName     = this.dashboarduser.displayName;
            var status          = status;
            var firstName       = this.dashboarduser.firstName;
            var lastName        = this.dashboarduser.lastName;
            var rankNo          = this.dashboarduser.rankNo;
            var rankName        = this.dashboarduser.rankName;
            var phoneNo         = this.dashboarduser.phoneNo;
            var belongsId       = this.dashboarduser.belongsId;
            var belongsName     = this.dashboarduser.belongsName;
            var mail            = this.dashboarduser.mail;
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            if (status == 0) {
              var locationId      = 999;
              var location        = '自席';
            } 
            if (status == 4) {
              var locationId      = 1000;
              var location        = '休み';
            }

            var locationPhon    = this.dashboarduser.locationPhon;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;
            var gomiFlag        = this.dashboarduser.gomiFlag;
            var souziFlag       = this.dashboarduser.souziFlag;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            var serverFlag      = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,

            }
            console.log(userProfile);
            this.update(userProfile)
            this.showStatusModal = false;
          },

          updateComment() {
            var id              = this.dashboarduser.id;
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
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            var locationPhon    = this.dashboarduser.locationPhon;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.comment;
            var gomiFlag        = this.dashboarduser.gomiFlag;
            var souziFlag       = this.dashboarduser.souziFlag;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            var serverFlag      = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,

            }
            console.log(userProfile);
            this.update(userProfile)
            this.showCommentModal = false;
          },

          gomi () {

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
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            var locationPhon    = this.dashboarduser.phoneNo;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;

            if (this.dashboarduser.gomiFlag == 0) {
               var gomiFlag     = 1;
            } else {
               var gomiFlag     = 0;
            }
           
            var souziFlag       = this.dashboarduser.souziFlag;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            var serverFlag      = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,
            }
            axios.patch(`/api/gomi`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))

            this.getDashbordUser();
            this.toubanModal = false;
          },

           tanafuki () {
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
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            var locationPhon    = this.dashboarduser.phoneNo;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;
            var gomiFlag        = this.dashboarduser.gomiFlag;
            var souziFlag       = this.dashboarduser.souziFlag;
            if (this.dashboarduser.seisouFlag == 0) {
               var seisouFlag     = 1;
            } else {
               var seisouFlag     = 0;
            }
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            var serverFlag      = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,
            }
            axios.patch(`/api/seisou`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))

            this.getDashbordUser();
            this.toubanModal = false;
          },

           server () {
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
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            var locationPhon    = this.dashboarduser.phoneNo;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;
            var gomiFlag        = this.dashboarduser.gomiFlag;
            var souziFlag       = this.dashboarduser.souziFlag;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            
            if (this.dashboarduser.serverFlag == 0) {
               var serverFlag     = 1;
            } else {
               var serverFlag     = 0;
            }

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,
            }
            
            axios.patch(`/api/server`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))

            this.getDashbordUser();
            this.toubanModal = false;
          },

           souziki () {
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
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            var locationPhon    = this.dashboarduser.phoneNo;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;
            var gomiFlag        = this.dashboarduser.gomiFlag;

            if (this.dashboarduser.souziFlag == 0) {
               var souziFlag     = 1;
            } else {
               var souziFlag     = 0;
            }
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var hinomotoFlag    = this.dashboarduser.hinomotoFlag;
            var serverFlag      = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,
            }
            axios.patch(`/api/souzi`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))

            this.getDashbordUser();
            this.toubanModal = false;
          },


           himoto () {
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
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            var locationPhon    = this.dashboarduser.phoneNo;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;


            var gomiFlag       = this.dashboarduser.gomiFlag;
            var souziFlag      = this.dashboarduser.souziFlag;
            var seisouFlag     = this.dashboarduser.seisouFlag;
            if (this.dashboarduser.hinomotoFlag == 0) {
               var hinomotoFlag     = 1;
            } else {
               var hinomotoFlag     = 0;
            }
            var serverFlag     = this.dashboarduser.serverFlag;

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
              gomiFlag:gomiFlag,
              souziFlag:souziFlag,
              seisouFlag:seisouFlag,
              hinomotoFlag:hinomotoFlag,
              serverFlag:serverFlag,
            }
           axios.patch(`/api/hinomoto`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))

            this.getDashbordUser();
            this.toubanModal = false;
          },


        } 
    }
</script>

<style>

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

.zaiseki-badge, .riseki-badge, .torikomi-badge, .renraku-badge, .taiseki-badge {

  margin-right: 1px !important;
  margin-left: 1px !important;
  font-size: 12px !important;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
}

.zaiseki-badge {
  background-color: #4CAF50; 
  cursor: pointer;
}

.riseki-badge {
  background-color: #FF9800; 
  cursor: pointer;
}

.torikomi-badge {
  background-color: #2196F3; 
  cursor: pointer;
}

.renraku-badge {
  background-color: #9C27B0; 
  cursor: pointer;
}
.taiseki-badge {
  background-color: #E91E63; 
  cursor: pointer;
}

.riseki-box {
  background-color: #4CAF50; 
}

.zaiseki-box {
  padding:3px;
  background-color: #34495e !important;
}

.riseki-box {
  padding:3Px;
  background-color: #c0392b !important;
}

.torikomi-box {
  padding:3px;
  background-color: #3F51B5 !important;
}

.renraku-box {
  padding:3Px;
  background-color: #009688 !important;
}

.taiseki-box {
  padding:3px;
  background-color: #E91E63 !important;
}

.zaiseki-list {
  padding:3px;
  font-size: 30px;
  color: #fff;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #4CAF50 !important;
}

.riseki-list {
  padding:3Px;
  color: #fff;
  font-size: 30px;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #FF9800 !important;
}

.torikomi-list {
  margin: auto;
  padding:3px;
  font-size: 30px;
  text-align: center;
  color: #fff;
  font-weight: bold; 
  cursor: pointer;
  background-color: #2196F3 !important;
}

.renraku-list {
  padding:3Px;
  font-size: 30px;
  text-align: center;
  color: #fff;
  font-weight: bold; 
  cursor: pointer;
  background-color: #9C27B0 !important;
}

.taiseki-list {
  padding:3px;
  font-size: 30px;
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

.zaiseki-box-d {
  padding:3px;
  background-color:#4CAF50 !important;
}

.riseki-box-d {
  padding:3Px;
  background-color:#FF9800 !important;
}

.taiseki-box-d {
  padding:3px;
  background-color:#E91E63 !important;
}


.zero-badge-i, .first-badge-i, .second-badge-i, .third-badge-i {
  /*
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
  */
  font-size: 14px !important;
  font-weight: bold !important;
}

.zero-badge-i {
  color: #34495e !important; 
}

.first-badge-i {
  color: #c0392b !important; 
}

.second-badge-i {
  color: #3F51B5 !important; 
}

.third-badge-i {
  color: #009688 !important; 
}

</style>
