<template>
    <v-container>
         <v-form @submit.prevent="create">
            <v-text-field
            v-model="form.title"
            label="Title"
            required
            type="text"
            ></v-text-field>

            <v-select
            :items="categories"
    
            item-text="name"
            item-value="id"
            v-model="form.category_id"
            label="Category"
            ></v-select>

          
            <vue-simplemde v-model="form.body"/>
            <v-btn
                color="green"
                type="submit"
            >Login
            </v-btn>

        </v-form>
    </v-container>
  
</template>

<script>
    import ShowQuestion from './ShowQuestion'
    import VueSimplemde from 'vue-simplemde'
    export default {
        methods: {
            create() {
                axios.post('/api/question', this.form)
                .then(res => console.log(res.data))
                .catch(error => console.log(error.response.data))
            }
        },
        components:{ShowQuestion, VueSimplemde},
        data() {
            return {
                form: {
                    title:null,
                    category_id:null,
                    body:null
                },
                categories : {}
            }
        },
        created() {
            axios.get('/api/category')
            .then(res => this.categories = res.data.data)
            .catch(error => console.log(error.response.data))
        }
    }
</script>

<style>
</style>
