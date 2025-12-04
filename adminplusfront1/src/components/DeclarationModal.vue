<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="$emit('close')"></div>

      <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-2xl z-10">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <div>
                <h3 class="text-xl font-bold">Nouvelle déclaration</h3>
                <p class="text-sm text-purple-100">Créer une déclaration sociale ou fiscale</p>
              </div>
            </div>
            <button @click="$emit('close')" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors text-white">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Body -->
        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <!-- Type de déclaration -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Type de déclaration <span class="text-red-500">*</span>
            </label>
            <select
              v-model="formData.type"
              @change="onTypeChange"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
            >
              <option value="">Sélectionner un type...</option>
              <option value="sociale">Déclaration sociale (CNSS)</option>
              <option value="fiscale">Déclaration fiscale (IR)</option>
            </select>
          </div>

          <!-- Info selon le type -->
          <div v-if="formData.type" class="p-4 rounded-lg border-2" :class="typeInfoClass">
            <div class="flex items-start gap-3">
              <svg class="w-6 h-6 flex-shrink-0 mt-0.5" :class="typeInfoIconClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <p class="font-semibold mb-1" :class="typeInfoTextClass">{{ typeInfoTitle }}</p>
                <p class="text-sm" :class="typeInfoTextClass">{{ typeInfoDesc }}</p>
              </div>
            </div>
          </div>

          <!-- Sélection paie -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Basé sur la paie <span class="text-red-500">*</span>
            </label>
            <select
              v-model="formData.paie_id"
              @change="calculateMontant"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
            >
              <option value="">Sélectionner une paie...</option>
              <option v-for="paie in paies" :key="paie.id" :value="paie.id">
                {{ paie.label }}
              </option>
            </select>
          </div>

          <!-- Montant calculé -->
          <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              Calcul automatique
            </h4>

            <div v-if="formData.type === 'sociale'" class="space-y-3">
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-600">Part employeur (16.4%)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(montants.cnssEmployeur) }}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-600">Part salarié (3.6%)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(montants.cnssSalarie) }}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-purple-100 rounded-lg">
                <span class="text-sm font-semibold text-purple-900">Total CNSS</span>
                <span class="font-bold text-purple-900 text-lg">{{ formatMontant(montants.cnssTotal) }}</span>
              </div>
            </div>

            <div v-else-if="formData.type === 'fiscale'" class="space-y-3">
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-600">Impôt sur revenu (IR)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(montants.ir) }}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-600">Contribution PPN (2.5%)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(montants.ppn) }}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-green-100 rounded-lg">
                <span class="text-sm font-semibold text-green-900">Total fiscale</span>
                <span class="font-bold text-green-900 text-lg">{{ formatMontant(montants.fiscalTotal) }}</span>
              </div>
            </div>

            <div v-else class="text-center py-4 text-gray-500 text-sm">
              Sélectionnez un type de déclaration
            </div>
          </div>

          <!-- Référence -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Référence
            </label>
            <input
              v-model="formData.reference"
              type="text"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
              placeholder="Ex: DECL-2025-10-001"
            />
            <p class="text-xs text-gray-500 mt-1">Laissez vide pour génération automatique</p>
          </div>

          <!-- Détails supplémentaires -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Détails / Notes
            </label>
            <textarea
              v-model="formData.details"
              rows="3"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none resize-none"
              placeholder="Informations complémentaires..."
            ></textarea>
          </div>

          <!-- Info importante -->
          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <div class="text-sm text-yellow-800">
                <p class="font-semibold mb-1">Rappel des échéances</p>
                <p>• CNSS : à déposer avant le <strong>15 du mois</strong> suivant</p>
                <p>• IR : à déposer avant le <strong>15 du mois</strong> suivant</p>
              </div>
            </div>
          </div>

          <!-- Erreur -->
          <div v-if="error" class="bg-red-50 border-2 border-red-200 rounded-lg p-3 flex items-start gap-2">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-red-700 text-sm font-semibold">{{ error }}</p>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4 border-t-2 border-gray-100">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="loading || !isFormValid"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
            >
              <svg v-if="loading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ loading ? 'Création...' : 'Créer la déclaration' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import api from '../services/api/axios';

interface Paie {
  id: number;
  label: string;
  salaire_base: number;
  net_a_payer: number;
}

