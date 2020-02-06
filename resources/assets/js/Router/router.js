import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../login/Login'
import SignUp from '../login/signup'
import Logout from '../login/Logout'
import DashBordComponentTv from  '../dashbord/DashBordComponentTv'
import DashBordComponentIn from '../dashbord/DashBordComponentIn'
import TantouUser from '../dashbord/TantouUser'
import DashBordTagForm from '../dashbord/DashBordTagForm'
import PhotoList from '../pages/PhotoList.vue'
import PhotoDetail from '../pages/PhotoDetail.vue'
import SystemError from '../pages/errors/System.vue'
import NotFound from '../pages/errors/NotFound.vue'
import store from '../store'

Vue.use(VueRouter)

const routes = [
    {path: '/login', component: Login},
    {path: '/signup', component: SignUp},
    {path: '/', component: DashBordComponentIn},
    {path: '/logout', component: Logout},
    {path: '/dashboard', component: DashBordComponentTv},
    {path: '/TantouUser', component: TantouUser},
    {path: '/tag'       , component: DashBordTagForm}
]

const router = new VueRouter({
    routes,
    hashbang : false,
    mode: 'history'
  
})

export default router