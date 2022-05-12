import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '../views/Home.vue';
import Dashboard from '../views/Dashboard.vue';
import { authGuard } from '../auth/authGuard';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/dashboard/',
    name: 'Dashboard',
    component: Dashboard,
    beforeEnter: authGuard,
  }
]

const router = new VueRouter({
  routes
});

export default router;
