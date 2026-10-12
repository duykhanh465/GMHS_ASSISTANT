import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/login', name: 'login', component: () => import('@/views/LoginView.vue') },
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/OverviewView.vue'),
    },
    { path: '/patients', name: 'patients', component: () => import('@/views/PatientsView.vue') },
    { path: '/ventilator', name: 'ventilator', component: () => import('@/views/VentilatorView.vue') },
    { path: '/anesthesia', name: 'anesthesia', component: () => import('@/views/AnesthesiaView.vue') },
    { path: '/vasopressor', name: 'vasopressor', component: () => import('@/views/VasopressorView.vue') },
    { path: '/pain', name: 'pain', component: () => import('@/views/PainView.vue') },
    { path: '/spinal', name: 'spinal', component: () => import('@/views/SpinalView.vue') },
    { path: '/crisis', name: 'crisis', component: () => import('@/views/CrisisView.vue') },
    { path: '/formulas', name: 'formulas', component: () => import('@/views/FormulasView.vue') },
    { path: '/references', name: 'references', component: () => import('@/views/ReferencesView.vue') },
    { path: '/settings', name: 'settings', component: () => import('@/views/SettingsView.vue') },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
  ],
})

router.beforeEach((to) => {
  const token = localStorage.getItem('gmhs_token')
  if (to.path !== '/login' && !token) return '/login'
  if (to.path === '/login' && token) return '/'
})

export default router