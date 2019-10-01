<template>
  <v-card>
    <v-toolbar
      color="cyan"
      dark
    >
      <v-toolbar-title>Categories</v-toolbar-title>

      <v-spacer></v-spacer>
    </v-toolbar>
    <v-list>
    <v-list-tile v-for="category in categories" :key="category.id">
        <v-list-tile-content>
            <v-list-tile-title>{{category.name}}</v-list-tile-title>
        </v-list-tile-content>
    </v-list-tile>
   
    </v-list>
  </v-card>
</template>
<script>
  
    export default {
        props:['data'],
        data() {
            return {
                categories:{}
            }
        },
        mounted() {
           this.getCategory();

          Echo.channel('likeChannel')
              .listen('.LikeEvent', (e) => {

                  this.getCategory(); // 全メッセージを再読込

              });

        },
        created() {
             axios.get('/api/category')
            .then(res => this.categories = res.data.data)
            .catch(error => consple.log(error.response.data))
        },
        methods: {
          getCategory() {
              axios.get('/api/category')
            .then(res => this.categories = res.data.data)
            .catch(error => consple.log(error.response.data))
          }
        }
    }
</script>

