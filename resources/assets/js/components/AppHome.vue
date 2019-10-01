<template>
    <div>
    <toolbar></toolbar>
     <v-content>
      <v-container fluid fill-height>
        <v-fade-transition mode="out-in">
          <router-view></router-view>
        </v-fade-transition>
      </v-container>
    </v-content>
    <app-footer></app-footer>
   
    </div>
</template>

<script>
    import toolbar from './Toolbar'
    import AppFooter from './Appfooter'
    import Login from '../login/Login'
    export default {
        components:{toolbar,AppFooter,Login}
    }
</script>

<style>

</style>
