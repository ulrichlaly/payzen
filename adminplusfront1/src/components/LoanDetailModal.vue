<template>
  <Transition name="modal">
    <div
      v-if="show && loan"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
      <div
        class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
        @click="$emit('close')"
      ></div>

      <div
        class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
      >
        <!-- Header -->
        <div
          class="sticky top-0 bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-2xl z-10"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 text-white">
              <svg
                class="w-8 h-8"
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
              <div>
                <h3 class="text-xl font-bold">Détails du prêt</h3>
                <p class="text-sm text-purple-100">Demande #{{ loan.id }}</p>
              </div>
            </div>
            <button
              @click="$emit('close')"
              class="p-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors text-white"
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

        <!-- Body -->
        <div class="p-6 space-y-6">
          <!-- Informations collaborateur -->
          <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-5">
            <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
              <svg
                class="w-5 h-5 text-purple-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                />
              </svg>
              Collaborateur
            </h4>
            <div class="flex items-center gap-4">
              <div
                :class="loan.avatarColor"
                class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-xl"
              >
                {{ loan.initials }}
              </div>
              <div>
                <p class="text-lg font-bold text-gray-900">
                  {{ loan.employeeName }}
                </p>
                <p class="text-sm text-gray-600">
                  Date de demande :
                  {{ formatDate(loan.date_demande || new Date()) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Détails du prêt -->
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
              <p class="text-xs font-medium text-gray-600 mb-1">Type</p>
              <span
                :class="getTypeClass(loan.type)"
                class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ loan.type }}
              </span>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
              <p class="text-xs font-medium text-gray-600 mb-1">Statut</p>
              <span
                :class="loan.statusClass"
                class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ loan.statut }}
              </span>
            </div>

            <div
              class="col-span-2 bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-300 rounded-xl p-5"
            >
              <p class="text-sm font-medium text-gray-600 mb-1">
                Montant demandé
              </p>
              <p class="text-3xl font-bold text-purple-700">
                {{ formatMontant(loan.montant) }}
              </p>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
              <p class="text-xs font-medium text-gray-600 mb-2">
                Durée de remboursement
              </p>
              <p class="text-2xl font-bold text-gray-900">
                {{ loan.duree }} mois
              </p>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
              <p class="text-xs font-medium text-gray-600 mb-2">
                Mensualité
              </p>
              <p class="text-2xl font-bold text-blue-600">
                {{ formatMontant(loan.montant / loan.duree) }}
              </p>
            </div>

            <div
              v-if="loan.statut === 'En cours'"
              class="col-span-2 bg-blue-50 border-2 border-blue-200 rounded-xl p-5"
            >
              <p class="text-sm font-medium text-gray-600 mb-1">
                Montant restant à rembourser
              </p>
              <p class="text-2xl font-bold text-blue-700">
                {{ formatMontant(loan.montantRestant) }}
              </p>
              <div class="mt-3 bg-white rounded-full h-3 overflow-hidden">
                <div
                  class="bg-blue-600 h-full transition-all duration-300"
                  :style="{
                    width: `${
                      ((loan.montant - loan.montantRestant) / loan.montant) *
                      100
                    }%`,
                  }"
                ></div>
              </div>
              <p class="text-xs text-gray-600 mt-2">
                {{
                  Math.round(
                    ((loan.montant - loan.montantRestant) / loan.montant) * 100
                  )
                }}% remboursé
              </p>
            </div>
          </div>

          <!-- Motif -->
          <div v-if="loan.motif" class="space-y-2">
            <h4 class="font-bold text-gray-800 flex items-center gap-2">
              <svg
                class="w-5 h-5 text-gray-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h7"
                />
              </svg>
              Motif de la demande
            </h4>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-700 whitespace-pre-wrap">
                {{ loan.motif }}
              </p>
            </div>
          </div>

          <!-- Actions (si en attente) -->
          <div
            v-if="loan.statut === 'En attente'"
            class="flex gap-3 pt-4 border-t-2 border-gray-100"
          >
            <button
              @click="$emit('reject', loan)"
              class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors flex items-center justify-center gap-2"
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
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
              Rejeter
            </button>
            <button
              @click="$emit('approve', loan)"
              class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2"
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
                  d="M5 13l4 4L19 7"
                />
              </svg>
              Approuver
            </button>
          </div>

          <!-- Bouton fermer (autres statuts) -->
          <div
            v-else
            class="flex justify-center pt-4 border-t-2 border-gray-100"
          >
            <button
              @click="$emit('close')"
              class="px-8 py-3 bg-gray-600 text-white rounded-lg font-semibold hover:bg-gray-700 transition-colors"
            >
              Fermer
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
interface Loan {
  id: number;
  employeeName: string;
  initials: string;
  avatarColor: string;
  type: string;
  montant: number;
  duree: number;
  montantRestant: number;
  statut: string;
  statusClass: string;
  motif?: string;
  date_demande?: string;
}

defineProps<{
  show: boolean;
  loan: Loan | null;
}>();

defineEmits(["close", "approve", "reject"]);

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", {
      minimumFractionDigits: 0,
    }).format(montant) + " FCFA"
  );
};

const formatDate = (date: string | Date) => {
  return new Date(date).toLocaleDateString("fr-FR");
};

const getTypeClass = (type: string) => {
  const map: Record<string, string> = {
    Prêt: "bg-purple-100 text-purple-700",
    Avance: "bg-blue-100 text-blue-700",
  };
  return map[type] || "bg-gray-100 text-gray-700";
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