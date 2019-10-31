<template>
  <v-form style="margin:auto !important;">
        <v-radio-group v-model="row" row style="font-size=100px !important">
            <v-radio label="在籍" value="1" style="font-size=100px !important"></v-radio>
            <v-radio label="退席" value="2"></v-radio>
        </v-radio-group>

      　<v-text-field
          id="tagNo"
          label="Card ID"
          solo
          style="visibility:hidden !important"
          v-model="message"
          @change="someHandler"
        ></v-text-field>
        <v-snackbar
          style="font-size:38px; padding:5px"
          v-model="snackbar"
          :timeout="timeout"
          color=orange
        >
          {{ text }}
        </v-snackbar>
  </v-form>
  
</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: {},
                comments:[],
                comment:'',
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                showStatusModal:false,
                isModal: false,   
                items: [],  
                locations:[],
                message:"",
                row:'1',
                snackbar: false,
                text: 'My timeout is set to 2000.',
                timeout: 2000,
                flag:0,
            }
        },

        methods: {
          someHandler() {
            if (this.row == 1) {
              if (this.message != "") {
                axios.patch(`/api/zaiseki/${this.message}`)
                .then(res =>  (this.flag = res.data))
                .catch(error => console.log(error.res));
                this.message = "";
                if (this.flag == 0) {
                  this.text = '君の名は？'
                }
                if (this.flag == 1) {
                  this.text = 'こんにちわ'
                }
                this.snackbar = true;
              }
            } else {
                if (this.message != "") {
                  axios.patch(`/api/taiseki/${this.message}`)
                  .then(res =>  this.flag = res.data)
                  .catch(error => console.log(error.res));
                  this.message = "";
                if (this.flag == 0) {
                  this.text = '君の名は？'
                }
                if (this.flag == 1) {
                  this.text = 'さようなら'
                }
                this.snackbar = true;
                }
            }
          },
        } 
    }
</script>
<style>
 .v-input--selection-controls.v-input .v-label {
   font-size: 48px;
 }
</style>