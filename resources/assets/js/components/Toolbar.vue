<template>

    <v-toolbar dense>

        <v-toolbar-title>Title</v-toolbar-title>

        <v-spacer></v-spacer>

        <div class="hidden-sm-and-down">
         
                <v-btn flat>Forum</v-btn>
         
            <v-btn flat>Ask Question</v-btn>
            <v-btn flat>LINK TWO</v-btn>
            <router-link to="/login">
                <v-btn flat>Login</v-btn>
            </router-link>
        </div>

    </v-toolbar>

</template>

<script>
    export default {
    }
</script>

<style>

</style>
