<template>
  <Transition name="modal">
    <div
      v-if="show"
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
                <h3 class="text-xl font-bold">Nouvelle demande</h3>
                <p class="text-sm text-purple-100">
                  Prêt ou avance sur salaire
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
        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Type de demande <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-4">
              <button
                type="button"
                @click="formData.type = 'Prêt'"
                :class="
                  formData.type === 'Prêt'
                    ? 'border-purple-500 bg-purple-50'
                    : 'border-gray-300'
                "
                class="border-2 rounded-xl p-4 text-left hover:border-purple-400 transition-colors"
              >
                <div class="flex items-center gap-3">
                  <div
                    :class="
                      formData.type === 'Prêt' ? 'bg-purple-500' : 'bg-gray-300'
                    "
                    class="w-10 h-10 rounded-full flex items-center justify-center"
                  >
                    <svg
                      class="w-6 h-6 text-white"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                      />
                    </svg>
                  </div>
                  <div>
                    <p class="font-bold text-gray-900">Prêt</p>
                    <p class="text-xs text-gray-600">
                      Remboursement sur plusieurs mois
                    </p>
                  </div>
                </div>
              </button>

              <button
                type="button"
                @click="formData.type = 'Avance'"
                :class="
                  formData.type === 'Avance'
                    ? 'border-blue-500 bg-blue-50'
                    : 'border-gray-300'
                "
                class="border-2 rounded-xl p-4 text-left hover:border-blue-400 transition-colors"
              >
                <div class="flex items-center gap-3">
                  <div
                    :class="
                      formData.type === 'Avance' ? 'bg-blue-500' : 'bg-gray-300'
                    "
                    class="w-10 h-10 rounded-full flex items-center justify-center"
                  >
                    <svg
                      class="w-6 h-6 text-white"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                      />
                    </svg>
                  </div>
                  <div>
                    <p class="font-bold text-gray-900">Avance sur salaire</p>
                    <p class="text-xs text-gray-600">
                      Déduit du prochain salaire
                    </p>
                  </div>
                </div>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Montant demandé (FCFA) <span class="text-red-500">*</span>
              </label>
              <input
                v-model.number="formData.montant"
                type="number"
                required
                min="10000"
                step="5000"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
                placeholder="100000"
              />
              <p class="text-xs text-gray-500 mt-1">Minimum: 10 000 FCFA</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Durée de remboursement <span class="text-red-500">*</span>
              </label>
              <select
                v-model.number="formData.duree"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none"
              >
                <option value="">Sélectionner...</option>
                <option value="1">1 mois</option>
                <option value="2">2 mois</option>
                <option value="3">3 mois</option>
                <option value="6">6 mois</option>
                <option value="12">12 mois</option>
                <option value="24">24 mois</option>
              </select>
            </div>
          </div>

          <!-- Calcul mensualité -->
          <div
            v-if="formData.montant > 0 && formData.duree > 0"
            class="bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-300 rounded-xl p-5"
          >
            <p class="text-sm font-medium text-gray-600 mb-2">
              Mensualité estimée
            </p>
            <p class="text-3xl font-bold text-purple-700">
              {{ formatMontant(formData.montant / formData.duree) }}
            </p>
            <p class="text-xs text-gray-600 mt-2">
              Cette somme sera déduite automatiquement de votre salaire chaque
              mois
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Motif de la demande <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="formData.motif"
              rows="4"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none resize-none"
              placeholder="Expliquez la raison de votre demande (ex: frais médicaux, scolarité des enfants...)"
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">
              Minimum 20 caractères - Soyez précis pour faciliter le traitement
            </p>
          </div>

          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex items-start gap-3">
              <svg
                class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                />
              </svg>
              <div class="text-sm text-yellow-800">
                <p class="font-semibold mb-1">Important</p>
                <ul class="list-disc list-inside space-y-1">
                  <li>Les mensualités seront déduites automatiquement</li>
                  <li>Votre demande sera examinée sous 48-72h</li>
                  <li>
                    Assurez-vous d'avoir un salaire suffisant pour le
                    remboursement
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div
            v-if="error"
            class="bg-red-50 border-2 border-red-200 rounded-lg p-3 flex items-start gap-2"
          >
            <svg
              class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
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
              <svg
                v-if="loading"
                class="animate-spin h-5 w-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  class="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  stroke-width="4"
                ></circle>
                <path
                  class="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
              </svg>
              <span>{{
                loading ? "Soumission..." : "Soumettre la demande"
              }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";

defineProps<{
  show: boolean;
}>();

const emit = defineEmits(["close", "submit"]);

const loading = ref(false);
const error = ref("");

const formData = ref({
  type: "Prêt",
  montant: 0,
  duree: 0,
  motif: "",
});

const isFormValid = computed(() => {
  return (
    formData.value.type &&
    formData.value.montant >= 10000 &&
    formData.value.duree > 0 &&
    formData.value.motif.trim().length >= 20
  );
});

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", {
      minimumFractionDigits: 0,
    }).format(montant) + " FCFA"
  );
};

const handleSubmit = () => {
  error.value = "";

  if (!isFormValid.value) {
    error.value = "Veuillez remplir tous les champs obligatoires";
    return;
  }

  if (formData.value.motif.trim().length < 20) {
    error.value = "Le motif doit contenir au moins 20 caractères";
    return;
  }

  if (formData.value.montant < 10000) {
    error.value = "Le montant minimum est de 10 000 FCFA";
    return;
  }

  loading.value = true;

  const submitData = {
    type: formData.value.type,
    montant: formData.value.montant,
    duree: formData.value.duree,
    motif: formData.value.motif,
  };

  emit("submit", submitData);

  setTimeout(() => {
    loading.value = false;
  }, 2000);
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
