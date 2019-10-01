<template>
  <v-container grid-list-md >
    <v-layout row wrap >
      <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" lg4 md6 xs12>
        <v-card v-bind:class="{ 'green-box': dashboarduser. status === 0, 'yerrow-box': dashboarduser.status === 1 , 'red-box': dashboarduser. status === 2}">
            <div style="display: flex; justify-content: space-between; padding:4px;font-size:16px; cursor: pointer; background-color:#fff;">
              <div style="text-align:center;font-size:32px;font-weight: bold;" @click="openUserModal(dashboarduser);" >{{dashboarduser.displayName}}
              </div>
              <div>
                  <v-icon color="red">add_alert</v-icon>{{dashboarduser.comentNum}}
                  <v-icon v-bind:class="{ 'zero-box': dashboarduser.belongsName === 'IT企画部', 'first-box': dashboarduser.belongsName === 'システム1課', 'second-box': dashboarduser.belongsName === 'システム2課', 'third-box': dashboarduser.belongsName === 'システム3課'}">
                    location_on
                  </v-icon>
                  {{dashboarduser.belongsName}}  
                   <v-icon v-bind:class="{ 'zero-box': dashboarduser.belongsName === 'IT企画部', 'first-box': dashboarduser.belongsName === 'システム1課', 'second-box': dashboarduser.belongsName === 'システム2課', 'third-box': dashboarduser.belongsName === 'システム3課'}">
                  phone
                  </v-icon>
                  {{dashboarduser.phoneNo}}
              </div>
            </div>
            <div>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn icon  @click="updateUser(dashboarduser)" style="margin:0;padding:0;" v-on="on">
                    <v-icon style="font-size:12px; color:#fff">refresh</v-icon>
                  </v-btn>
                </template>
                <span color:orange>在席状態</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn icon  @click="openLocationModal(dashboarduser);" style="margin:0;padding:0;" v-on="on">
                    <v-icon style="font-size:12px; color:#fff">event</v-icon>
                  </v-btn>
                </template>
                <span color:orange>行先変更</span>
              </v-tooltip>
              <v-tooltip top>
                  <template v-slot:activator="{ on }">
                    <v-btn icon @click="openCommentModal(dashboarduser);" style="margin:0;padding:0;" v-on="on" >
                      <v-icon style="font-size:12px; color:#fff">edit</v-icon>
                    </v-btn>
                  </template>
                  <span color:orange>メモ</span>
              </v-tooltip>
            </div>
          <v-divider></v-divider>
          <p style="bacground-color:red; font-size:28px; padding:1px; margin:0; cursor: pointer; color:#fff">{{dashboarduser.location}}</p>
        </v-card>
      </v-flex>
    </v-layout> 


    <v-dialog v-model="showUserModal" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar style="background-color:#1976D2">
          <v-btn icon dark @click="showUserModal = false">
            <v-icon>clear</v-icon>
          </v-btn>
          <v-toolbar-title style="color:white;">ユーザ詳細</v-toolbar-title>
          <div class="flex-grow-1"></div>
        </v-toolbar>
        <v-flex xs12 sm12 offset-sm12 style="margin:0; color:orange;">
      <v-card style="color:orange;">
         <v-img
            class="white--text"
            height="200px"
            src="https://cdn.vuetifyjs.com/images/cards/docks.jpg"
          >
            <v-card-title class="align-end fill-height">行先変更</v-card-title>
          </v-img>
        <v-list two-line style=" ">

          <v-list-tile>
            <v-list-tile-action>
              <v-icon color="orange">location_on</v-icon>
            </v-list-tile-action>

            <v-list-tile-content>
              <v-list-tile-title style="color:black;">{{dashboarduser["belongsName"]}}</v-list-tile-title>
              <v-list-tile-sub-title style="color:black;">所属</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>
          <v-divider inset></v-divider>

          <v-list-tile>
            <v-list-tile-action>
              <v-icon color="orange">phone</v-icon>
            </v-list-tile-action>

            <v-list-tile-content>
              <v-list-tile-title style="color:black;">{{dashboarduser["phoneNo"]}}</v-list-tile-title>
              <v-list-tile-sub-title style="color:black;">内線</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>

          <v-divider inset></v-divider>

          <v-list-tile>
            <v-list-tile-action>
              <v-icon color="orange">mail</v-icon>
            </v-list-tile-action>

            <v-list-tile-content>
              <v-list-tile-title style="color:black;">{{dashboarduser["mail"]}}</v-list-tile-title>
              <v-list-tile-sub-title style="color:black;">メールアドレス</v-list-tile-sub-title>
            </v-list-tile-content>
          </v-list-tile>

        </v-list>
      </v-card>
    </v-flex>
      </v-card>
    </v-dialog>



    <v-dialog v-model="showCommentModal" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar style="background-color:#1976D2">
          <v-btn icon dark @click="showCommentModal = false">
            <v-icon>clear</v-icon>
          </v-btn>
          <v-toolbar-title style="color:#fff">Comment</v-toolbar-title>
          <div class="flex-grow-1"></div>
        </v-toolbar>
        <v-flex xs12 sm12 offset-sm12 style="margin:0; color:orange;">
          <v-card>
            <v-card-title>
            <v-text-field v-model="newComment" placehlder="Input here..."></v-text-field>
            <v-btn dark color="indigo" @click="addComment()" class="white--text">
                ADD
            </v-btn>
              <v-spacer></v-spacer>

            </v-card-title>
            <v-data-table color:orange
                :headers="commentheaders"
                :items="comments"
                :loading="true"
                class="elevation-1"
                :sort-by="['create_at']"
            >
              <v-progress-linear v-slot:progress color="orange" indeterminate></v-progress-linear>
              <template v-slot:items="comments" color:orange>
                  <tr>
                    <td class="text-xs-center" >{{ comments.item.comment }}</td>
                    <td class="text-xs-center" >{{ comments.item.created_at }}</td>
                    <td class="text-xs-center" xs1>
                      <v-btn flat small fab @click="deleteComment(comments.item.id)"><v-icon color="error">delete</v-icon></v-btn>
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
        </v-flex>
      </v-card>
  </v-dialog>

    <v-dialog v-model="showLocationModal" fullscreen hide-overlay transition="dialog-bottom-transition">
      <v-card>
        <v-toolbar style="background-color:#1976D2">
          <v-btn icon dark @click="showLocationModal = false">
            <v-icon>clear</v-icon>
          </v-btn>
          <v-toolbar-title>{{dashboarduser["firstName"]}}  {{dashboarduser["lastName"]}}</v-toolbar-title>
          <div class="flex-grow-1"></div>
        </v-toolbar>
        <v-flex xs12 sm12 offset-sm12 style="margin:0; color:orange;">
          <v-card style="color:orange;">
    
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
                :items="location"
                :search="search"

                :loading="true"
                class="elevation-1"
                :sort-by="['ID']"
            >
              <v-progress-linear v-slot:progress color="orange" indeterminate></v-progress-linear>
              <template v-slot:items="location" color:orange>
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
        </v-flex>
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
           updateUser (dashboarduser) {

            var id              =dashboarduser.id;
            var status          =dashboarduser.status;
            var displayId       =dashboarduser.displayId;
            var displayName     =dashboarduser.displayName;
            var status          =dashboarduser.status;
            var firstName       =dashboarduser.firstName;
            var lastName        =dashboarduser.lastName;
            var rankNo          =dashboarduser.rankNo;
            var rankName        =dashboarduser.rankName;
            var phoneNo         =dashboarduser.phoneNo;
            var belongsId       =dashboarduser.belongsId;
            var belongsName     =dashboarduser.belongsName;
            var mail            =dashboarduser.mail;
            var locationId      =dashboarduser.locationId;
            var location        =dashboarduser.location;
            var comentNum       =dashboarduser.comentNum;
            var seisouFlag      =dashboarduser.seisouFlag;
            var soujiFlag       =dashboarduser.soujiFlag;
            var garbageFlag     =dashboarduser.garbageFlag;

            if(dashboarduser.status == 2) {
              status = 0;
            } else {
              status++;
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
              comentNum:comentNum,
              seisouFlag:seisouFlag,
              soujiFlag:soujiFlag,
              garbageFlag:garbageFlag,
            }
          
            this.update(userProfile)
          },

          update(userProfile) {
              axios.patch(`/api/dashboarduser/${userProfile.id}`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))
              this.getDashbordUser();
          },
          getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },

          openUserModal(dashboarduser) {
            console.log(dashboarduser);
            this.dashboarduser = dashboarduser;
            this.showUserModal= true;
          },


          openLocationModal(dashboarduser) {
            this.dashboarduser = dashboarduser;
            this.getLocation();
            this.showLocationModal= true;
          },

          openCommentModal(dashboarduser) {
            this.dashboarduser = dashboarduser;
            this.getComment(dashboarduser.id);
            this.showCommentModal= true;
          },

          getLocation() {
             axios.get('/api/location')
            .then(res => this.location = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },

          getComment(id) {
            axios.get('/api/comment')
            .then(res => {
              this.comments = res.data.data.filter(function(item, index){if (item.userId == id) return true;});
              console.log(res.data)
            })
            .catch(error => console.log(error.res.data))
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
            var comentNum       = this.dashboarduser.comentNum;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var soujiFlag       = this.dashboarduser.soujiFlag;
            var garbageFlag     = this.dashboarduser.garbageFlag;

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
              comentNum:comentNum,
              seisouFlag:seisouFlag,
              soujiFlag:soujiFlag,
              garbageFlag:garbageFlag,
            }
            console.log(userProfile);
            this.update(userProfile)
            this.showLocationModal = false;
          },

          addComment() {
            //comment
            const CommentProfie = {
              userId:this.dashboarduser.id,
              comment:this.newComment,
            }
            axios.post('/api/comment', CommentProfie)
            .then(res => {
              console.log(res.data)
              this.getComment(this.dashboarduser.id)
              this.newComment = ""
              }  
            )
            
            //user
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
            var comentNum       = this.dashboarduser.comentNum + 1;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var soujiFlag       = this.dashboarduser.soujiFlag;
            var garbageFlag     = this.dashboarduser.garbageFlag;

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
              comentNum:comentNum,
              seisouFlag:seisouFlag,
              soujiFlag:soujiFlag,
              garbageFlag:garbageFlag,
            }
            console.log(userProfile);
            this.update(userProfile)


          },
          deleteComment(id) {
            //comment
             axios.delete(`/api/comment/${id}`)
            .then(res => {
              console.log(res.data)
              this.getComment(this.dashboarduser.id)
              }  
            )

            //user 
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
            var comentNum       = this.dashboarduser.comentNum - 1;
            var seisouFlag      = this.dashboarduser.seisouFlag;
            var soujiFlag       = this.dashboarduser.soujiFlag;
            var garbageFlag     = this.dashboarduser.garbageFlag;

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
              comentNum:comentNum,
              seisouFlag:seisouFlag,
              soujiFlag:soujiFlag,
              garbageFlag:garbageFlag,
            }
            console.log(userProfile);
            this.update(userProfile)
          }
        }
    }
</script>

<style>

.green-box {
  padding:5px;
  background-color: #009688 !important;
}

.yerrow-box {
  padding:5px;
  background-color: #CDDC39 !important;
}

.red-box {
  padding:5px;
  background-color: #F44336 !important;
}

.zero-box {
  font-size:16px; 
  padding: 1px; 
  color:"white" !important;
}

.first-box {
  font-size:16px; 
  padding: 1px; 
  color:rgb(198, 40, 40) !important;
}
.second-box {
  font-size:16px; 
  padding: 1px; 
  color:rgb(40, 53, 147) !important;
}

.third-box {
  font-size:16px; 
  padding: 1px; 
  color:rgb(46, 125, 50) !important;
}

.color-nomal {
  font-size:12px;
  color: #fff !important;
}

.color-orange {
  font-size:12px;
  color: orange !important;
}

</style>
