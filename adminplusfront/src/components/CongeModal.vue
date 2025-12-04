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
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
              <div>
                <h3 class="text-xl font-bold">Nouvelle demande de congé</h3>
                <p class="text-sm text-blue-100">
                  Créer une demande pour un employé
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
          <!-- Sélection employé -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Employé <span class="text-red-500">*</span>
            </label>
            <select
              v-model="formData.collaborator_id"
              required
              @change="loadEmployeeSolde"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
              <option value="">Sélectionner un employé...</option>
              <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                {{ emp.fullname }} ({{ emp.matricule }})
              </option>
            </select>
          </div>

          <!-- 🔥 AFFICHAGE DU SOLDE DE CONGÉS -->
          <div
            v-if="selectedEmployeeSolde !== null"
            class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg p-4"
          >
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div
                  class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"
                >
                  <svg
                    class="w-6 h-6 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-700">
                    Solde disponible
                  </p>
                  <p class="text-xs text-gray-500 mt-0.5">
                    {{ soldeInfo.jours_alloues }} jours alloués -
                    {{ soldeInfo.jours_pris }} jours pris
                  </p>
                </div>
              </div>
              <div class="text-right">
                <p
                  class="text-3xl font-bold"
                  :class="
                    selectedEmployeeSolde > 0
                      ? 'text-green-600'
                      : 'text-red-600'
                  "
                >
                  {{ selectedEmployeeSolde }}
                </p>
                <p class="text-xs text-gray-600">jour(s) restant(s)</p>
              </div>
            </div>
          </div>

          <!-- Type de congé -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Type de congé <span class="text-red-500">*</span>
            </label>
            <select
              v-model="formData.type"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
              <option value="">Sélectionner un type...</option>
              <option value="annuel">Congé annuel</option>
              <option value="maladie">Congé maladie</option>
              <option value="maternite">Congé maternité</option>
              <option value="autre">Autre</option>
            </select>
          </div>

          <!-- Dates -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Date de début <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.date_debut"
                type="date"
                required
                @change="calculateDays"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Date de fin <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.date_fin"
                type="date"
                required
                @change="calculateDays"
                :min="formData.date_debut"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              />
            </div>
          </div>

          <!-- Nombre de jours (calculé automatiquement) -->
          <div
            class="border-2 rounded-lg p-4"
            :class="
              isNbJoursExceeded
                ? 'bg-red-50 border-red-300'
                : 'bg-blue-50 border-blue-200'
            "
          >
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-semibold text-gray-700">
                  Durée calculée
                </p>
                <p class="text-xs text-gray-500 mt-1">
                  Basée sur les dates sélectionnées
                </p>
                <!-- 🔥 Avertissement si dépassement -->
                <p
                  v-if="isNbJoursExceeded"
                  class="text-xs text-red-600 font-semibold mt-2 flex items-center gap-1"
                >
                  <svg
                    class="w-4 h-4"
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
                  Dépasse le solde disponible !
                </p>
              </div>
              <div class="text-right">
                <p
                  class="text-3xl font-bold"
                  :class="isNbJoursExceeded ? 'text-red-600' : 'text-blue-600'"
                >
                  {{ formData.nb_jours }}
                </p>
                <p class="text-xs text-gray-600">jour(s)</p>
              </div>
            </div>
          </div>

          <!-- Motif -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Motif
            </label>
            <textarea
              v-model="formData.motif"
              rows="4"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
              placeholder="Décrivez la raison de la demande de congé..."
            ></textarea>
          </div>

          <!-- Info -->
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
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div class="text-sm text-yellow-800">
                <p class="font-semibold mb-1">Information</p>
                <p>
                  La demande sera créée avec le statut "En attente" et devra
                  être approuvée par un responsable.
                </p>
              </div>
            </div>
          </div>

          <!-- Erreur -->
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
              :disabled="loading || !isFormValid || isNbJoursExceeded"
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
              <span>{{ loading ? "Création..." : "Créer la demande" }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import api from "../services/api/axios";

interface Employee {
  id: number;
  fullname: string;
  matricule: string;
}

const props = defineProps<{
  show: boolean;
}>();

const emit = defineEmits(["close", "submit"]);

const loading = ref(false);
const error = ref("");
const employees = ref<Employee[]>([]);

// 🔥 NOUVEAUX REFS POUR LE SOLDE
const selectedEmployeeSolde = ref<number | null>(null);
const soldeInfo = ref({
  jours_alloues: 0,
  jours_pris: 0,
  jours_restant: 0,
});

const formData = ref({
  collaborator_id: "",
  type: "",
  date_debut: "",
  date_fin: "",
  nb_jours: 0,
  motif: "",
});

// 🔥 CALCULER SI LE NOMBRE DE JOURS DÉPASSE LE SOLDE
const isNbJoursExceeded = computed(() => {
  if (selectedEmployeeSolde.value === null || formData.value.nb_jours === 0) {
    return false;
  }
  return formData.value.nb_jours > selectedEmployeeSolde.value;
});

// 🔥 CHARGER LE SOLDE DE L'EMPLOYÉ SÉLECTIONNÉ
const loadEmployeeSolde = async () => {
  if (!formData.value.collaborator_id) {
    selectedEmployeeSolde.value = null;
    return;
  }

  try {
    const response = await api.get(
      `/conges/historique/${formData.value.collaborator_id}`
    );

    selectedEmployeeSolde.value = response.data.jours_restant || 0;
    soldeInfo.value = {
      jours_alloues: response.data.jours_alloues || 0,
      jours_pris: response.data.jours_pris || 0,
      jours_restant: response.data.jours_restant || 0,
    };
  } catch (err) {
    console.error("Erreur chargement solde:", err);
    selectedEmployeeSolde.value = 0;
  }
};

// 🔥 RÉINITIALISER LE SOLDE QUAND ON OUVRE/FERME LE MODAL
watch(
  () => props.show,
  (newVal) => {
    if (!newVal) {
      // Réinitialiser le formulaire
      formData.value = {
        collaborator_id: "",
        type: "",
        date_debut: "",
        date_fin: "",
        nb_jours: 0,
        motif: "",
      };
      selectedEmployeeSolde.value = null;
      error.value = "";
    }
  }
);

onMounted(async () => {
  await loadEmployees();
});

const loadEmployees = async () => {
  try {
    const response = await api.get("/collaborators");
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];
    employees.value = data.map((c: any) => ({
      id: c.id,
      fullname: c.nom_complet || c.fullname || "N/A",
      matricule: c.matricule || "N/A",
    }));
  } catch (error) {
    console.error("Erreur chargement employés:", error);
  }
};

