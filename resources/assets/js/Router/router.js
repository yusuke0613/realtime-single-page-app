import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../login/Login'
import SignUp from '../login/signup'
import Logout from '../login/Logout'
import DashBordComponentTv from  '../dashbord/DashBordComponentTv'
import DashBordComponentIn from '../dashbord/DashBordComponentIn'
import TantouUser from '../dashbord/TantouUser'
import DashBordTagForm from '../dashbord/DashBordTagForm'
import PhotoList from '../pages/PhotoList'
import UPhotoList from '../pages/UPhotoList'
import PhotoDetail from '../pages/PhotoDetail'
import SystemError from '../pages/errors/System'
import NotFound from '../pages/errors/NotFound'
import store from '../store'


Vue.use(VueRouter)

const routes = [
    {path: '/login'       , component: Login},
    {path: '/signup'      , component: SignUp},
    {path: '/'            , component: DashBordComponentIn},
    {path: '/logout'      , component: Logout},
    {path: '/dashboard'   , component: DashBordComponentTv},
    {path: '/TantouUser'  , component: TantouUser},
    {path: '/tag'         , component: DashBordTagForm},
    {path: '/photos'      
     , component: PhotoList
     ,  props: route => {
                            const page = route.query.page
                            return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
                        }
    },
    {path: '/photos/:id'  , component: PhotoDetail, props: true},
    {path: '/upphotos'    , component: UPhotoList
    },
]

const router = new VueRouter({
    routes,
    hashbang : false,
    mode: 'history'
  
})

export default router