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
              @click="downloadPDF"
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
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
              Télécharger PDF
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
        <div id="bulletin-content" class="p-6 bg-white">
          <!-- En-tête République -->
          <div class="text-center mb-6 border-b-2 border-green-600 pb-4">
            <img
              src="/public/raynis.png"
              alt="Logo"
              class="mx-auto w-20 h-auto mb-2"
            />
            <h2 class="text-lg font-bold text-green-700">BULLETIN DE PAIE</h2>
            <p class="text-xs text-gray-600 mt-1">
              Période : {{ paie.periode }}
            </p>
          </div>

          <!-- Informations Employeur et Employé -->
          <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Employeur -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
              <h3
                class="font-bold text-gray-900 mb-2 text-xs uppercase border-b border-blue-300 pb-1"
              >
                Employeur
              </h3>
              <div class="space-y-1 text-xs">
                <div>
                  <span class="text-gray-600">Raison sociale :</span>
                  <p class="font-semibold text-gray-900">RAYNIS Bénin</p>
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
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
              <h3
                class="font-bold text-gray-900 mb-2 text-xs uppercase border-b border-green-300 pb-1"
              >
                Employé
              </h3>
              <div class="space-y-1 text-xs">
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
          <div class="mb-4">
            <h3
              class="font-bold text-gray-900 mb-3 text-sm border-b border-gray-300 pb-1"
            >
              Détail de la rémunération
            </h3>

            <!-- Gains -->
            <div class="mb-4">
              <h4
                class="font-semibold text-green-700 mb-2 flex items-center gap-1 text-xs"
              >
                GAINS
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <thead class="bg-gray-100">
                  <tr>
                    <th
                      class="text-left py-1 px-2 border-b border-gray-200 font-semibold"
                    >
                      Libellé
                    </th>
                    <th
                      class="text-right py-1 px-2 border-b border-gray-200 font-semibold"
                    >
                      Montant (FCFA)
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b border-gray-200">
                    <td class="py-1 px-2">Salaire de base</td>
                    <td class="py-1 px-2 text-right font-semibold">
                      {{ formatMontant(paie.salaireBase) }}
                    </td>
                  </tr>
                  <tr v-if="paie.primes > 0" class="border-b border-gray-200">
                    <td class="py-1 px-2">Primes et indemnités</td>
                    <td
                      class="py-1 px-2 text-right font-semibold text-green-600"
                    >
                      {{ formatMontant(paie.primes) }}
                    </td>
                  </tr>
                  <tr class="bg-green-50 font-bold">
                    <td class="py-1 px-2">TOTAL BRUT</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(paie.salaireBase + paie.primes) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Retenues -->
            <div class="mb-4">
              <h4
                class="font-semibold text-red-700 mb-2 flex items-center gap-1 text-xs"
              >
                RETENUES
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <thead class="bg-gray-100">
                  <tr>
                    <th
                      class="text-left py-1 px-2 border-b border-gray-200 font-semibold"
                    >
                      Libellé
                    </th>
                    <th
                      class="text-right py-1 px-2 border-b border-gray-200 font-semibold"
                    >
                      Montant (FCFA)
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="paie.retenues > 0" class="border-b border-gray-200">
                    <td class="py-1 px-2">
                      Retenues diverses (CNSS, avances, etc.)
                    </td>
                    <td class="py-1 px-2 text-right font-semibold text-red-600">
                      {{ formatMontant(paie.retenues) }}
                    </td>
                  </tr>
                  <tr v-else class="border-b border-gray-200">
                    <td class="py-1 px-2 text-gray-500 italic" colspan="2">
                      Aucune retenue
                    </td>
                  </tr>
                  <tr class="bg-red-50 font-bold">
                    <td class="py-1 px-2">TOTAL RETENUES</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(paie.retenues) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Net à payer -->
            <div
              class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-4"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs opacity-90 mb-1">NET À PAYER</p>
                  <p class="text-2xl font-bold">
                    {{ formatMontant(paie.netAPayer) }}
                  </p>
                </div>
                <div class="text-3xl font-bold opacity-50">FCFA</div>
              </div>
            </div>
          </div>

          <!-- Pied de page -->
          <div class="mt-6 pt-4 border-t border-gray-300">
            <div class="grid grid-cols-2 gap-6">
              <div class="text-center">
                <p class="text-xs text-gray-600 mb-6">
                  Signature de l'employeur
                </p>
                <div class="border-t border-gray-400 pt-2 mt-8">
                  <p class="text-xs text-gray-500">Cachet et signature</p>
                </div>
              </div>
              <div class="text-center">
                <p class="text-xs text-gray-600 mb-6">Signature de l'employé</p>
                <div class="border-t border-gray-400 pt-2 mt-8">
                  <p class="text-xs text-gray-500">Pour réception</p>
                </div>
              </div>
            </div>

            <div class="text-center mt-4 text-xs text-gray-500">
              <p>
                Document généré le {{ new Date().toLocaleDateString("fr-FR") }}
              </p>
              <p class="mt-1">
                PAYZEN - Système de gestion RH - République du Bénin
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
  if (!montant || isNaN(montant)) {
    return "0";
  }
  return new Intl.NumberFormat("fr-FR", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(montant);
};

const downloadPDF = async () => {
  const content = document.getElementById("bulletin-content");
  if (!content) return;

  const script = document.createElement("script");
  script.src =
    "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js";

  script.onload = () => {
    // @ts-ignore
    const opt = {
      margin: [10, 10, 10, 10],
      filename: `bulletin-paie-${Date.now()}.pdf`,
      image: { type: "jpeg", quality: 0.98 },
      html2canvas: {
        scale: 2,
        useCORS: true,
        letterRendering: true,
      },
      jsPDF: {
        unit: "mm",
        format: "a4",
        orientation: "portrait",
      },
      pagebreak: { mode: "avoid-all" },
    };

    // @ts-ignore
    html2pdf().set(opt).from(content).save();
  };

  document.head.appendChild(script);
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
</style>
