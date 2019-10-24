<template>
  <v-form>
    <v-container>
      <v-row>
        <v-radio-group v-model="row" row>
            <v-radio label="在籍" value="radio-1"></v-radio>
            <v-radio label="退席" value="radio-2"></v-radio>
        </v-radio-group>

      　<v-text-field
          id="tagNo"
          label="Card ID"
          solo
        ></v-text-field>
      </v-row>
    </v-container>
  </v-form>
        
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
                showCommentModal:false,                     // Modak表示フラグ
                items: [],  
                locations:[],
                row:'radio-1'

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

          openStatusModal(dashboarduser){
            this.dashboarduser = dashboarduser;
            this.showStatusModal= true;
          },

          openCommentModal(dashboarduser) {
            this.dashboarduser = dashboarduser;
            this.comment = dashboarduser.comment;
            this.showCommentModal= true;
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
            this.showCommentModal = false;
          }

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
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  font-size: 12px;
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

</style>
