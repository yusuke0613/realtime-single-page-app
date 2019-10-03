import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../login/Login'
import SignUp from '../login/signup'
import Logout from '../login/Logout'
import dashBordUser from '../dashbord/DashBordComponent'
import DashBordUserControl from '../dashbord/DashBordUserControl'
import DashBordComponent from '../dashbord/UserModal'
import DashBordUserControl2 from '../dashbord/DashBordUserControl2'
import DashBordComponentTv from  '../dashbord/DashBordComponentTv'
import DashBordComponentIn from '../dashbord/DashBordComponentIn'

Vue.use(VueRouter)

const routes = [
    {path: '/login', component: Login},
    {path: '/signup', component: SignUp},
    {path: '/', component: DashBordUserControl2},
    {path: '/logout', component: Logout},
    {path: '/dashboeradmin', component: DashBordUserControl},
    {path: '/test', component: DashBordComponent},
    {path: '/a', component: DashBordUserControl2},
    {path: '/dashbord', component: DashBordComponentTv},
    {path: '/1', component: DashBordComponentIn},
]

const router = new VueRouter({
    routes,
    hashbang : false,
    mode: 'history'
  
})

export default router