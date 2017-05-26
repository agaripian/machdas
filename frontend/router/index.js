import Vue from 'vue';
import Router from 'vue-router';
import Hello from '@/components/Hello';
import Signup from '@/components/Signup';
import Login from '@/components/Login';
import User from '@/components/User';
import Beimage from '@/components/Beimage';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Hello',
      component: Hello,
    },
    {
      path: '/signup',
      name: 'Signup',
      component: Signup,
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
    },
    {
      path: '/user/:userId',
      name: 'User',
      component: User,
      meta: { requiresAuth: true },
      props: true,
      children: [
        {
          // UserProfile will be rendered inside User's <router-view>
          // when /user/:id/profile is matched
          path: 'profile',
          component: Beimage,
        },
      ],
    },
  ],
});
