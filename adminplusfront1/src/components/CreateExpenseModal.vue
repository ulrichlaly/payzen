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
                <h3 class="text-xl font-bold">Nouvelle note de frais</h3>
                <p class="text-sm text-blue-100">
                  Soumettez une demande de remboursement
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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Catégorie <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.categorie"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              >
                <option value="">Sélectionner...</option>
                <option value="Transport">Transport</option>
                <option value="Hébergement">Hébergement</option>
                <option value="Restauration">Restauration</option>
                <option value="Fournitures">Fournitures de bureau</option>
                <option value="Autre">Autre</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Date <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.date"
                type="date"
                required
                :max="today"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Montant (FCFA) <span class="text-red-500">*</span>
            </label>
            <input
              v-model.number="formData.montant"
              type="number"
              required
              min="100"
              step="100"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              placeholder="15000"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="formData.description"
              rows="4"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
              placeholder="Décrivez la dépense (ex: Taxi pour rendez-vous client...)"
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Justificatif
              <span class="text-gray-500 text-xs"
                >(Facultatif mais recommandé)</span
              >
            </label>
            <div
              class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors"
            >
              <input
                type="file"
                @change="handleFileChange"
                accept="image/*,.pdf"
                class="hidden"
                ref="fileInput"
              />
              <button
                type="button"
                @click="$refs.fileInput.click()"
                class="text-blue-600 hover:text-blue-700 font-medium flex items-center gap-2 mx-auto"
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
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                  />
                </svg>
                Cliquez pour choisir un fichier
              </button>
              <p class="text-xs text-gray-500 mt-2">Image ou PDF (Max 5 MB)</p>
              <p
                v-if="fileName"
                class="text-sm text-green-600 font-medium mt-3"
              >
                {{ fileName }}
              </p>
            </div>
          </div>

          <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex items-start gap-3">
              <svg
                class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Conseils</p>
                <ul class="list-disc list-inside space-y-1">
                  <li>Joignez toujours un justificatif (reçu, facture...)</li>
                  <li>Soyez précis dans la description</li>
                  <li>Votre demande sera examinée sous 48h</li>
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
              class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
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
              <span>{{ loading ? "Soumission..." : "Soumettre" }}</span>
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
const fileName = ref("");
const fileInput = ref<HTMLInputElement | null>(null);

const formData = ref({
  categorie: "",
  date: "",
  montant: 0,
  description: "",
  justificatif: null as File | null,
});

const today = new Date().toISOString().split("T")[0];

const isFormValid = computed(() => {
  return (
    formData.value.categorie &&
    formData.value.date &&
    formData.value.montant > 0 &&
    formData.value.description.trim().length >= 10
  );
});

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (file) {
    if (file.size > 5 * 1024 * 1024) {
      error.value = "Le fichier ne doit pas dépasser 5 MB";
      return;
    }
    formData.value.justificatif = file;
    fileName.value = file.name;
    error.value = "";
  }
};

const handleSubmit = () => {
  error.value = "";

  if (!isFormValid.value) {
    error.value = "Veuillez remplir tous les champs obligatoires";
    return;
  }

  if (formData.value.description.trim().length < 10) {
    error.value = "La description doit contenir au moins 10 caractères";
    return;
  }

  loading.value = true;

  const submitData = new FormData();
  submitData.append("categorie", formData.value.categorie);
  submitData.append("date", formData.value.date);
  submitData.append("montant", formData.value.montant.toString());
  submitData.append("description", formData.value.description);

  if (formData.value.justificatif) {
    submitData.append("justificatif", formData.value.justificatif);
  }

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
