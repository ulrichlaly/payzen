<template>
  <div v-if="loading" class="flex items-center justify-center min-h-screen">
    <div class="text-center">
      <svg class="animate-spin h-16 w-16 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="text-gray-600 font-semibold">Chargement de votre espace...</p>
    </div>
  </div>

  <div v-else>
    <AdminDashboard v-if="authStore.isAdmin" />
    <ComptableDashboard v-else-if="authStore.isComptable" />
    <CollaborateurDashboard v-else-if="authStore.isCollaborateur" />

    <div v-else class="flex items-center justify-center min-h-screen">
      <div class="max-w-md mx-auto text-center p-8">
        <div class="bg-red-50 rounded-2xl p-8 border-2 border-red-200">
          <svg class="w-20 h-20 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
          <h3 class="text-xl font-bold text-red-800 mb-2">Rôle non reconnu</h3>
          <p class="text-red-600 font-semibold mb-4">{{ authStore.userRole }}</p>
          
          <div class="bg-white rounded-lg p-4 mb-4 text-left">
            <p class="text-xs text-gray-600 mb-1"><strong>Utilisateur :</strong></p>
            <p class="text-sm text-gray-900 mb-2">{{ authStore.user?.fullname }}</p>
            
            <p class="text-xs text-gray-600 mb-1"><strong>Email :</strong></p>
            <p class="text-sm text-gray-900 mb-2">{{ authStore.user?.email }}</p>
            
            <p class="text-xs text-gray-600 mb-1"><strong>Rôles assignés :</strong></p>
            <p class="text-sm text-gray-900">{{ authStore.userRoles.join(', ') || 'Aucun' }}</p>
          </div>
          
          <p class="text-gray-600 text-sm mb-4">Veuillez contacter l'administrateur</p>
          
          <button 
            @click="handleLogout" 
            class="px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors"
          >
            Se déconnecter
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import AdminDashboard from './AdminDashboard.vue';
import ComptableDashboard from './ComptableDashboard.vue';
import CollaborateurDashboard from './CollaborateurDashboard.vue';

const authStore = useAuthStore();
const router = useRouter();
const loading = ref(true);

onMounted(async () => {
  try {
    if (!authStore.user) {
      authStore.restoreUser();
    }
    
    if (!authStore.user) {
      await authStore.fetchUser();
    }

    console.log('=== DASHBOARD ROUTER ===');
    console.log('User:', authStore.user);
    console.log('Rôle principal:', authStore.userRole);
    console.log('Tous les rôles:', authStore.userRoles);
    console.log('isAdmin:', authStore.isAdmin);
    console.log('isComptable:', authStore.isComptable);
    console.log('isCollaborateur:', authStore.isCollaborateur);
    console.log('=======================');
    
  } catch (error) {
    console.error('Erreur chargement utilisateur:', error);
    router.push('/login');
  } finally {
    loading.value = false;
  }
});

const handleLogout = async () => {
  await authStore.logout();
  router.push('/login');
};
</script>
