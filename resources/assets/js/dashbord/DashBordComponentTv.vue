<template>
  <div style="margin:auto; width:100vw;">
    <v-container grid-list-md style="padding:0px;margin:auto;" stagger="100">
        
        <v-layout row wrap stagger="70">
          <pu-stagger stagger="100">
          <transition-group stagger="100" appear class="grid-wrapper stagger" name="fade" xl4 lg4 md6 xs12>
          <v-flex v-for="dashboarduser in dashboardusers" :key="dashboarduser.id" stagger="70">
              
              <v-card v-bind:class="{ 
                'zaiseki-box-d' : dashboarduser.status === 0, 
                'riseki-box-d'  : dashboarduser.status === 1, 
                'taiseki-box-d' : dashboarduser.status === 4,
                }"
              >
                  
                  <div style="display: flex; justify-content: space-between; padding:5px;font-size:32px; background-color:#fff;">
                    <div style="text-align:center;font-size:38px;font-weight: bold; ">{{dashboarduser.displayName}}
                    <span v-if="dashboarduser.gomiFlag==1"     class="zaiseki-badge-t">ゴミ</span>
                    <span v-if="dashboarduser.souziFlag==1"    class="riseki-badge-t">掃除</span>
                    <span v-if="dashboarduser.seisouFlag==1"   class="torikomi-badge-t">棚拭き</span>
                    <span v-if="dashboarduser.serverFlag==1"   class="renraku-badge-t">サーバ</span>
                    <span v-if="dashboarduser.hinomotoFlag==1" class="taiseki-badge-t">火元</span>
                    </div>
                    <span v-if="dashboarduser.belongsId === 0" class="zero-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                    <span v-if="dashboarduser.belongsId === 1" class="first-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                    <span v-if="dashboarduser.belongsId === 2" class="second-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                    <span v-if="dashboarduser.belongsId === 3" class="third-badge-t">{{dashboarduser.belongsName}}/{{dashboarduser.rankName}}/({{dashboarduser.phoneNo}})</span>
                  </div>
              
                <p style="font-size:24px !important; padding:1px; margin:0; color:#fff"> {{dashboarduser.location}}</p>
                <v-divider color="white"></v-divider>
                <p style="font-size:20px !important; padding:1px; margin:0; color:#fff; text-overflow:  overflow: hidden; height:30px !important"> {{dashboarduser.comment}}</p>
              </v-card>

            
          </v-flex>
          </transition-group>
          </pu-stagger>
        </v-layout>

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
          beforeEnter: function (el) {
            el.style.opacity = 0
            el.style.height = 0
          },
          enter: function (el, done) {
            var delay = el.dataset.index * 150
            setTimeout(function () {
              Velocity(
                el,
                { opacity: 1, height: '1.6em' },
                { complete: done }
              )
            }, delay)
          },
          leave: function (el, done) {
            var delay = el.dataset.index * 150
            setTimeout(function () {
              Velocity(
                el,
                { opacity: 0, height: 0 },
                { complete: done }
              )
            }, delay)
          }
        }
        
    }
</script>

<style>

.container {
  padding: 0 !important;
  padding-top: 15px !important;
}

.container fluid fill-height  {
  margin:  0 !important;
  padding: 0 !important;
}

.container.grid-list-md .layout .flex {
    padding: 1px !important;
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

.grid-wrapper {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 1px;
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
  font-size: 16px !important;
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

.zaiseki-badge-t, .riseki-badge-t, .torikomi-badge-t, .renraku-badge-t, .taiseki-badge-t {
  margin-right: 1px !important;
  margin-left: 1px !important;
  padding: 4px !important;
  font-size: 18px !important;
  color: white;
  border-radius: 6px;
  box-shadow: 0 0 6px #ddd;
  white-space: nowrap;
}

.zaiseki-badge-t {
  background-color: #4CAF50; 
  cursor: pointer;
}

.riseki-badge-t {
  background-color: #FF9800; 
  cursor: pointer;
}

.torikomi-badge-t {
  background-color: #2196F3; 
  cursor: pointer;
}

.renraku-badge-t {
  background-color: #9C27B0; 
  cursor: pointer;
}
.taiseki-badge-t {
  background-color: #E91E63; 
  cursor: pointer;
}


.fade-enter-active, .fade-leave-active {
  transition: all 1.5s ease;
  transform-origin: center top 0px; 
}
.fade-enter, .fade-leave-to {
    transform: matrix3d(1,0,0.00,0,0.00,0,1.00,0.008,0,-1,0,0,0,0,0,1);
    opacity: 0;
}

 
</style>
