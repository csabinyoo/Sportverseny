import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from "@/stores/useAuthStore.js";

function checkIfNotLogged(to, from, next) {
  const storeAuth = useAuthStore();
  if (!storeAuth.user) {
    return next("/bejelentkezes");
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
      path: '/teams',
      name: 'teams',
      component: () => import('../views/TeamView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Teams', requiresAdmin: true }
    },
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: HomeView,
      meta: { title: (route) => 'FőOldal' }
    }
  ],
})

router.beforeEach((to, from, next) => {
  const title = to.meta.title;
  document.title = 'Sportverseny - ' + to.meta.title(to);
  next();
})

export default router
