import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import DashboardView from '@/views/DashboardView.vue';

// Function to check if user is authenticated
function isAuthenticated() {
  return !!localStorage.getItem('token'); // Check if token exists
}

const routes = [
  {
    path: '/dashboard',
    component: DashboardView,
    meta: { requiresAuth: true }, // Protect this route
  },
  {
    path: '/',
    component: LoginView,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/');
  } else {
    next();
  }
});

// const router = createRouter({
//   history: createWebHistory(import.meta.env.BASE_URL),
//   routes: [
//     {
//       path: '/',
//       name: 'login',
//       component: LoginView,
//     },
//     {
//       path: '/about',
//       name: 'about',
//       // route level code-splitting
//       // this generates a separate chunk (About.[hash].js) for this route
//       // which is lazy-loaded when the route is visited.
//       component: () => import('../views/AboutView.vue'),
//     },
//     {
//       path: '/dashboard',
//       name: 'dashboard',
//       component: () => import("../views/DashboardView.vue"),
//     },
//   ],
// })

export default router
