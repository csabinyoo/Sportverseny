import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from "@/stores/useAuthStore.js";

function checkIfNotLogged() {
  const storeAuth = useAuthStore();
  if (!storeAuth.user) {
    return "/login";
  }
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
      meta: { title: (route) => 'Admin' }
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
