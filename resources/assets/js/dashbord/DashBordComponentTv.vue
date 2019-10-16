<template>

    <v-container grid-list-md style="padding:0">
        <v-layout row wrap>
          <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" xl4 lg4 md6 xs12>
            <v-card v-bind:class="{ 
              'zaiseki-box-d' : dashboarduser.status === 0, 
              'riseki-box-d'  : dashboarduser.status === 1, 
              'taiseki-box-d' : dashboarduser.status === 4,
              }"
              @click="openSeatModal()"
            >

                <div style="display: flex; justify-content: space-between; padding:1px;background-color:#fff;">
                  <div style="text-align:center;font-size:32px;font-weight: bold; ">{{dashboarduser.displayName}}
                  <span v-if="dashboarduser.status==0" class="zaiseki-badge"  @click="openStatusModal(dashboarduser)">在席</span>
                  <span v-if="dashboarduser.status==1" class="riseki-badge"   @click="openStatusModal(dashboarduser)">離席</span>
                  <span v-if="dashboarduser.status==4" class="taiseki-badge"  @click="openStatusModal(dashboarduser)">退席</span>
                  <span v-if="dashboarduser.belongsId === 0" class="zero-badge">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 1" class="first-badge">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 2" class="second-badge">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 3" class="third-badge">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  </div>
                  
                </div>
              <p @click="openLocationModal(dashboarduser)" style="cursor: pointer;font-size:24px; padding:1px; margin:0; color:#fff"><v-icon style="font-size:28px; padding:1px; margin:0; color:#fff">transfer_within_a_station</v-icon> {{dashboarduser.location}}</p>
              <v-divider color="white"></v-divider>
              <p @click="openCommentModal(dashboarduser)" style="cursor: pointer;font-size:20px; padding:1px; margin:0; color:#fff; text-overflow:  overflow: hidden; height:32px"><v-icon style="font-size:20px; padding:1px; margin:0; color:#fff;">chat</v-icon> {{dashboarduser.comment}}</p>
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

         <v-dialog 
          v-model="seatMoal" 
          fullscreen
          hide-overlay
        >
            <v-img
              src='/seat.png'
              width="100%"
              style ="background-color:rgba(0,0,0,.9) !important"
              contain="true"
            ></v-img>
  　　    </v-dialog>


    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: {},
                seatMoal:false,
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
        mounted () {
          // releasedAtFromNowを1分ごとに更新する
          window.setInterval(() => {
            this.openSeatModal()
          }, 1000 * 10)
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
          openSeatModal() {
            if (this.seatMoal == true) {
              this.seatMoal = false;
            } else {
              this.seatMoal = true;
            }
            
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

.container {
  padding: 0 !important;
  padding-top: 5px !important;
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
  font-size: 16px !important;
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

.taiseki-badge {
  background-color: #E91E63; 
  cursor: pointer;
}

.zero-badge, .first-badge, .second-badge, .third-badge {
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  font-size: 16px !important;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
}

.zero-badge {
  background-color: #34495e !important; 
}

.first-badge {
  background-color: #c0392b !important; 
}

.second-badge {
  background-color: #3F51B5 !important; 
}

.third-badge {
  background-color: #009688 !important; 
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
  padding:3px;
  color: #fff;
  font-size: 30px;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #FF9800 !important;
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
