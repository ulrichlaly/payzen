<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-3xl font-bold text-gray-900">💼 Dashboard Comptable</h2>
        <p class="text-sm text-gray-500 mt-1">Bienvenue, {{ user?.fullname }}</p>
      </div>
      <div class="flex items-center gap-3">
        <span class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg font-semibold text-sm">
          Comptable
        </span>
      </div>
    </div>

    <!-- Stats financières -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Paies du mois</p>
            <h3 class="text-4xl font-bold mb-2">{{ stats.paies_du_mois || 0 }}</h3>
            <p class="text-xs opacity-75">{{ currentMonth }}</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
      </div>

      <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Déclarations en attente</p>
            <h3 class="text-4xl font-bold mb-2">{{ stats.declarations_en_attente || 0 }}</h3>
            <p class="text-xs opacity-75">À traiter</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 text-white">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm opacity-90 mb-1">Montant total mois</p>
            <h3 class="text-3xl font-bold mb-2">{{ formatMontant(stats.montant_total_mois || 0) }}</h3>
            <p class="text-xs opacity-75">FCFA</p>
          </div>
          <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Actions principales -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Actions principales</h3>
      <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <router-link to="/dashboard/payroll" class="p-6 border-2 border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all group text-center">
          <svg class="w-12 h-12 text-gray-400 group-hover:text-green-600 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-sm font-bold text-gray-700 group-hover:text-green-600">Paies</p>
          <p class="text-xs text-gray-500 mt-1">Gérer les paies</p>
        </router-link>

        <router-link to="/dashboard/declarations" class="p-6 border-2 border-gray-200 rounded-xl hover:border-purple-500 hover:bg-purple-50 transition-all group text-center">
          <svg class="w-12 h-12 text-gray-400 group-hover:text-purple-600 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-sm font-bold text-gray-700 group-hover:text-purple-600">Déclarations</p>
          <p class="text-xs text-gray-500 mt-1">CNSS, IR, PPN</p>
        </router-link>

        <button @click="generateReports" class="p-6 border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group text-center">
          <svg class="w-12 h-12 text-gray-400 group-hover:text-blue-600 mb-3 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="text-sm font-bold text-gray-700 group-hover:text-blue-600">Rapports</p>
          <p class="text-xs text-gray-500 mt-1">Générer rapports</p>
        </button>
      </div>
    </div>

    <!-- Calendrier fiscal et tâches -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <!-- Échéances fiscales -->
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📅 Échéances à venir</h3>
        <div class="space-y-3">
          <div class="flex items-start gap-4 p-4 rounded-lg border-2 border-orange-200 bg-orange-50">
            <div class="w-12 h-12 rounded-xl bg-orange-500 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">Déclaration CNSS</p>
              <p class="text-xs text-gray-600 mt-1">Échéance : 15 {{ nextMonth }}</p>
              <p class="text-xs font-semibold text-orange-600 mt-1">⏰ {{ daysUntilDeadline }} jours restants</p>
            </div>
          </div>

          <div class="flex items-start gap-4 p-4 rounded-lg border-2 border-green-200 bg-green-50">
            <div class="w-12 h-12 rounded-xl bg-green-500 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">Déclaration IR</p>
              <p class="text-xs text-gray-600 mt-1">Échéance : 15 {{ nextMonth }}</p>
              <p class="text-xs font-semibold text-green-600 mt-1">⏰ {{ daysUntilDeadline }} jours restants</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Dernières activités -->
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Résumé comptable</h3>
        <div class="space-y-3">
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-700">Total paies générées</span>
            <span class="font-bold text-gray-900">{{ stats.paies_du_mois }}</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-700">Déclarations soumises</span>
            <span class="font-bold text-green-700">{{ stats.declarations_soumises || 0 }}</span>
          </div>
          <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
            <span class="text-sm text-gray-700">En attente</span>
            <span class="font-bold text-orange-700">{{ stats.declarations_en_attente }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import api from '../services/api/axios';

const authStore = useAuthStore();
const user = authStore.user;

const stats = ref({
  paies_du_mois: 0,
  declarations_en_attente: 0,
  declarations_soumises: 0,
  montant_total_mois: 0
});

const currentMonth = computed(() => {
  const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
  return months[new Date().getMonth()];
});

const nextMonth = computed(() => {
  const months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
  return months[(new Date().getMonth() + 1) % 12];
});

const daysUntilDeadline = computed(() => {
  const today = new Date();
  const deadline = new Date(today.getFullYear(), today.getMonth() + 1, 15);
  const diff = deadline.getTime() - today.getTime();
  return Math.ceil(diff / (1000 * 60 * 60 * 24));
});

onMounted(async () => {
  try {
    const response = await api.get('/dashboard');
    stats.value = response.data.stats || {};
  } catch (error) {
    console.error('Erreur chargement stats:', error);
  }
});

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0 }).format(montant);
};

const generateReports = () => {
  alert('Génération des rapports en cours...');
};
</script>