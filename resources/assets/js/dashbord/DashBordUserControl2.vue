 <template>
      <v-flex>
        <v-card xs12 class="m-3 px-3">
          <v-data-table
            :headers="headers"
            :items="dashboardusers"
            :pagination.sync="pagination"
            :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
            :loading="loading"
            :search="search"
            class="elevation-0 p-1"
          >
            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

            <template slot="items" slot-scope="props">
              <tr>
                <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>

                <template v-for="n in (headers.length - 2)">
                  <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;" v-text="props.item[headers[n].value]"></td>
                </template>
                <td class="text-xs-center" xs1>
                   <v-btn flat small fab @click="updateUserModal(props.item)"><v-icon color="success">edit</v-icon></v-btn>
                   <v-btn flat small fab @click="dialogOpen(props.item,true)"><v-icon color="error">delete</v-icon></v-btn>
                </td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
        

        <v-dialog v-model="showUpdateUserModal" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-card>
              <v-toolbar style="background-color:#1976D2">
                <v-btn icon dark @click="showUpdateUserModal = false">
                  <v-icon>clear</v-icon>
                </v-btn>
                <v-toolbar-title style="color:#fff">User Update</v-toolbar-title>
                <div class="flex-grow-1"></div>
              </v-toolbar>
               <v-form v-model="valid">
                <v-container>
                  <v-layout row wrap >
                    <v-flex lg6 md6 xs12>
                      <v-text-field
                        v-model="displayId"
                        :rules="nameRules"
                        :counter="3"
                        label="Display id"
                        required
                      ></v-text-field>
                    </v-flex>
                    <v-flex lg6 md6 xs12>
                      <v-text-field
                        v-model="displayName"
                        :rules="nameRules"
                        :counter="5"
                        label="Display name"
                        required
                      ></v-text-field>
                    </v-flex>
                    <v-flex lg6 md6 xs12>
                      <v-text-field
                        v-model="firstName"
                        :rules="nameRules"
                        :counter="10"
                        label="First name"
                        required
                      ></v-text-field>
                    </v-flex>

                    <v-flex lg6 md6 xs12>
                      <v-text-field
                        v-model="lastName"
                        :rules="nameRules"
                        :counter="10"
                        label="Last name"
                        required
                      ></v-text-field>
                    </v-flex>

                    <v-flex lg6 md6 xs12>
                      <v-select
                        v-model="Belongs"
                        item-text="label"
                        item-value="value"
                        label="Belongs"
                       :items="items"
                    
                      ></v-select>
                    </v-flex>

                    <v-flex lg6 md6 xs12>
                      <v-select
                        v-model="Rank"
                        item-text="label"
                        item-value="value"
                        :items="items2"
                        label="Rank"
                        return-object
                      ></v-select>
                    </v-flex>

                    <v-flex lg6 md6 xs12>
                      <v-text-field
                        v-model="mail"
                        :rules="emailRules"
                        label="E-mail"
                        required
                      ></v-text-field>
                    </v-flex>
                    <v-flex lg6 md6 xs12>
                      <v-text-field
                        v-model="phoneNo"
                        :rules="emailRules"
                        label="phoneNo"
                        required
                      ></v-text-field>
                    </v-flex>

                  </v-layout>
                    <v-btn 
                        style="fcolor:#fff"
                        color="blue"
                        type="button"
                        @click="test"
                    >Update</v-btn>
                </v-container>
              </v-form>
            </v-card>
        </v-dialog>
      </v-flex>
      
    </template>
<script>

    export default {

        data() {
            return {
                dashboardusers: [],
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                headers: [
                {align: 'center',  value: 'displayName', text: '在籍',},
                {text: '名前', align: 'center', value: 'displayName'},
                {text: '所属', align: 'center', value: 'belongsName'},
                {text: '役職',align: 'center',value: 'rankName'},
                {text: '内線',align: 'center', value: 'phoneNo'},
                ],
                loading: false,
                search: '',
                showModal2: false,
                pagination: { sortBy: 'name', descending: false, },
                setsearch(id) {
                  this.search = id
                },
                postItem: { id: 1, displayId:'1', name: 'aのitem' },
                showUpdateUserModal: false,
                valid: false,
                displayId: '',
                displayName:'',
                firstName: '',
                lastName: '',
                phoneNo:'',
                Rank:'',
                Belongs: { label: 'IT企画部', value: '0' },
                nameRules: [
                  v => !!v || 'Name is required',
                  v => v.length <= 10 || 'Name must be less than 10 characters',
                ],
                mail: '',
                emailRules: [
                  v => !!v || 'E-mail is required',
                  v => /.+@.+/.test(v) || 'E-mail must be valid',
                ],
                selectedBelongs: { label: 'ドイツ'   , value: 'germany'  },
                items: [
                  {label:'IT企画部'   , value:'0'},
                  {label:'システム1課', value:'1'},
                  {label:'システム2課', value:'2'},
                  {label:'システム3課', value:'3'},
                ],
                items2: [
                  {label:'G7', value:'7'},
                  {label:'G6', value:'6'},
                  {label:'G5', value:'5'},
                  {label:'G4', value:'4'},
                  {label:'G3', value:'3'},
                  {label:'G2', value:'2'},
                  {label:'G1', value:'1'},
                ]
                
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
           reload() {
            this.getDashbordUser()
           },


           updateUser (dashboarduser) {
            var status = dashboarduser.status;
    
            if(dashboarduser.status == 2) {
              status = 0;
            } else {
              status++;
            }
            const userProfile = {
              locationId:dashboarduser.locationId,
              location:dashboarduser.location,
              id:dashboarduser.id,
              status:status
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

          updateUserModal(user) {
            this.postItem = user;
            this.displayId   = this.postItem["displayId"];
            this.displayName = this.postItem["displayName"];
            this.firstName   = this.postItem["firstName"];
            this.lastName    = this.postItem["lastName"];
            this.rankNo      = this.postItem["rankNo"];
            this.phoneNo     = this.postItem["phoneNo"];
            this.belongsName = this.postItem["belongsName"];
            this.mail        = this.postItem["mail"];
            this.Belongs     = { label: this.postItem["belongsName"], value: this.postItem["belongsId"] };

            console.log(this.Belongs);

            this.showUpdateUserModal= true;
          },
          closeUserModal() {
            this.showUpdateUserModal = false;
          },

          test() {
            var test = {
              'displayId':this.displayId,
              'displayName':this.displayName,
              'status':0,
              'firstName':this.firstName,
              'lastName':this.lastName,
              'rankNo':0,
              'phoneNo':this.phoneNo,
              'rankName':this.Rank,
              'belongsId':0,
              'belongsName':this.Belongs,
              'mail':this.mail,
              'locationId':0,
              'location':'自席',
              'comentNum':0,
              'seisouFlag':0,
              'soujiFlag':0,
              'garbageFlag':0
            };
            axios.post('/api/dashboarduser', test)
            .then(res => {
              console.log(res.data)
              //this.form.name=null
              }  
            )
          },
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