defineProps<{
  show: boolean;
}>();

const emit = defineEmits(['close', 'submit']);

const loading = ref(false);
const error = ref('');
const paies = ref<Paie[]>([]);
const selectedPaie = ref<Paie | null>(null);

const formData = ref({
  type: '',
  paie_id: '',
  reference: '',
  details: ''
});

const montants = ref({
  cnssEmployeur: 0,
  cnssSalarie: 0,
  cnssTotal: 0,
  ir: 0,
  ppn: 0,
  fiscalTotal: 0
});

onMounted(async () => {
  await loadPaies();
});

const loadPaies = async () => {
  try {
    const response = await api.get('/paies');
    const data = Array.isArray(response.data) ? response.data : response.data.data || [];
    paies.value = data.map((p: any) => ({
      id: p.id,
      label: `${p.collaborator?.user?.fullname || 'N/A'} - ${p.mois}/${p.annee}`,
      salaire_base: parseFloat(p.salaire_base || 0),
      net_a_payer: parseFloat(p.net_a_payer || 0)
    }));
  } catch (error) {
    console.error('Erreur chargement paies:', error);
  }
};

const onTypeChange = () => {
  calculateMontant();
};

const calculateMontant = () => {
  const paie = paies.value.find(p => p.id === Number(formData.value.paie_id));
  if (!paie) {
    resetMontants();
    return;
  }

  selectedPaie.value = paie;
  const salaireBase = paie.salaire_base;

  if (formData.value.type === 'sociale') {
    // CNSS : 16.4% employeur + 3.6% salarié
    montants.value.cnssEmployeur = salaireBase * 0.164;
    montants.value.cnssSalarie = salaireBase * 0.036;
    montants.value.cnssTotal = montants.value.cnssEmployeur + montants.value.cnssSalarie;
  } else if (formData.value.type === 'fiscale') {
    // IR : calcul simplifié (à adapter selon barème réel)
    montants.value.ir = salaireBase * 0.10; // 10% approximatif
    // PPN : 2.5%
    montants.value.ppn = salaireBase * 0.025;
    montants.value.fiscalTotal = montants.value.ir + montants.value.ppn;
  }
};

const resetMontants = () => {
  montants.value = {
    cnssEmployeur: 0,
    cnssSalarie: 0,
    cnssTotal: 0,
    ir: 0,
    ppn: 0,
    fiscalTotal: 0
  };
};

const typeInfoClass = computed(() => {
  return formData.value.type === 'sociale' ? 'border-blue-200 bg-blue-50' : 'border-green-200 bg-green-50';
});

const typeInfoIconClass = computed(() => {
  return formData.value.type === 'sociale' ? 'text-blue-600' : 'text-green-600';
});

const typeInfoTextClass = computed(() => {
  return formData.value.type === 'sociale' ? 'text-blue-900' : 'text-green-900';
});

const typeInfoTitle = computed(() => {
  return formData.value.type === 'sociale' ? 'Déclaration CNSS' : 'Déclaration fiscale (IR)';
});

const typeInfoDesc = computed(() => {
  return formData.value.type === 'sociale' 
    ? 'Cotisations sociales obligatoires : 16.4% (employeur) + 3.6% (salarié) = 20% du salaire brut'
    : 'Impôt sur le revenu (barème progressif) + Contribution PPN (2.5%)';
});

const isFormValid = computed(() => {
  return formData.value.type && formData.value.paie_id;
});

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { 
    minimumFractionDigits: 0 
  }).format(montant) + ' FCFA';
};

const handleSubmit = () => {
  error.value = '';
  
  if (!isFormValid.value) {
    error.value = 'Veuillez remplir tous les champs obligatoires';
    return;
  }

  loading.value = true;

  const montant_total = formData.value.type === 'sociale' 
    ? montants.value.cnssTotal 
    : montants.value.fiscalTotal;

  const submitData = {
    type: formData.value.type,
    paie_id: Number(formData.value.paie_id),
    montant_total: montant_total,
    reference: formData.value.reference || `DECL-${Date.now()}`,
    details: formData.value.details,
    date_declaration: new Date().toISOString().split('T')[0],
    statut: 'en_attente'
  };

  emit('submit', submitData);
  
  setTimeout(() => {
    loading.value = false;
  }, 2000);
};
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.2s;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
</style>