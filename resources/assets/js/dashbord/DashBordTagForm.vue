<template>
  <v-form>
    <v-container>
      <v-row>
        <v-radio-group v-model="row" row>
            <v-radio label="在籍" value="radio-1"></v-radio>
            <v-radio label="退席" value="radio-2"></v-radio>
        </v-radio-group>

      　<v-text-field
          id="tagNo"
          label="Card ID"
          solo
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
                row:'radio-1'
            }
        },

        methods: {
          getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
          },

          openCommentModal(dashboarduser) {
            this.dashboarduser = dashboarduser;
            this.comment = dashboarduser.comment;
            this.showCommentModal= true;
          },

          someHandler() {
            alert('test');
          },

        
          updateStatus(status) {
            var id              = this.dashboarduser.id;
            var displayId       = this.dashboarduser.displayId;
            var displayName     = this.dashboarduser.displayName;
            var status          = status;
            var firstName       = this.dashboarduser.firstName;
            var lastName        = this.dashboarduser.lastName;
            var rankNo          = this.dashboarduser.rankNo;
            var rankName        = this.dashboarduser.rankName;
            var phoneNo         = this.dashboarduser.phoneNo;
            var belongsId       = this.dashboarduser.belongsId;
            var belongsName     = this.dashboarduser.belongsName;
            var mail            = this.dashboarduser.mail;
            var locationId      = this.dashboarduser.locationId;
            var location        = this.dashboarduser.location;
            if (status == 0) {
              var locationId      = 999;
              var location        = '自席';
            } 
            if (status == 4) {
              var locationId      = 1000;
              var location        = '休み';
            }

            var locationPhon    = this.dashboarduser.locationPhon;
            var comentNum       = this.dashboarduser.comentNum;
            var comment         = this.dashboarduser.comment;

            const userProfile = {
              id:id,
              displayId:displayId,
              displayName:displayName,
              status:status,
              firstName:firstName,
              lastName:lastName,
              rankNo:rankNo,
              rankName:rankName,
              phoneNo:phoneNo,
              belongsId:belongsId,
              belongsName:belongsName,
              mail:mail,
              locationId:locationId,
              location:location,
              locationPhon:locationPhon,
              comentNum:comentNum,
              comment:comment,

            }
            console.log(userProfile);
            this.update(userProfile)
            this.showStatusModal = false;
          },

        } 
    }
</script>
