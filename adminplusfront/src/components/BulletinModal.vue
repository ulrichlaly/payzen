<template>
  <Transition name="modal">
    <div
      v-if="show && paie"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
      <div
        class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
        @click="$emit('close')"
      ></div>

      <div
        class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
      >
        <!-- Header Actions -->
        <div
          class="sticky top-0 bg-white border-b-2 border-gray-200 px-6 py-4 rounded-t-2xl z-10 flex items-center justify-between"
        >
          <h3 class="text-xl font-bold text-gray-900">Bulletin de Paie</h3>
          <div class="flex items-center gap-2">
            <button
              @click="printBulletin"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2"
            >
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                />
              </svg>
              Imprimer
            </button>
            <button
              @click="$emit('close')"
              class="p-2 hover:bg-gray-100 rounded-lg"
            >
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>

        <!-- Bulletin Content -->
        <div id="bulletin-content" class="p-8 bg-white">
          <!-- En-tête République -->
          <div class="text-center mb-8 border-b-4 border-green-600 pb-6">
            <img
              src="/public/raynis.png"
              alt="Logo"
              class="mx-auto w-24 h-auto mb-2"
            />
            <h2 class="text-xl font-bold text-green-700">BULLETIN DE PAIE</h2>
            <p class="text-sm text-gray-600 mt-2">
              Période : {{ paie.periode }}
            </p>
          </div>

          <!-- Informations Employeur et Employé -->
          <div class="grid grid-cols-2 gap-6 mb-8">
            <!-- Employeur -->
            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
              <h3
                class="font-bold text-gray-900 mb-3 text-sm uppercase border-b border-blue-300 pb-2"
              >
                Employeur
              </h3>
              <div class="space-y-2 text-sm">
                <div>
                  <span class="text-gray-600">Raison sociale :</span>
                  <p class="font-semibold text-gray-900">Admin+ Bénin</p>
                </div>
                <div>
                  <span class="text-gray-600">Adresse :</span>
                  <p class="font-semibold text-gray-900">Cotonou, Bénin</p>
                </div>
                <div>
                  <span class="text-gray-600">N° CNSS :</span>
                  <p class="font-semibold text-gray-900">123456789</p>
                </div>
              </div>
            </div>

            <!-- Employé -->
            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4">
              <h3
                class="font-bold text-gray-900 mb-3 text-sm uppercase border-b border-green-300 pb-2"
              >
                Employé
              </h3>
              <div class="space-y-2 text-sm">
                <div>
                  <span class="text-gray-600">Nom complet :</span>
                  <p class="font-semibold text-gray-900">
                    {{ paie.employeeName }}
                  </p>
                </div>
                <div>
                  <span class="text-gray-600">Matricule :</span>
                  <p class="font-semibold text-gray-900">
                    {{ paie.employeeMatricule }}
                  </p>
                </div>
                <div>
                  <span class="text-gray-600">Période :</span>
                  <p class="font-semibold text-gray-900">{{ paie.periode }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Détails de la paie -->
          <div class="mb-8">
            <h3
              class="font-bold text-gray-900 mb-4 text-lg border-b-2 border-gray-300 pb-2"
            >
              Détail de la rémunération
            </h3>

            <!-- Gains -->
            <div class="mb-6">
              <h4
                class="font-semibold text-green-700 mb-3 flex items-center gap-2"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                  />
                </svg>
                GAINS
              </h4>
              <table class="w-full border-2 border-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th
                      class="text-left py-2 px-4 border-b-2 border-gray-200 font-semibold text-sm"
                    >
                      Libellé
                    </th>
                    <th
                      class="text-right py-2 px-4 border-b-2 border-gray-200 font-semibold text-sm"
                    >
                      Montant (FCFA)
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b border-gray-200">
                    <td class="py-2 px-4 text-sm">Salaire de base</td>
                    <td class="py-2 px-4 text-sm text-right font-semibold">
                      {{ formatMontant(paie.salaireBase) }}
                    </td>
                  </tr>
                  <tr v-if="paie.primes > 0" class="border-b border-gray-200">
                    <td class="py-2 px-4 text-sm">Primes et indemnités</td>
                    <td
                      class="py-2 px-4 text-sm text-right font-semibold text-green-600"
                    >
                      {{ formatMontant(paie.primes) }}
                    </td>
                  </tr>
                  <tr class="bg-green-50 font-bold">
                    <td class="py-3 px-4 text-sm">TOTAL BRUT</td>
                    <td class="py-3 px-4 text-sm text-right">
                      {{ formatMontant(paie.salaireBase + paie.primes) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Retenues -->
            <div class="mb-6">
              <h4
                class="font-semibold text-red-700 mb-3 flex items-center gap-2"
              >
                <svg
                  class="w-5 h-5"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20 12H4"
                  />
                </svg>
                RETENUES
              </h4>
              <table class="w-full border-2 border-gray-200">
                <thead class="bg-gray-100">
                  <tr>
                    <th
                      class="text-left py-2 px-4 border-b-2 border-gray-200 font-semibold text-sm"
                    >
                      Libellé
                    </th>
                    <th
                      class="text-right py-2 px-4 border-b-2 border-gray-200 font-semibold text-sm"
                    >
                      Montant (FCFA)
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="paie.retenues > 0" class="border-b border-gray-200">
                    <td class="py-2 px-4 text-sm">
                      Retenues diverses (CNSS, avances, etc.)
                    </td>
                    <td
                      class="py-2 px-4 text-sm text-right font-semibold text-red-600"
                    >
                      {{ formatMontant(paie.retenues) }}
                    </td>
                  </tr>
                  <tr v-else class="border-b border-gray-200">
                    <td
                      class="py-2 px-4 text-sm text-gray-500 italic"
                      colspan="2"
                    >
                      Aucune retenue
                    </td>
                  </tr>
                  <tr class="bg-red-50 font-bold">
                    <td class="py-3 px-4 text-sm">TOTAL RETENUES</td>
                    <td class="py-3 px-4 text-sm text-right">
                      {{ formatMontant(paie.retenues) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Net à payer -->
            <div
              class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl p-6"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm opacity-90 mb-1">NET À PAYER</p>
                  <p class="text-4xl font-bold">
                    {{ formatMontant(paie.netAPayer) }}
                  </p>
                </div>
                <svg
                  class="w-16 h-16 opacity-50"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
            </div>
          </div>

          <!-- Pied de page -->
          <div class="mt-12 pt-6 border-t-2 border-gray-300">
            <div class="grid grid-cols-2 gap-8">
              <div class="text-center">
                <p class="text-sm text-gray-600 mb-8">
                  Signature de l'employeur
                </p>
                <div class="border-t-2 border-gray-400 pt-2 mt-12">
                  <p class="text-xs text-gray-500">Cachet et signature</p>
                </div>
              </div>
              <div class="text-center">
                <p class="text-sm text-gray-600 mb-8">Signature de l'employé</p>
                <div class="border-t-2 border-gray-400 pt-2 mt-12">
                  <p class="text-xs text-gray-500">Pour réception</p>
                </div>
              </div>
            </div>

            <div class="text-center mt-8 text-xs text-gray-500">
              <p>
                Document généré le {{ new Date().toLocaleDateString("fr-FR") }}
              </p>
              <p class="mt-1">
                Admin+ - Système de gestion RH - République du Bénin
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
interface Paie {
  id: number;
  employeeName: string;
  employeeMatricule: string;
  periode: string;
  salaireBase: number;
  primes: number;
  retenues: number;
  netAPayer: number;
}

defineProps<{
  show: boolean;
  paie: Paie | null;
}>();

const emit = defineEmits(["close"]);

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat("fr-FR", {
    style: "currency",
    currency: "XOF",
    minimumFractionDigits: 0,
  }).format(montant);
};

const printBulletin = () => {
  window.print();
};
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s;
}
.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

@media print {
  .sticky {
    position: relative !important;
  }
  button {
    display: none !important;
  }
}
</style>
