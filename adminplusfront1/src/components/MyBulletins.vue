<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Mes bulletins de paie</h2>
        <p class="text-sm text-gray-500 mt-1">Consultez et téléchargez vos bulletins</p>
      </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center gap-4">
        <select v-model="filters.annee" @change="loadBulletins" class="input-field w-auto">
          <option value="">Toutes les années</option>
          <option value="2025">2025</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>

        <select v-model="filters.mois" @change="loadBulletins" class="input-field w-auto">
          <option value="">Tous les mois</option>
          <option value="01">Janvier</option>
          <option value="02">Février</option>
          <option value="03">Mars</option>
          <option value="04">Avril</option>
          <option value="05">Mai</option>
          <option value="06">Juin</option>
          <option value="07">Juillet</option>
          <option value="08">Août</option>
          <option value="09">Septembre</option>
          <option value="10">Octobre</option>
          <option value="11">Novembre</option>
          <option value="12">Décembre</option>
        </select>

        <button @click="loadBulletins" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">
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
        <p class="text-gray-600">Chargement de vos bulletins...</p>
      </div>
    </div>

    <!-- Liste des bulletins -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
      <div v-if="bulletins.length === 0" class="col-span-full text-center py-12">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-lg font-semibold text-gray-700">Aucun bulletin trouvé</p>
        <p class="text-sm text-gray-500 mt-2">Vos bulletins apparaîtront ici une fois générés</p>
      </div>

      <div v-for="bulletin in bulletins" :key="bulletin.id" class="bg-white rounded-xl p-6 shadow-sm border-2 border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all cursor-pointer">
        <div class="flex items-start justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
          <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
            Payé
          </span>
        </div>

        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ bulletin.periode }}</h3>
        
        <div class="space-y-2 mb-4">
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Salaire brut</span>
            <span class="font-semibold text-gray-900">{{ formatMontant(bulletin.salaire_brut) }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-600">Retenues</span>
            <span class="font-semibold text-red-600">-{{ formatMontant(bulletin.retenues) }}</span>
          </div>
          <div class="flex justify-between pt-2 border-t-2 border-gray-200">
            <span class="text-sm font-semibold text-gray-900">Net à payer</span>
            <span class="text-lg font-bold text-green-700">{{ formatMontant(bulletin.net_a_payer) }}</span>
          </div>
        </div>

        <div class="flex gap-2">
          <button @click="viewBulletin(bulletin)" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            Voir
          </button>
          <button @click="downloadBulletin(bulletin)" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-sm flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            PDF
          </button>
        </div>
      </div>
    </div>

    <!-- Modal visualisation -->
    <BulletinModal
      v-if="showBulletinModal"
      :show="showBulletinModal"
      :paie="selectedBulletin"
      @close="showBulletinModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import BulletinModal from '../components/BulletinModal.vue';
import api from '../services/api/axios';

const authStore = useAuthStore();
const bulletins = ref<any[]>([]);
const loading = ref(false);
const showBulletinModal = ref(false);
const selectedBulletin = ref<any>(null);

const filters = ref({
  annee: new Date().getFullYear().toString(),
  mois: ''
});

onMounted(() => {
  loadBulletins();
});

const loadBulletins = async () => {
  loading.value = true;
  try {
    const userResponse = await api.get('/me');
    const userId = userResponse.data.id;

    const collabResponse = await api.get('/collaborators');
    const collabData = Array.isArray(collabResponse.data) ? collabResponse.data : collabResponse.data.data || [];
    const myCollab = collabData.find((c: any) => c.user_id === userId);

    if (!myCollab) {
      console.error('Collaborateur non trouvé');
      return;
    }

    const response = await api.get(`/collaborators/${myCollab.id}/paies`);
    const data = Array.isArray(response.data) ? response.data : response.data.historique || [];
    
    bulletins.value = data
      .filter((p: any) => {
        const matchAnnee = !filters.value.annee || p.annee === filters.value.annee;
        const matchMois = !filters.value.mois || p.mois === filters.value.mois;
        return matchAnnee && matchMois;
      })
      .map((p: any) => ({
        id: p.id,
        periode: `${getMoisLabel(p.mois)} ${p.annee}`,
        salaire_brut: parseFloat(p.salaire_base || 0) + parseFloat(p.prime || 0) + parseFloat(p.indemnite || 0),
        retenues: parseFloat(p.retenue || 0),
        net_a_payer: parseFloat(p.net_a_payer || 0),
        mois: p.mois,
        annee: p.annee
      }))
      .sort((a: any, b: any) => {
        if (b.annee !== a.annee) return b.annee.localeCompare(a.annee);
        return b.mois.localeCompare(a.mois);
      });
  } catch (error) {
    console.error('Erreur chargement bulletins:', error);
  } finally {
    loading.value = false;
  }
};

const viewBulletin = (bulletin: any) => {
  selectedBulletin.value = {
    ...bulletin,
    employeeName: authStore.user?.fullname,
    employeeMatricule: 'N/A',
    salaireBase: bulletin.salaire_brut - (bulletin.salaire_brut * 0.20),
    primes: bulletin.salaire_brut * 0.20,
    retenues: bulletin.retenues
  };
  showBulletinModal.value = true;
};

const downloadBulletin = async (bulletin: any) => {
  alert(`Téléchargement du bulletin ${bulletin.periode}...`);
};

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 0 }).format(montant) + ' FCFA';
};

const getMoisLabel = (mois: string) => {
  const labels: Record<string, string> = {
    '01': 'Janvier', '02': 'Février', '03': 'Mars', '04': 'Avril',
    '05': 'Mai', '06': 'Juin', '07': 'Juillet', '08': 'Août',
    '09': 'Septembre', '10': 'Octobre', '11': 'Novembre', '12': 'Décembre'
  };
  return labels[mois] || mois;
};
</script>