import Vue from 'vue';
import VueRouter from 'vue-router';
import LandingPage from '../views/LandingPage.vue';
import Dashboard from '../views/Dashboard.vue';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    name: 'landingPage',
    component: LandingPage,
    beforeEnter: (to, from, next) => {
      if (localStorage.token) {
        next('/dashboard');
      } else {
        next();
      }
    },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    beforeEnter: (to, from, next) => {
      if (localStorage.token) {
        next();
      } else {
        next('/');
      }
    },
  },
];

const router = new VueRouter({
  routes,
});

export default router;
