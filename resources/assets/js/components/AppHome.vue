<template>
    <div>
    <toolbar v-if="test()"></toolbar>
     <v-content>
      <v-container fluid fill-height>
        <v-fade-transition mode="out-in">
          <router-view></router-view>
        </v-fade-transition>
      </v-container>
    </v-content>
    <app-footer v-if="test()"></app-footer>
   
    </div>
</template>

<script>
    import toolbar from './Toolbar'
    import AppFooter from './Appfooter'
    import Login from '../login/Login'
    export default {
        components:{toolbar,AppFooter,Login},
        methods:{
          test() {
            return !location.pathname.match(/^\/dashboard/);
          }
        }
    }

   
  
</script>

<style>

</style>
