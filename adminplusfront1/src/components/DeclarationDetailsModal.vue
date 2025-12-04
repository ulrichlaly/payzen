<template>
  <Transition name="modal">
    <div v-if="show && declaration" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm" @click="$emit('close')"></div>

      <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 rounded-t-2xl z-10">
          <div class="flex items-center justify-between text-white">
            <div>
              <h3 class="text-xl font-bold">Détails de la déclaration</h3>
              <p class="text-sm text-indigo-100 mt-1">{{ declaration.typeLabel }}</p>
            </div>
            <button @click="$emit('close')" class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-6">
          <!-- Statut Badge -->
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <span class="text-sm font-semibold text-gray-700">Statut actuel</span>
            <span :class="declaration.statutClass" class="px-4 py-2 rounded-full text-sm font-bold">
              {{ declaration.statutLabel }}
            </span>
          </div>

          <!-- Informations principales -->
          <div class="grid grid-cols-2 gap-4">
            <div class="p-4 border-2 border-gray-200 rounded-lg">
              <p class="text-xs text-gray-500 mb-1">Type</p>
              <p class="text-sm font-bold text-gray-900">{{ declaration.typeLabel }}</p>
            </div>

            <div class="p-4 border-2 border-gray-200 rounded-lg">
              <p class="text-xs text-gray-500 mb-1">Organisme</p>
              <p class="text-sm font-bold text-gray-900">{{ declaration.organism }}</p>
            </div>

            <div class="p-4 border-2 border-gray-200 rounded-lg">
              <p class="text-xs text-gray-500 mb-1">Période</p>
              <p class="text-sm font-bold text-gray-900">{{ declaration.periode }}</p>
            </div>

            <div class="p-4 border-2 border-gray-200 rounded-lg">
              <p class="text-xs text-gray-500 mb-1">Référence</p>
              <p class="text-sm font-mono font-bold text-gray-900">{{ declaration.reference || 'N/A' }}</p>
            </div>
          </div>

          <!-- Montant total -->
          <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm opacity-90 mb-1">Montant total</p>
                <p class="text-4xl font-bold">{{ formatMontant(declaration.montant_total) }}</p>
              </div>
              <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
          </div>

          <!-- Détails selon le type -->
          <div v-if="declaration.type === 'sociale'" class="bg-blue-50 border-2 border-blue-200 rounded-xl p-5">
            <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              Détail des cotisations CNSS
            </h4>
            <div class="space-y-2">
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-700">Part employeur (16.4%)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(declaration.montant_total * 0.82) }}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-700">Part salarié (3.6%)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(declaration.montant_total * 0.18) }}</span>
              </div>
            </div>
          </div>

          <div v-else-if="declaration.type === 'fiscale'" class="bg-green-50 border-2 border-green-200 rounded-xl p-5">
            <h4 class="font-bold text-green-900 mb-4 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              Détail des prélèvements fiscaux
            </h4>
            <div class="space-y-2">
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-700">Impôt sur le Revenu (IR)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(declaration.montant_total * 0.8) }}</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <span class="text-sm text-gray-700">Contribution PPN (2.5%)</span>
                <span class="font-bold text-gray-900">{{ formatMontant(declaration.montant_total * 0.2) }}</span>
              </div>
            </div>
          </div>

          <!-- Détails additionnels -->
          <div v-if="declaration.details" class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
            <p class="text-xs font-semibold text-gray-600 mb-2">Notes / Détails</p>
            <p class="text-sm text-gray-800">{{ declaration.details }}</p>
          </div>

          <!-- Date de déclaration -->
          <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg">
            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div>
              <p class="text-xs text-gray-500">Date de déclaration</p>
              <p class="text-sm font-bold text-gray-900">{{ formatDate(declaration.date_declaration) }}</p>
            </div>
          </div>

          <!-- Info échéance -->
          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div class="text-sm text-yellow-800">
                <p class="font-semibold mb-1">Rappel</p>
                <p>Les déclarations doivent être soumises avant le <strong>15 du mois</strong> suivant la période concernée.</p>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-3 pt-4 border-t-2 border-gray-100">
            <button @click="$emit('close')" class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
              Fermer
            </button>
            <button @click="downloadPDF" class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Télécharger PDF
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
interface Declaration {
  id: number;
  type: string;
  typeLabel: string;
  organism: string;
  periode: string;
  montant_total: number;
  date_declaration: string;
  reference: string;
  statut: string;
  statutLabel: string;
  statutClass: string;
  details?: string;
}

defineProps<{
  show: boolean;
  declaration: Declaration | null;
}>();

const emit = defineEmits(['close']);

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat('fr-FR', { 
    minimumFractionDigits: 0 
  }).format(montant) + ' FCFA';
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return 'N/A';
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', { 
    day: '2-digit', 
    month: 'long', 
    year: 'numeric' 
  });
};

const downloadPDF = () => {
  alert('Téléchargement du PDF en cours...');
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