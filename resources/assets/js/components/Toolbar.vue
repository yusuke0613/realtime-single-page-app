<template>

   <div>
    <v-navigation-drawer
      v-model="navBar"
      absolute
      bottom
      temporary
    >
        <v-list dense class="pt-0">
          <router-link to="/">
            <v-list-tile>
              <v-list-tile-action>
                <v-icon>dashboard</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>DASHBOARD</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </router-link>
          <router-link to="/dashboeradmin">
            <v-list-tile>
              <v-list-tile-action>
                <v-icon>settings</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>SETTING</v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </router-link>
        </v-list>
    </v-navigation-drawer>
    <v-toolbar
      dark
      color="blue-grey darken-4"
      clipped-left
      class="mx-auto overflow-hidden"
    >
      <v-toolbar-side-icon @click.stop="navBar = !navBar"></v-toolbar-side-icon>
      <v-toolbar-title class="white--text">Ito-san</v-toolbar-title>
      <v-spacer></v-spacer>
         <router-link to="/login">
            <v-list-tile color:white>
              <v-list-tile-content color:white>
                <v-list-tile-title style="color:white;">LOGIN</v-list-tile-title>
              </v-list-tile-content>
               <v-list-tile-action>
                <v-icon color:white>account_circle</v-icon>
              </v-list-tile-action>
            </v-list-tile>
          </router-link>   
       

        　
    </v-toolbar>

    </div>

</template>

<script>
    export default {
        data() {
            return {
                items: [
                    {title : 'Top', to : '/dashbord', show : true},
                    {title : 'Login', to : '/login', show : !User.loggedIn()},
                    {title : 'Logout', to : '/logout', show : User.loggedIn()},
                ],
                navBar:null,
                message: (new Date()).toLocaleString()
            }
        },
        created() {
            EventBus.$on('logout', () => {
                User.logout()
            })
            setInterval(this.clock(),1000);
        },
        methods: {
          clock() {
            this.message = (new Date()).toLocaleString()
          }
        },
    }
</script>

<style>
      a {
    text-decoration: none;
  }
  

</style>
