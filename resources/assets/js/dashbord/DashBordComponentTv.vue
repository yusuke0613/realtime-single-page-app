<template>
  <div style="margin:auto; width:100vw;">
    <v-container grid-list-md style="padding:1px;margin:auto;">
        <v-layout row wrap>
          <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" xl4 lg4 md6 xs12>
            <v-card v-bind:class="{ 
              'zaiseki-box-d' : dashboarduser.status === 0, 
              'riseki-box-d'  : dashboarduser.status === 1, 
              'taiseki-box-d' : dashboarduser.status === 4,
              }"
              @click="openSeatModal()"
            >
                <div style="display: flex; justify-content: space-between; padding:5px;font-size:38px; background-color:#fff;" >
                  <div style="text-align:center;font-size:42px;font-weight: bold; ">{{dashboarduser.displayName}}
                  <span v-if="dashboarduser.gomiFlag==1"     class="zaiseki-badge">ゴミ</span>
                  <span v-if="dashboarduser.souziFlag==1"    class="riseki-badge">掃除</span>
                  <span v-if="dashboarduser.seisouFlag==1"   class="torikomi-badge">棚拭き</span>
                  <span v-if="dashboarduser.serverFlag==1"   class="renraku-badge">サーバ</span>
                  <span v-if="dashboarduser.hinomotoFlag==1" class="taiseki-badge">火元</span>
                  </div>
                  <span v-if="dashboarduser.belongsId === 0" class="zero-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 1" class="first-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 2" class="second-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  <span v-if="dashboarduser.belongsId === 3" class="third-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                </div>
             
              <p style="font-size:22px !important; padding:1px; margin:0; color:#fff"> {{dashboarduser.location}}</p>
              <v-divider color="white"></v-divider>
              <p style="font-size:18px !important; padding:1px; margin:0; color:#fff; text-overflow:  overflow: hidden; height:24px !important"> {{dashboarduser.comment}}</p>
            </v-card>
          </v-flex>
        </v-layout>

         <v-dialog 
          v-model="seatMoal" 
          fullscreen
         >
            <v-img
              src='/seat.png'
              height=100vh
              style ="background-color:rgba(255,255,255,0.9) !important"
            ></v-img>
  　　    </v-dialog>
    </v-container>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                dashboardusers: {},
                seatMoal:false,
                comments:[],
                comment:'',
                dashboarduser: [{ id: 1, name: 'aのitem' },{ id: 2, name: 'bのitem' }],
                showLocationModal:false,
                showUserModal: false,
                showCommentModal: false,
                widgets: false,
                showStatusModal:false,
                isModal: false,   
                showCommentModal:false,                     // Modak表示フラグ
                items: [],  
                locations:[],
                newComment:'', 
                search:'',
                headers: [
                  { text: 'ID', value: 'locationId' },
                  { text: 'LOCATION', value: 'locationName1' },
                  { text: 'NAME', value: 'locationName2' },
                  { text: 'PHONE', value: 'phoneNo' },
                ],
            }
        },
        mounted () {
          // releasedAtFromNowを1分ごとに更新する
          /*
          window.setInterval(() => {
            this.openSeatModal()
          }, 1000 * 10)
          */
        },
        created() {
          Echo.channel("dashBordChannel")
          .listen("DashBordEvent", (e) => {
             this.getDashbordUser();
          })
          this.getDashbordUser();
        },
        methods: {
          openSeatModal() {
            if (this.seatMoal == true) {
              this.seatMoal = false;
            } else {
              this.seatMoal = true;
            }
          },
           getDashbordUser() {
             axios.get('/api/dashboarduser')
            .then(res => this.dashboardusers = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },
        } 
    }
</script>

<style>

.container {
  padding: 0 !important;
  padding-top: 20px !important;
}

.container fluid fill-height  {
  margin:  0 !important;
  padding: 0 !important;
}

.container.grid-list-md .layout .flex {
    padding: 2px !important;
}

.zaiseki-badge-tv, .riseki-badge-tv, .torikomi-badge-tv, .renraku-badge-tv, .taiseki-badge-tv {
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  font-size: 12px !important;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
  font-weight: normal !important;
}

.zaiseki-badge-tv {
  background-color: #4CAF50; 
  cursor: pointer;
}

.riseki-badge-tv {
  background-color: #FF9800; 
  cursor: pointer;
}

.taiseki-badge-tv {
  background-color: #E91E63; 
  cursor: pointer;
}

.zero-badge, .first-badge, .second-badge, .third-badge {
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  font-size: 12px !important;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
  font-weight: normal !important;
}

.zero-badge {
  background-color: #34495e !important; 
}

.first-badge {
  background-color: #c0392b !important; 
}

.second-badge {
  background-color: #3F51B5 !important; 
}

.third-badge {
  background-color: #009688 !important; 
}

.zaiseki-box-d {
  padding:3px;
  background-color:#4CAF50 !important;
}

.riseki-box-d {
  padding:3Px;
  background-color:#FF9800 !important;
}

.taiseki-box-d {
  padding:3px;
  background-color:#E91E63 !important;
}


.zaiseki-list {
  padding:3px;
  font-size: 30px;
  color: #fff;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #4CAF50 !important;
}

.riseki-list {
  padding:3px;
  color: #fff;
  font-size: 30px;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #FF9800 !important;
}

.taiseki-list {
  padding:3px;
  font-size: 30px;
  color: #fff;
  text-align: center;
  font-weight: bold; 
  cursor: pointer;
  background-color: #E91E63 !important;
}


.zaiseki-list:hover {
opacity: 0.5 ;
}

.riseki-list:hover {
opacity: 0.5 ;
}

.torikomi-list:hover {
opacity: 0.5 ;
}

.renraku-list:hover {
opacity: 0.5 ;
}

.taiseki-list:hover {
opacity: 0.5 ;
}



.v-enter {
  transform: translate(-100px, 0);
  opacity: 0;
}
.v-enter-to {
  opacity: 1;
}
.v-enter-active {
  transition: all 1s 0s ease;
}
.v-leave {
  transform: translate(0, 0);
  opacity: 1;
}
.v-leave-to {
  transform: translate(100px, 0);
  opacity: 0;
}
.v-leave-active {
  transition: all .5s 0s ease;
}


.zero-badge-t, .first-badge-t, .second-badge-t, .third-badge-t {
  /*
  padding: 3px 6px;
  margin-right: 8px;
  margin-left: 1px;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 3px #ddd;
  white-space: nowrap;
  */
  font-size: 20px !important;
  font-weight: bold !important;
}

.zero-badge-t {
  color: #34495e !important; 
}

.first-badge-t {
  color: #c0392b !important; 
}

.second-badge-t {
  color: #3F51B5 !important; 
}

.third-badge-t {
  color: #009688 !important; 
}

</style>
