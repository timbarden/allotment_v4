import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import LoggedIn from '../views/LoggedIn.vue'
//import { authGuard } from "../auth";

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/welcome/',
    name: 'LoggedIn',
    component: LoggedIn,
  }
]

const router = new VueRouter({
  routes
})

export default router
