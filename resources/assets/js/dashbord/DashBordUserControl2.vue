 <template>
      <v-flex>
        <v-card xs12 class="m-3 px-3">
          <v-data-table
            :headers="headers"
            :items="dashboardusers"
            :pagination.sync="pagination"
            :items-per-page="50"
            :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
            :loading="loading"
            :search="search"
            class="elevation-0 p-1"
          >
            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

            <template slot="items" slot-scope="props">
              <tr>
                <td :class="'text-xs-' + headers[0].align" style="white-space: nowrap;">
                  <span v-if="props.item['status'] == 0" class="zaiseki-badge">在席</span>
                  <span v-if="props.item['status'] == 1" class="riseki-badge">離席中</span>
                  <span v-if="props.item['status'] == 2" class="torikomi-badge">取り込み中</span>
                  <span v-if="props.item['status'] == 3" class="renraku-badge">連絡不可</span>
                  <span v-if="props.item['status'] == 4" class="taiseki-badge">退席中</span>
                </td>
                <td class="text-xs-center" xs1><v-btn flat small fab @click="updateUserModal(props.item)"><v-icon color="success">edit</v-icon></v-btn></td>
                <td :class="'text-xs-' + headers[1].align" style="white-space: nowrap;" v-text="props.item['firstName']　+ ' ' +props.item['lastName'] + ' (' + props.item['phoneNo'] + ')'"></td>
                <td :class="'text-xs-' + headers[2].align" style="white-space: nowrap;" v-text="props.item['location'] + ' (' + props.item['locationPhon'] + ')'"></td>
                <td :class="'text-xs-' + headers[2].align" style="white-space: nowrap;" v-text="props.item['belongsName']"></td>
                <td :class="'text-xs-' + headers[2].align" style="white-space: nowrap;" v-text="props.item['comment']"></td>
              </tr>
            </template>
          </v-data-table>
        </v-card>

        <v-dialog v-model="showUpdateUserModal" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
              <v-toolbar color="blue-grey darken-4">
                <v-btn icon dark @click="showUpdateUserModal = false">
                  <v-icon>clear</v-icon>
                </v-btn>
                <v-toolbar-title style="color:#fff">User Update</v-toolbar-title>
                <div class="flex-grow-1"></div>
              </v-toolbar>
               <v-form v-model="valid">
                <v-container>
                  <v-layout row wrap >
                    <v-flex lg12 md12 xs12 style="padding:0 !important;padding-top:10px !important">
                    <v-icon color="blue-grey darken-4">account_circle</v-icon>  Status
                    
                    </v-flex>
                    <v-flex lg4 md4 xs4 style="padding:0 !important;" class="tt">
                      <v-select
                        class="tt"
                        v-model="selectedBelongs"
                        icon=transfer_within_a_station
                        item-text="label"
                        item-value="value"
                       :items="items"
             
                      ></v-select>
                    </v-flex>

                    <v-flex lg12 md12 xs12 style="padding:0 !important">
                    <v-icon color="blue-grey darken-4" style=" cursor:pointer;" @click="openLocationModal">transfer_within_a_station</v-icon>  Locaton
                    </v-flex>
                    <v-flex lg12 md12 xs12 style="padding:0 !important">
                      <v-text-field
                        v-model="locationDetail"
                        disabled=disabled 
                      ></v-text-field>
                    </v-flex>

                    <v-flex lg12 md12 xs12 style="padding:0 !important">
                    <v-icon color="blue-grey darken-4">chat</v-icon>  Comment
                    </v-flex>
                    <v-flex lg12 md12 xs12 style="padding:0 !important">
                      <v-text-field
                        v-model="comment"
                        :rules="nameRules"
                        :counter="30"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-flex lg12 md12 xs12>
                  <v-btn 
                      color="blue-grey darken-4"
                      type="button"
                      style="color:#fff"
                      @click="updateUser();"
                  >Update</v-btn>
                  </v-flex>

                </v-container>
              </v-form>
            </v-card>
        </v-dialog>

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
                  :headers="headers2"
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
      </v-flex>
      
    </template>
