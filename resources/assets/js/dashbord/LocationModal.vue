<template>
    <transition name="LocationModal">
        <div class="overlay" @click="$emit('close')"> 
            <div class="panel" @click.stop>
                <template>
                    <v-card>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="search"
                                label="Search"
                                single-line
                                hide-details
                                
                            >
                            </v-text-field>
                        </v-card-title>
                        <v-data-table color:orange
                            :headers="headers"
                            :items="location"
                            :search="search"

                            :loading="true"
                            class="elevation-1"
                            :sort-by="['ID']"
                        >
                            <v-progress-linear v-slot:progress color="orange" indeterminate></v-progress-linear>
                            <template v-slot:items="props" color:orange>
                                <tr @click="updateSelectLocation(props.item)" color:orange>
                                <td class="text-xs-right" >{{ props.item.locationId }}</td>
                                <td class="text-xs-right" >{{ props.item.locationName1 }}</td>
                                <td class="text-xs-right" >{{ props.item.locationName2 }}</td>
                                <td class="text-xs-right" >{{ props.item.phoneNo }}</td>
                                </tr>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card>
                </template>      
            </div> 
        </div>
    </transition>
</template>

<script>
    export default {
        name: 'SelectList',
         props: {
      // 受け取る属性名を指定
      val: Object // オブジェクトのみ受け取る
    },
        data() {
            return {
                isModal: false,                        // Modak表示フラグ
                items: [],                             // すべてのアイテム
                search: '',
                
                headers: [
                { text: 'ID', value: 'locationId' },
                { text: 'LOCATION', value: 'locationName1' },
                { text: 'NAME', value: 'locationName2' },
                { text: 'PHONE', value: 'phoneNo' },
                ],
                desserts: [],
                locationItems:['テクニカルセンター', '亀山工場','ITEC','VnTEC'],
                location: [],
            }
        },
        computed: {
            // 選択されているオプションは非表示
            filteredItems() {
                let data = this.items;
                let selects = this.selectItems;
                data = data.filter(function (row) {
                    for (var i=0; i<selects.length; i++) {
                        if (selects[i].id == row['id']) {
                            return false;
                            break;
                        }
                    }
                    return true;
                });
                
                return data;
            }
        },
        created() {
            axios.get('/api/location')
            .then(res => this.location = res.data.data)
            /*
            window.Echo.channel("dashBordChannel")
              .listen("DashBordEvent", (e) => {
                console.log(e);
              })
            */
        },
             
        mounted() {
          //this.getLocation();
          /*
          window.Echo.channel("dashBordChannel")
          .listen("DashBordEvent", (e) => {
             this.getDashbordUser();
          })
          */
        },
        methods: {
         
          getLocation() {
             axios.get('/api/location')
            .then(res => this.location = res.data.data)
            .catch(error => console.log(error.res.data))
      
          },
           updateSelectLocation (u) {
                var locationId       = u.locationId;
                var location         = u.locationName2;
                var id               = this.val['id'];
                var status           = this.val['status'];

                const userProfile = {
                locationId:locationId,
                location:location,
                id:id,
                status:status
                }
                
                axios.patch(`/api/dashboarduser/${userProfile.id}`, userProfile)
                .then(res =>  console.log(res.data))
                .catch(error => console.log(error.res))
                this.$emit('callParent')
                this.$emit('close')
            },
        }
    }
</script>

<style>
    .overlay {
    background: rgba(0, 0, 0, .5);
    position: fixed;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    z-index: 900;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
 
 
}

</style>
