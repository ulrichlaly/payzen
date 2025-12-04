import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../components/Login.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: () => import('../components/DashboardLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard-home',
        component: () => import('../components/DashboardRouter.vue')
      },
      {
        path: 'employees',
        name: 'employees',
        component: () => import('../components/Employees.vue'),
        meta: { roles: ['Admin', 'Administrateur', 'Admin Général'] }
      },
      // Routes Admin et Comptable
      {
        path: 'payroll',
        name: 'payroll',
        component: () => import('../components/Payroll.vue'),
        meta: { roles: ['Admin', 'Administrateur', 'Comptable'] }
      },
      {
        path: 'declarations',
        name: 'declarations',
        component: () => import('../components/Declarations.vue'),
        meta: { roles: ['Admin', 'Administrateur', 'Comptable'] }
      },
      // Routes pour tous
      {
        path: 'leaves',
        name: 'leaves',
        component: () => import('../components/Leaves.vue')
      },
      // Routes Collaborateur
      {
        path: 'my-bulletins',
        name: 'my-bulletins',
        component: () => import('../components/MyBulletins.vue'),
        meta: { roles: ['Collaborateur'] }
      },
      {
        path: 'my-leaves',
        name: 'my-leaves',
        component: () => import('../components/MyLeaves.vue'),
        meta: { roles: ['Collaborateur'] }
      },
      {
        path: 'profile',
        name: 'profile',
        component: () => import('../components/Profile.vue')
      },
      {
  path: 'expenses',
  name: 'expenses',
  component: () => import('../components/Expenses.vue'),
  meta: { roles: ['Admin', 'Administrateur', 'Comptable'] }
},
{
  path: 'my-expenses',
  name: 'my-expenses',
  component: () => import('../components/MyExpenses.vue'),
  meta: { roles: ['Collaborateur'] }
},
{
  path: 'loans',
  name: 'loans',
  component: () => import('../components/Loans.vue'),
  meta: { roles: ['Admin', 'Administrateur', 'Comptable'] }
},
{
  path: 'my-loans',
  name: 'my-loans',
  component: () => import('../components/MyLoans.vue'),
  meta: { roles: ['Collaborateur'] }
},
    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  console.log('Navigation vers:', to.path);
  console.log('isAuthenticated:', authStore.isAuthenticated);

  // Si la route nécessite l'authentification
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    console.log('Redirection vers /login (non authentifié)');
    next('/login');
    return;
  }

  // Si l'utilisateur est connecté et essaie d'accéder au login
  if (to.path === '/login' && authStore.isAuthenticated) {
    console.log('Redirection vers /dashboard (déjà connecté)');
    next('/dashboard');
    return;
  }

  // Vérification des rôles
  if (to.meta.roles) {
    // Charger l'utilisateur si pas encore chargé
    if (!authStore.user) {
      try {
        await authStore.fetchUser();
      } catch (error) {
        console.error('Erreur lors de la récupération de l\'utilisateur:', error);
        next('/login');
        return;
      }
    }

    const userRoles = authStore.user?.roles || [];
    const allowedRoles = to.meta.roles as string[];

    // Vérifier si l'utilisateur a au moins un des rôles autorisés
    const hasPermission = allowedRoles.some(role => userRoles.includes(role));

    if (!hasPermission) {
      console.warn(`Accès refusé. Rôles utilisateur: ${userRoles.join(', ')}, Rôles autorisés: ${allowedRoles.join(', ')}`);
      alert('Accès refusé. Vous n\'avez pas les permissions nécessaires.');
      next('/dashboard');
      return;
    }
  }

  next();
});

export default router;