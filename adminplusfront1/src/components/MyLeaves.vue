<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Mes demandes de congé</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez vos congés et consultez votre solde</p>
      </div>
      <button @click="showModal = true" class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Nouvelle demande
      </button>
    </div>

    <!-- Message de succès -->
    <div v-if="successMessage" class="bg-green-50 border-2 border-green-200 rounded-lg p-4 flex items-center gap-3">
      <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <p class="text-green-700 font-semibold">{{ successMessage }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-semibold opacity-90">Solde disponible</h3>
          <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <p class="text-5xl font-bold mb-2">{{ soldeConges }}</p>
        <p class="text-sm opacity-75">jours de congé</p>
      </div>

      <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-semibold opacity-90">En attente</h3>
          <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <p class="text-5xl font-bold mb-2">{{ stats.enAttente }}</p>
        <p class="text-sm opacity-75">demande(s)</p>
      </div>

      <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-semibold opacity-90">Approuvées</h3>
          <svg class="w-8 h-8 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <p class="text-5xl font-bold mb-2">{{ stats.approuvees }}</p>
        <p class="text-sm opacity-75">cette année</p>
      </div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
      <div class="flex items-center gap-4">
        <select v-model="filters.statut" @change="loadConges" class="input-field w-auto">
          <option value="">Tous les statuts</option>
          <option value="en_attente">En attente</option>
          <option value="approuvé">Approuvé</option>
          <option value="refusé">Refusé</option>
        </select>

        <select v-model="filters.type" @change="loadConges" class="input-field w-auto">
          <option value="">Tous les types</option>
          <option value="annuel">Congé annuel</option>
          <option value="maladie">Congé maladie</option>
          <option value="maternite">Congé maternité</option>
          <option value="autre">Autre</option>
        </select>

        <button @click="loadConges" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
          Actualiser
        </button>
      </div>
    </div>

    <!-- Loader -->
    <div v-if="loading" class="flex items-center justify-center py-12">
      <div class="text-center">
        <svg class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-600">Chargement de vos congés...</p>
      </div>
    </div>

    <div v-else class="bg-white rounded-xl shadow-sm border border-gray-100">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Type</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Date début</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Date fin</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Durée</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Statut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Motif</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="conges.length === 0">
              <td colspan="6" class="py-12 text-center text-gray-500">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-semibold text-gray-700">Aucune demande de congé</p>
                <p class="text-sm text-gray-500 mt-2">Créez votre première demande</p>
              </td>
            </tr>
            <tr v-for="conge in conges" :key="conge.id" class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
              <td class="py-4 px-6">
                <span :class="conge.typeClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ conge.typeLabel }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ formatDate(conge.date_debut) }}</td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ formatDate(conge.date_fin) }}</td>
              <td class="py-4 px-6">
                <span class="text-sm font-bold text-gray-900">{{ conge.nb_jours }} jour(s)</span>
              </td>
              <td class="py-4 px-6">
                <span :class="conge.statutClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ conge.statutLabel }}
                </span>
              </td>
              <td class="py-4 px-6">
                <p class="text-sm text-gray-600 line-clamp-2">{{ conge.motif || 'Aucun motif' }}</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="p-6 border-t border-gray-100">
        <p class="text-sm text-gray-600">Affichage de {{ conges.length }} demande(s)</p>
      </div>
    </div>

    <CongeModal
      v-if="showModal"
      :show="showModal"
      :collaborator-id="collaboratorId"
      @close="showModal = false"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import CongeModal from '../components/CongeModal.vue';
import api from '../services/api/axios';

const authStore = useAuthStore();
const conges = ref<any[]>([]);
const loading = ref(false);
const showModal = ref(false);
const successMessage = ref('');
const collaboratorId = ref<number | null>(null);

const filters = ref({
  statut: '',
  type: ''
});

const stats = computed(() => {
  return {
    enAttente: conges.value.filter(c => c.statut === 'en_attente').length,
    approuvees: conges.value.filter(c => c.statut === 'approuvé').length,
  };
});

const soldeConges = computed(() => {
  const joursPris = conges.value
    .filter(c => c.statut === 'approuvé')
    .reduce((sum, c) => sum + c.nb_jours, 0);
  return 30 - joursPris;
});

onMounted(async () => {
  await loadCollaboratorInfo();
  await loadConges();
});

const loadCollaboratorInfo = async () => {
  try {
    const userResponse = await api.get('/me');
    const userId = userResponse.data.id;

    const collabResponse = await api.get('/collaborators');
    const collabData = Array.isArray(collabResponse.data) ? collabResponse.data : collabResponse.data.data || [];
    const myCollab = collabData.find((c: any) => c.user_id === userId);

    if (myCollab) {
      collaboratorId.value = myCollab.id;
    }
  } catch (error) {
    console.error('Erreur chargement info collaborateur:', error);
  }
};

const loadConges = async () => {
  if (!collaboratorId.value) return;

  loading.value = true;
  try {
    const response = await api.get(`/collaborators/${collaboratorId.value}/conges`);
    const data = Array.isArray(response.data) ? response.data : response.data.historique || [];

    conges.value = data
      .filter((c: any) => {
        const matchStatut = !filters.value.statut || c.statut === filters.value.statut;
        const matchType = !filters.value.type || c.type === filters.value.type;
        return matchStatut && matchType;
      })
      .map((c: any) => ({
        id: c.id,
        type: c.type,
        typeLabel: getTypeLabel(c.type),
        typeClass: getTypeClass(c.type),
        date_debut: c.date_debut,
        date_fin: c.date_fin,
        nb_jours: c.nb_jours || 0,
        statut: c.statut,
        statutLabel: getStatutLabel(c.statut),
        statutClass: getStatutClass(c.statut),
        motif: c.motif
      }))
      .sort((a: any, b: any) => {
        return new Date(b.date_debut).getTime() - new Date(a.date_debut).getTime();
      });
  } catch (error) {
    console.error('Erreur chargement congés:', error);
  } finally {
    loading.value = false;
  }
};

const handleSubmit = async (data: any) => {
  try {
    await api.post('/conges', {
      ...data,
      collaborator_id: collaboratorId.value
    });
    successMessage.value = 'Demande de congé envoyée avec succès !';
    showModal.value = false;
    await loadConges();

    setTimeout(() => {
      successMessage.value = '';
    }, 5000);
  } catch (error: any) {
    console.error('Erreur:', error);
    alert(error.response?.data?.message || 'Erreur lors de l\'envoi de la demande');
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return 'N/A';
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
};

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    'annuel': 'Congé annuel',
    'maladie': 'Congé maladie',
    'maternite': 'Congé maternité',
    'autre': 'Autre'
  };
  return labels[type] || type;
};

const getTypeClass = (type: string) => {
  const classes: Record<string, string> = {
    'annuel': 'bg-blue-100 text-blue-700',
    'maladie': 'bg-red-100 text-red-700',
    'maternite': 'bg-green-100 text-green-700',
    'autre': 'bg-orange-100 text-orange-700'
  };
  return classes[type] || 'bg-gray-100 text-gray-700';
};

const getStatutLabel = (statut: string) => {
  const labels: Record<string, string> = {
    'en_attente': 'En attente',
    'approuvé': 'Approuvé',
    'refusé': 'Refusé'
  };
  return labels[statut] || statut;
};

const getStatutClass = (statut: string) => {
  const classes: Record<string, string> = {
    'en_attente': 'bg-orange-100 text-orange-700',
    'approuvé': 'bg-green-100 text-green-700',
    'refusé': 'bg-red-100 text-red-700'
  };
  return classes[statut] || 'bg-gray-100 text-gray-700';
};
</script>