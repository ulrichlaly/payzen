<template>
  <Transition name="modal">
    <div
      v-if="show && expense"
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
          class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-2xl z-10"
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
                  d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"
                />
              </svg>
              <div>
                <h3 class="text-xl font-bold">Détails de la note de frais</h3>
                <p class="text-sm text-blue-100">
                  Demande #{{ expense.id }}
                </p>
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
          <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-5">
            <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
              <svg
                class="w-5 h-5 text-blue-600"
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
                :class="expense.avatarColor"
                class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-xl"
              >
                {{ expense.initials }}
              </div>
              <div>
                <p class="text-lg font-bold text-gray-900">
                  {{ expense.employeeName }}
                </p>
                <p class="text-sm text-gray-600">
                  Date de demande : {{ formatDate(expense.date) }}
                </p>
              </div>
            </div>
          </div>

          <!-- Détails de la dépense -->
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
              <p class="text-xs font-medium text-gray-600 mb-1">Catégorie</p>
              <span
                :class="getCategoryClass(expense.categorie)"
                class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ expense.categorie }}
              </span>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-xl p-4">
              <p class="text-xs font-medium text-gray-600 mb-1">Statut</p>
              <span
                :class="expense.statusClass"
                class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
              >
                {{ expense.statut }}
              </span>
            </div>

            <div
              class="col-span-2 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl p-5"
            >
              <p class="text-sm font-medium text-gray-600 mb-1">
                Montant demandé
              </p>
              <p class="text-3xl font-bold text-green-700">
                {{ formatMontant(expense.montant) }}
              </p>
            </div>
          </div>

          <!-- Description -->
          <div v-if="expense.description" class="space-y-2">
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
              Description
            </h4>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
              <p class="text-sm text-gray-700 whitespace-pre-wrap">
                {{ expense.description }}
              </p>
            </div>
          </div>

          <!-- Justificatif -->
          <div v-if="expense.justificatif" class="space-y-2">
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
                  d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                />
              </svg>
              Justificatif
            </h4>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
              <a
                :href="expense.justificatif"
                target="_blank"
                class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2"
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
                Télécharger le justificatif
              </a>
            </div>
          </div>

          <!-- Actions (si en attente) -->
          <div
            v-if="expense.statut === 'En attente'"
            class="flex gap-3 pt-4 border-t-2 border-gray-100"
          >
            <button
              @click="$emit('reject', expense)"
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
              @click="$emit('approve', expense)"
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
interface Expense {
  id: number;
  employeeName: string;
  initials: string;
  avatarColor: string;
  categorie: string;
  date: string;
  montant: number;
  statut: string;
  statusClass: string;
  description?: string;
  justificatif?: string;
}

defineProps<{
  show: boolean;
  expense: Expense | null;
}>();

defineEmits(["close", "approve", "reject"]);

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", {
      minimumFractionDigits: 0,
    }).format(montant) + " FCFA"
  );
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString("fr-FR");
};

const getCategoryClass = (categorie: string) => {
  const map: Record<string, string> = {
    Transport: "bg-blue-100 text-blue-700",
    Hébergement: "bg-purple-100 text-purple-700",
    Restauration: "bg-orange-100 text-orange-700",
    Fournitures: "bg-green-100 text-green-700",
    Autre: "bg-gray-100 text-gray-700",
  };
  return map[categorie] || "bg-gray-100 text-gray-700";
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