const calculateDays = () => {
  if (formData.value.date_debut && formData.value.date_fin) {
    const start = new Date(formData.value.date_debut);
    const end = new Date(formData.value.date_fin);
    const diffTime = Math.abs(end.getTime() - start.getTime());
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
    formData.value.nb_jours = diffDays;
  }
};

const isFormValid = computed(() => {
  return (
    formData.value.collaborator_id &&
    formData.value.type &&
    formData.value.date_debut &&
    formData.value.date_fin &&
    formData.value.nb_jours > 0
  );
});

const handleSubmit = async () => {
  error.value = "";

  if (!isFormValid.value) {
    error.value = "Veuillez remplir tous les champs obligatoires";
    return;
  }

  if (new Date(formData.value.date_fin) < new Date(formData.value.date_debut)) {
    error.value = "La date de fin doit être après la date de début";
    return;
  }

  // 🔥 VÉRIFICATION CLIENT-SIDE DU SOLDE
  if (isNbJoursExceeded.value) {
    error.value = `Le nombre de jours demandés (${formData.value.nb_jours}) dépasse le solde disponible (${selectedEmployeeSolde.value} jours)`;
    return;
  }

  loading.value = true;

  try {
    const submitData = {
      collaborator_id: Number(formData.value.collaborator_id),
      type: formData.value.type,
      date_debut: formData.value.date_debut,
      date_fin: formData.value.date_fin,
      nb_jours: formData.value.nb_jours,
      motif: formData.value.motif,
      statut: "en_attente",
    };

    // 🔥 GÉRER LES ERREURS 422 DU BACKEND
    emit("submit", submitData);
  } catch (err: any) {
    // Gérer l'erreur 422 du backend
    if (err.response?.status === 422) {
      const data = err.response.data;
      error.value = data.message || "Solde de congés insuffisant";

      // Mettre à jour l'affichage du solde
      if (data.solde_disponible !== undefined) {
        selectedEmployeeSolde.value = data.solde_disponible;
      }
    } else {
      error.value = "Erreur lors de la création de la demande";
    }
  } finally {
    loading.value = false;
  }
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
