<template>
  <transition name="UpdateUserModal" >
    <v-layout row  class="overlay" @click="$emit('close')">
      <v-flex xs12 sm6 offset-sm3 style="background: rgba(0, 0, 0, 0); " @click.stop>
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
                :items="items"
                filled
                label="Belongs"
              ></v-select>
            </v-flex>

            <v-flex lg6 md6 xs12>
              <v-select
                v-model="Rank"
                :items="items2"
                filled
                label="Rank"
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
    }),
    methods: {
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
