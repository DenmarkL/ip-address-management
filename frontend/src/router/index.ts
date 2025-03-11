import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/AuthStore';
import LoginView from '@/views/LoginView.vue';
import DashboardView from '@/views/DashboardView.vue';
import IPManagement from '@/views/IPManagement.vue';
import AuditLogs from '@/views/AuditLogsView.vue';

// Function to check if user is authenticated
function isAuthenticated() {
  return !!localStorage.getItem('accessToken');
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
      { path: '', component: IPManagement }, 
      { path: 'ip-management', component: IPManagement },
      { path: 'audit-logs', component: AuditLogs, meta: { requiresAdmin: true } },
    ],
  },
  { path: '/', redirect: '/dashboard' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});


// Navigation Guard
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // Ensure user role is fetched before proceeding
  if (!authStore.isAdmin && isAuthenticated()) {
    await authStore.fetchUserRole();
  }

  if (to.meta.requiresAuth && !isAuthenticated()) {
    next('/login');
  } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router;