<script>

    export default {

        data() {
            return {
                dashboardusers: [],
                headers: [
                  {text: '状態'      　, align: 'center', value: 'status' ,width: '5%'},
                  {text: '編集'  　    , align: 'center',width: '5%'},
                  {text: '名前 (内線)' , align: 'center',width: '20%'},
                  {text: '場所 (内線)' , align: 'center',width: '20%'},
                  {text: '所属'　　    , align: 'center',width: '10%'},
                  {text: 'メモ'      　, align: 'center',width: '40%'},
                ],
                loading: false,
                search: '',
 
                pagination: { sortBy: 'name', descending: false, },
                setsearch(id) {
                  this.search = id
                },
                postItem: { id: 1, displayId:'1', name: 'aのitem' },
                showUpdateUserModal: false,
                valid: false,
                showLocationModal:false,

                displayId: '',
                displayName:'',
                firstName: '',
                lastName: '',
                phoneNo:'',
                Rank:'',
                belongsName:'',
                status:'',
                comment:'',
                statusName:'',
                locationName:'',
                locationDetail:'',
                locPhoneNo:'',
                Belongs: { label: 'IT企画部', value: '0' },
                nameRules: [
                  v => !!v || 'Name is required',
                  v => v.length <= 10 || 'Name must be less than 10 characters',
                ],
 
                selectedBelongs: { label: '在籍' , value: '0' },
                items: [
                  {label:'在籍'      , value:0},
                  {label:'離席中'    , value:1},
                  {label:'取り込み中' , value:2},
                  {label:'連絡不可'  , value:3},
                  {label:'退席中'    , value:4},
                ],
               
                search: '',
                headers2: [
                { text: 'ID', value: 'locationId' ,color:'red'},
                { text: 'LOCATION', value: 'locationName1' },
                { text: 'NAME', value: 'locationName2' },
                { text: 'PHONE', value: 'phoneNo' },
                ],
                locations: [],
                          
            }
        },
        
        created() {
          Echo.channel("dashBordChannel")
          .listen("DashBordEvent", (e) => {
             this.getDashbordUser();
          })
          this.getDashbordUser();
           this.getLocation();
        },

        methods: {
           reload() {
            this.getDashbordUser()
           },

          update(userProfile) {
              axios.patch(`/api/dashboarduser/${userProfile.id}`, userProfile)
              .then(res =>  console.log(res.data))
              .catch(error => console.log(error.res))
              this.getDashbordUser();
              this.showUpdateUserModal = false
          },
          getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },

          updateUserModal(user) {
            this.postItem = user;
            this.aaa(this.postItem["status"]);
            this.id          = this.postItem["id"];
            this.displayId   = this.postItem["displayId"];
            this.displayName = this.postItem["displayName"];
            this.status      = this.postItem["status"];
            this.firstName   = this.postItem["firstName"];
            this.lastName    = this.postItem["lastName"];
            this.rankNo      = this.postItem["rankNo"];
            this.rankName    = this.postItem["rankName"];
            this.phoneNo     = this.postItem["phoneNo"];
            this.belongsId   = this.postItem["belongsId"];
            this.belongsName = this.postItem["belongsName"];
            this.mail        = this.postItem["mail"];
            this.locationId  = this.postItem["locationId"];
            this.location    = this.postItem["location"];
            this.locationPhon= this.postItem["locationPhon"];
            this.comentNum   = this.postItem["comentNum"];
            this.comment     = this.postItem["comment"];

            this.Belongs     = { label: this.postItem["belongsName"], value: this.postItem["belongsId"] };
            this.locationDetail = this.location + '(' + this.locationPhon + ')';
            this.selectedBelongs = { label: this.statusName , value: this.postItem["status"] };

            this.showUpdateUserModal= true;
          },
          closeUserModal() {
            this.showUpdateUserModal = false;
          },

          aaa(id) {
            if (id == 0) {
              this.statusName = '在籍';
            }
            if (id == 1) {
              this.statusName = '離席中';
            }
            if (id == 2) {
              this.statusName = '取り込み中';
            }
            if (id == 3) {
              this.c = '';
            }
            if (id == 4) {
              this.statusName = '連絡不可';
            }
            if (id == 5) {
              this.statusName = '退席中';
            }
          },

          getLocation() {
             axios.get('/api/location')
            .then(res => this.locations = res.data.data)
            .catch(error => console.log(error.res.data))
          },

          openLocationModal() {
            this.getLocation();
            this.showLocationModal= true;
          },

          updateSelectLocation(locationVal){
            this.location      =locationVal.locationName2;
            this.locPhoneNo    =locationVal.phoneNo;
            this.locationDetail = this.location + '(' + this.locationPhon + ')';
            this.showLocationModal= false;
          },

          updateUser () {

            if(this.selectedBelongs == 0) {
              this.location = '自席'
            }
            if(this.selectedBelongs == 4) {
              this.location = ''
            }
            
            if (typeof this.selectedBelongs == 'number') {
              var status = this.selectedBelongs;
              console.log(status);
            } else {
              var status =this.selectedBelongs.value;
              console.log(status);
            }

            var id              =this.id;
            var displayId       =this.displayId;
            var displayName     =this.displayName;
            var firstName       =this.firstName;
            var lastName        =this.lastName;
            var rankNo          =this.rankNo;
            var rankName        =this.rankName;
            var phoneNo         =this.phoneNo;
            var belongsId       =this.belongsId;
            var belongsName     =this.belongsName;
            var mail            =this.mail;
            var locationId      =this.locationId;
            var location        =this.location;
            var locationPhon    =this.locationPhon;
            var comentNum       =this.comentNum;
            var comment         =this.comment;
            const userProfile = {
              id           :id,
              displayId    :displayId,
              displayName  :displayName,
              status       :status,
              firstName    :firstName,
              lastName     :lastName,
              rankNo       :rankNo,
              rankName     :rankName,
              phoneNo      :phoneNo,
              belongsId    :belongsId,
              belongsName  :belongsName,
              mail         :mail,
              locationId   :locationId,
              location     :location,
              locationPhon :locationPhon,
              comentNum    :comentNum,
              comment      :comment,
            }
            this.update(userProfile)
          },
        }
    }
</script>

<style>

.zaiseki-badge, .riseki-badge, .torikomi-badge,.renraku-badge, .taiseki-badge {
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

.tt div[role=listitem]:nth-of-type(1).tt {
  background-color: #4CAF50 !important;
  color:#fff
}
.tt div[role=listitem]:nth-of-type(2) {
  background-color: #FF9800 !important;
  color:#fff
}
.tt div[role=listitem]:nth-of-type(3) {
  background-color: #2196F3 !important;
  color:#fff
}
.tt div[role=listitem]:nth-of-type(4) {
  background-color: #9C27B0 !important;
  color:#fff
}
.tt div[role=listitem]:nth-of-type(5) {
  background-color: #E91E63 !important;
  color:#fff
}

.v-list {
  padding: 0 !important;
}
</style>
