import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '@/views/LoginView.vue';
import DashboardView from '@/views/DashboardView.vue';
import IPManagement from '@/views/IPManagement.vue';

// Function to check if user is authenticated
function isAuthenticated() {
  return !!localStorage.getItem('accessToken'); // Check if token exists
}

const routes = [
  {
    path: '/login',
    component: LoginView,
  },
  {
    path: '/dashboard',
    component: DashboardView,
    meta: { requiresAuth: true },
    children: [
      { path: '', component: IPManagement }, // Default dashboard home
      { path: 'ip-management', component: IPManagement }, // Load inside dashboard
    ],
  },
  { path: '/', redirect: '/dashboard' }, // Redirect to dashboard by default
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guard
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/login');
  } else {
    next();
  }
});

export default router;
