import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../login/Login'
import SignUp from '../login/signup'
import Forum from '../forum/Forum'
import Read from '../forum/Read'
import Create from '../forum/create'
import Logout from '../login/Logout'
import dashBordUser from '../dashbord/DashBordComponent'
import DashBordUserControl from '../dashbord/DashBordUserControl'
import DashBordComponent from '../dashbord/UserModal'
import DashBordUserControl2 from '../dashbord/DashBordUserControl2'
import DashBordComponentTv from  '../dashbord/DashBordComponentTv'

Vue.use(VueRouter)

const routes = [
    {path: '/login', component: Login},
    {path: '/signup', component: SignUp},
    {path: '/forum', component: Forum, name:'forum'},
    {path: '/ask', component: Create},
    {path: '/', component: dashBordUser},
    {path: '/logout', component: Logout},
    {path: '/dashboeradmin', component: DashBordUserControl},
    {path: '/question/:slug', component: Read, name:'read'},
    {path: '/test', component: DashBordComponent},
    {path: '/a', component: DashBordUserControl2},
    {path: '/dashbord', component: DashBordComponentTv},
]

const router = new VueRouter({
    routes,
    hashbang : false,
    mode: 'history'
  
})

export default router