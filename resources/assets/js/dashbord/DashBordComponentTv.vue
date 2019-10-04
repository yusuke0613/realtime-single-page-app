<template>
  <v-container grid-list-md style="padding:0">
    <v-layout row wrap >
      <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" lg4 md6 xs12>
        <v-card v-bind:class="{ 
          'zaiseki-box' : dashboarduser. status  === 0, 
          'riseki-box'  : dashboarduser.status    === 1, 
          'torikomi-box': dashboarduser. status === 2,
          'renraku-box' : dashboarduser. status === 3,
          'taiseki-box' : dashboarduser. status === 4,
        }">
            <div style="display: flex; justify-content: space-between; padding:1px;font-size:20px; background-color:#fff;">
              <div style="text-align:center;font-size:18px;font-weight: bold; ">{{dashboarduser.displayName}}
              <span v-if="dashboarduser.status==0" class="zaiseki-badge">在席</span>
              <span v-if="dashboarduser.status==1" class="riseki-badge">離席中</span>
              <span v-if="dashboarduser.status==2" class="torikomi-badge">取り込み中</span>
              <span v-if="dashboarduser.status==3" class="renraku-badge">連絡不可</span>
              <span v-if="dashboarduser.status==4" class="taiseki-badge">退席中</span>
              </div>
              <P  style="font-size:14px;">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</P>
            </div>
          
          <p style="font-size:14px; padding:1px; margin:0; color:#fff"><v-icon style="font-size:14px; padding:1px; margin:0; color:#fff">transfer_within_a_station</v-icon> {{dashboarduser.location}}</p>
          <v-divider color="white"></v-divider>
          <p style="font-size:14px; padding:1px; margin:0; color:#fff"><v-icon style="font-size:14px; padding:1px; margin:0; color:#fff">chat</v-icon> {{dashboarduser.comment}}</p>
          

        </v-card>
      </v-flex>
    </v-layout> 
  </v-container>  
</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: {},
                comments:[],
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                isModal: false,                        // Modak表示フラグ
                items: [],  
                newComment:'',                           // すべてのアイテム
            }
        },

        created() {
          Echo.channel("dashBordChannel")
          .listen("DashBordEvent", (e) => {
             this.getDashbordUser();
          })
          this.getDashbordUser();
        },
      
        methods: {
          getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },
        } 
    }
</script>

<style>

.zero-box {
  font-size:16px  !important;
  padding: 4px; 
  color:"white" !important;
}

.first-box {
  font-size:16px  !important;
  padding: 4px; 
  color:#E91E63 !important;
}
.second-box {
  font-size:16px  !important;
  padding: 4px; 
  color:rgb(40, 53, 147) !important;
}

.third-box {
  font-size:16px  !important;
  padding: 4px; 
  color:#009688 !important;
}

.color-nomal {
  font-size:12px;
  color: #fff !important;
}

.color-orange {
  font-size:12px;
  color: orange !important;
}

.container fluid fill-height  {
  margin:  0 !important;
  padding: 0 !important;
}

.container.grid-list-md .layout .flex {
    padding: 2px !important;
}

.zaiseki-badge, .riseki-badge, .torikomi-badge, .renraku-badge, .taiseki-badge {
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  font-size: 12px;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
}

.zaiseki-badge {
  background-color: #4CAF50; 
}

.riseki-badge {
  background-color: #FF9800; 
}

.torikomi-badge {
  background-color: #2196F3; 
}

.renraku-badge {
  background-color: #9C27B0; 
}
.taiseki-badge {
  background-color: #E91E63; 
}

.riseki-box {
  background-color: #4CAF50; 
}

.zaiseki-box {
  padding:3px;
  background-color: #4CAF50 !important;
}

.riseki-box {
  padding:3Px;
  background-color: #FF9800 !important;
}

.torikomi-box {
  padding:3px;
  background-color: #2196F3 !important;
}

.renraku-box {
  padding:3Px;
  background-color: #9C27B0 !important;
}

.taiseki-box {
  padding:3px;
  background-color: #E91E63 !important;
}

</style>
