import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from "@/stores/useAuthStore.js";

function checkIfNotLogged(to, from, next) {
  const storeAuth = useAuthStore();
  if (!storeAuth.user) {
    return next("/");
  }

  if (to.meta.requiresAdmin && storeAuth.roleId !== 1) {
    return next("/");
  }

  next();
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { title: (route) => 'Főoldal' }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/components/Auth/Login.vue'),
      meta: { title: (route) => 'Login' }
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/components/Auth/Register.vue'),
      meta: { title: (route) => 'Register' }
    },
    {
      path: '/profile/:id',
      name: 'profile',
      component: () => import('@/components/Auth/Profile.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Profile' }
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Admin', requiresAdmin: true }
    },
    {
      path: '/tables',
      name: 'tables',
      component: () => import('../views/TableView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Admin', requiresAdmin: true }
    },
    {
      path: '/teams',
      name: 'teams',
      component: () => import('../views/TeamView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Teams', requiresAdmin: true }
    },
    {
      path: '/registerteam',
      name: 'registerteam',
      component: () => import('../views/TeamRegView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Teams' }
    },
    {
      path: '/stationview',
      name: 'stationview',
      component: () => import('../views/StationsView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Stations' }
    },
    {
      path: '/roles',
      name: 'roles',
      component: () => import('../views/RoleView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Roles', requiresAdmin: true }
    },
    {
      path: '/stations',
      name: 'stations',
      component: () => import('../views/StationView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Roles', requiresAdmin: true }
    },
    {
      path: '/competitions',
      name: 'competitions',
      component: () => import('../views/CompetitionView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Roles', requiresAdmin: true }
    },
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: () => import('../views/404.vue'),
      // component: HomeView,
      meta: { title: (route) => '404 - Page Not Found' }
    }
  ],
})

router.beforeEach((to, from, next) => {
  const title = to.meta.title;
  document.title = 'Sportverseny - ' + to.meta.title(to);
  next();
})

export default router
