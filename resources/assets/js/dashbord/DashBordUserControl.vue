 <template>
      <v-flex>
        <v-card xs12 class="m-3 px-3">

          <v-card-title class="title">
            <v-icon class="pr-2">account_box</v-icon> 社員管理
            <v-btn flat small fab @click="dialogOpen(props.item)"><v-icon color="orange ">add_circle_outline</v-icon></v-btn>
            
  　        <user-dialog ref="userDialog" @reload="reload" @setsearch="setsearch"></user-dialog>
            <v-spacer></v-spacer>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              prepend-icon="search"
              label="Search"
              single-line
              hide-details
              clearable
            ></v-text-field>
          </v-card-title>

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
                   <v-btn flat small fab @click="dialogOpen(props.item)"><v-icon color="success">edit</v-icon></v-btn>
                   <v-btn flat small fab @click="dialogOpen(props.item,true)"><v-icon color="error">delete</v-icon></v-btn>
                </td>
              </tr>
            </template>

          </v-data-table>
        </v-card>
      </v-flex>
    </template>
<script>
    import UserModal     from "./UserModal.vue";
    import LocationModal from "./LocationModal.vue";
    //import {db} from '../../../node_modules/firebase';
    export default {
        components: {
          UserModal,
          LocationModal
        },
        data() {
            return {
                dashboardusers: [],
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                 headers: [
                {text: 'ID', align: 'center', value: 'id'},
                {text: '名前', align: 'center', value: 'displayName'},
                {text: '所属', align: 'center', value: 'belongsName'},
                {text: '役職',align: 'center',value: 'rankName'},
                {text: 'メールアドレス',align: 'center',value: 'mail'},
                {text: '内線',align: 'center', value: 'phoneNo'},
                { align: 'center', sortable: false, text: 'アクション',},
                ],
                loading: false,
                search: '',
                pagination: { sortBy: 'name', descending: false, },
                setsearch(id) {
                  this.search = id
                },
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

          openUserModal(dashboarduser) {
            console.log(dashboarduser);
            this.dashboarduser = dashboarduser;
            this.showUserModal= true;
          },
          closeUserModal() {
            this.showUserModal = false;
          },
          openLocationModal(userid) {
            this.uid = userid;
            this.showLocationModal= true;
          },
          closeLocationModal() {
            this.showLocationModal = false;
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
