import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../login/Login'
import SignUp from '../login/signup'
import Forum from '../forum/Forum'
import Read from '../forum/Read'
import Create from '../forum/create'
import Logout from '../login/Logout'

Vue.use(VueRouter)

const routes = [
    {path: '/login', component: Login},
    {path: '/signup', component: SignUp},
    {path: '/forum', component: Forum, name:'forum'},
    {path: '/ask', component: Create},
    {path: '/logout', component: Logout},
    {path: '/question/:slug', component: Read, name:'read'}
    //{path: '/bar', component: Bar}
]

const router = new VueRouter({
    routes,
    hashbang : false,
    mode: 'history'
  
})

export default router