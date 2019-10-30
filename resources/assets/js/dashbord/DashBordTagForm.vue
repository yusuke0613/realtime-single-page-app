<template>
  <v-form>
    <v-container>
      <v-row>
        <v-radio-group v-model="row" row>
            <v-radio label="在籍" value="1"></v-radio>
            <v-radio label="退席" value="2"></v-radio>
        </v-radio-group>

      　<v-text-field
          id="tagNo"
          label="Card ID"
          solo
          v-model="message"
          @change="someHandler"
        ></v-text-field>
      </v-row>
    </v-container>
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
                row:'1'
            }
        },

        methods: {
          someHandler() {
            if (this.row == 1) {
              if (this.message != "") {
                //alert(document.getElementById("tagNo").value);
                axios.patch(`/api/zaiseki/${this.message}`)
                .then(res =>  console.log(res.data))
                .catch(error => console.log(error.res));

                this.message = "";
              }
            } else {
                if (this.message != "") {
                  //alert(document.getElementById("tagNo").value);
                  axios.patch(`/api/taiseki/${this.message}`)
                  .then(res =>  console.log(res.data))
                  .catch(error => console.log(error.res));

                  this.message = "";
                }
            }
          },
        } 
    }
</script>
