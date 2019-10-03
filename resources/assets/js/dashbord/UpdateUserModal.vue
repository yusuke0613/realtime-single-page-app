<template>
  <transition name="UpdateUserModal" >
    <v-layout row  class="overlay" @click="$emit('close')">
      <v-flex xs12 sm6 offset-sm3 style="background: rgba(0, 0, 0, 0); " @click.stop>
      <v-form v-model="valid">
        <v-container>
          <v-layout row wrap >
            <v-col cols="12" sm="6" md="6">
              <v-radio-group v-model="ex7" column>
                <v-radio
                  label="red"
                  color="red"
                  value="red"
                ></v-radio>
                <v-radio
                  label="red darken-3"
                  color="red darken-3"
                  value="red darken-3"
                ></v-radio>
                <v-radio
                  label="indigo"
                  color="indigo"
                  value="indigo"
                ></v-radio>
                <v-radio
                  label="indigo darken-3"
                  value="indigo darken-3"
                  style="color:#E91E63 !important"
                ></v-radio>
              </v-radio-group>
            </v-col>

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

          </v-layout>
            <v-btn 
                color="teal"
                type="button"
                @click="test"
            >Create</v-btn>
        </v-container>
      </v-form>
      </v-flex>
    </v-layout>
  </transition>
</template>


<script>
  export default {
    name: 'UpdateUserModal',
    props: {
      // 受け取る属性名を指定
      val: Object // オブジェクトのみ受け取る
    },
    data: () => ({
      valid: false,
      displayId:'',
      displayName:'',
      firstName: '',
      lastName: '',
      phoneNo:'',
      Rank:'',
      Belongs:'',
      nameRules: [
        v => !!v || 'Name is required',
        v => v.length <= 10 || 'Name must be less than 10 characters',
      ],
      mail: '',
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+/.test(v) || 'E-mail must be valid',
      ],
      items: ['IT企画部', 'システム1課', 'システム2課', 'システム3課'],
      items2: ['G7', 'G6', 'G5', 'G4', 'G3', 'G2', 'G1'],
      search: '',
      headers: [
      { text: 'ID', value: 'locationId' },
      { text: 'LOCATION', value: 'locationName1' },
      { text: 'NAME', value: 'locationName2' },
      { text: 'PHONE', value: 'phoneNo' },
      ],
    }),
    methods: {
          test() {
            this.getLocation();
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

          getLocation() {
             axios.get('/api/location')
            .then(res => this.location = res.data.data)
            .catch(error => console.log(error.res.data))
          },
    }
  }
</script>


<style>

.overlay {
    background: rgb(255, 255, 255, 0.8);
    position: fixed;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 900;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
 
}
</style>
