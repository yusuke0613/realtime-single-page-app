<template>
 <v-container>
        <v-form @submit.prevent="submit">
            <v-text-field
            v-model="form.name"
            label="Category Name"
            required
            ></v-text-field>
            <v-btn 
                color="teal"
                type="submit"
            >Create</v-btn>

        </v-form>

        <v-card>
          <v-toolbar
            color="indigo"
            dark
          >
            <v-toolbar-title>Categories</v-toolbar-title>

            <v-spacer></v-spacer>
          </v-toolbar>
          <v-list>

          <div  v-for="category in categories" :key="category.id">
          <v-list-tile>
            <v-list-tile-action>
              <v-btn Flat icom >
                <v-icon Flat color=orange>edit</v-icon>
              </v-btn>
            </v-list-tile-action>
              <v-list-tile-content>
                  <v-list-tile-title>{{category.name}}</v-list-tile-title>
              </v-list-tile-content>

              <v-list-tile-action>
                <v-btn Flat icom @click="destory(category.slug)">
                  <v-icon Flat color=red>delete</v-icon>
                </v-btn>
              </v-list-tile-action>
          </v-list-tile>
          <v-divider></v-divider>
          </div>
          </v-list>
        </v-card>

    </v-container>
</template>
<script>
    export default {
        data() {
            return {
                form:{
                  name:null
                },
                categories:{}
            }
        },
        created() {
             axios.get('/api/category')
            .then(res => this.categories = res.data.data)

             window.Echo.channel("likeChannel")
              .listen("LikeEvent", (e) => {
                console.log(e);
              })
        },
        mounted() {
          this.getCategory();
          window.Echo.channel("likeChannel")
          .listen("LikeEvent", (e) => {
             this.getCategory();
          })
        },
        methods: {
          submit() {
            axios.post('/api/category', this.form)
            .then(res => {
              console.log(res.data)
              this.categories.unshift(res.data)
              this.form.name=null
              }  
            )
          },
          destory(slug, index) {
             axios.delete(`/api/category/${slug}`)
            .then(res => this.categories.splice(index, 1))
          },
          getCategory() {
             axios.get('/api/category')
            .then(res => this.categories = res.data.data)
          }
        }
    }
</script>

