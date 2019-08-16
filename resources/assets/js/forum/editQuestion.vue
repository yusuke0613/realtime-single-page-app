<template>
    <v-container fluid>
        <v-card>
         <v-form @submit.prevent="update">
            <v-text-field
            v-model="form.title"
            label="Title"
            required
            type="text"
            ></v-text-field>
   
            <vue-simplemde v-model="form.body"/>

            <v-card-actions>
                <v-btn icon small type="submit">
                    <v-icon color="teal">save</v-icon>
                </v-btn>
                <v-btn icon small @click="cancel">
                    <v-icon color="black">cancel</v-icon>
                </v-btn>
            </v-card-actions>
        </v-form>
        </v-card>
    </v-container>
  
</template>

<script>
    import ShowQuestion from './ShowQuestion'
    import VueSimplemde from 'vue-simplemde'
    export default {
        props:['data'],
        methods: {
            create() {
                axios.post('/api/question', this.form)
                .then(res => console.log(res.data))
                .catch(error => console.log(error.response.data))
            },
            cancel() {
                EventBus.$emit('cancelEditing')
            },
            update() {
                axios.patch(`/api/question/${this.form.slug}`, this.form)
                .then(res => this.cancel())
                .catch(error => console.log(error.response.data))
            }
        },
        components:{ShowQuestion, VueSimplemde},
        data() {
            return {
                form: {
                    title:null,
                    body:null
                },
                categories : {}
            }
        },
        created() {
            axios.get('/api/category')
            .then(res => this.categories = res.data.data)
            .catch(error => console.log(error.response.data))
        },
        mounted() {
            this.form = this.data
        }
    }
</script>

<style>
</style>
