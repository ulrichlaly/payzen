<template>
  <div class="h-screen flex bg-gray-50 overflow-hidden">
    <Sidebar />
    
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header fixe -->
      <header class="bg-white border-b border-gray-200 px-6 py-4 flex-shrink-0">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h2>
            <p class="text-sm text-gray-500">{{ currentDate }}</p>
          </div>
          <div class="flex items-center gap-3">
            <div class="text-right">
              <p class="text-sm font-bold text-gray-900">{{ authStore.user?.fullname }}</p>
              <p class="text-xs text-gray-500">{{ authStore.user?.email }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold">
              {{ userInitials }}
            </div>
          </div>
        </div>
      </header>

      <!-- Contenu principal scrollable -->
      <main class="flex-1 overflow-y-auto p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import Sidebar from './Sidebar.vue';

const route = useRoute();
const authStore = useAuthStore();

const pageTitle = computed(() => {
  const titles: Record<string, string> = {
    '/dashboard': 'Tableau de bord',
    '/dashboard/employees': 'Gestion du personnel',
    '/dashboard/payroll': 'Paie et bulletins',
    '/dashboard/leaves': 'Congés et absences',
    '/dashboard/declarations': 'Déclarations',
    '/dashboard/my-bulletins': 'Mes bulletins de paie',
    '/dashboard/my-leaves': 'Mes demandes de congé',
    '/dashboard/profile': 'Mon profil',
  };
  return titles[route.path] || 'Tableau de bord';
});

const currentDate = computed(() => {
  return new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
});

const userInitials = computed(() => {
  if (!authStore.user?.fullname) return 'NA';
  const parts = authStore.user.fullname.split(' ');
  return parts.map(p => p[0]).join('').toUpperCase().slice(0, 2);
});
</script>