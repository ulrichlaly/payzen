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
        class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto"
      >
        <!-- Header -->
        <div
          class="sticky top-0 bg-gradient-to-r from-green-600 to-green-700 px-6 py-4 rounded-t-2xl z-10"
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
                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                />
              </svg>
              <div>
                <h3 class="text-xl font-bold">Calculateur de Paie - Bénin</h3>
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
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-1">
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Collaborateur <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.collaborator_id"
                @change="onEmployeeChange"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
              >
                <option value="">Sélectionner...</option>
                <option v-for="emp in employees" :key="emp.id" :value="emp.id">
                  {{ emp.fullname }} ({{ emp.matricule }})
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Mois <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.mois"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
              >
                <option value="">Sélectionner...</option>
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
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Année <span class="text-red-500">*</span>
              </label>
              <select
                v-model="formData.annee"
                required
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none"
              >
                <option value="">Sélectionner...</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
              </select>
            </div>
          </div>

          <!-- Éléments de rémunération -->
          <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
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
                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              Éléments de rémunération
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Salaire de base (FCFA) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model.number="formData.salaire_base"
                  type="number"
                  required
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="450000"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Prime (FCFA)
                </label>
                <input
                  v-model.number="formData.prime"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="0"
                />
              </div>

              <div v-if="formData.prime > 0" class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Motif de la prime
                </label>
                <input
                  v-model="formData.prime_motif"
                  type="text"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none bg-white"
                  placeholder="Ex: Prime de performance, Prime exceptionnelle..."
                />
              </div>
            </div>
          </div>

          <!-- Retenues supplémentaires -->
          <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
              <svg
                class="w-5 h-5 text-orange-600"
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
              Retenues supplémentaires
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Avances (FCFA)
                </label>
                <input
                  v-model.number="formData.avance"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none bg-white"
                  placeholder="0"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Sanctions (FCFA)
                </label>
                <input
                  v-model.number="formData.sanction"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none bg-white"
                  placeholder="0"
                />
              </div>

              <div v-if="formData.sanction > 0" class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Motif de la sanction
                </label>
                <input
                  v-model="formData.sanction_motif"
                  type="text"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none bg-white"
                  placeholder="Ex: Retard, absence non justifiée..."
                />
              </div>
            </div>
          </div>

          <!-- Récapitulatif des calculs automatiques -->
          <div
            class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-200 rounded-xl p-6"
          >
            <h4 class="font-bold text-purple-900 mb-4 flex items-center gap-2">
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
                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                />
              </svg>
              Récapitulatif détaillé (Calcul automatique - Bénin)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Gauche - Brut et charges employeur -->
              <div class="space-y-3">
                <div class="bg-white rounded-lg p-4 border-2 border-green-200">
                  <p class="text-xs text-gray-600 mb-1">Salaire brut</p>
                  <p class="text-2xl font-bold text-green-700">
                    {{ formatMontant(calculs.salaireBrut) }} FCFA
                  </p>
                </div>

                <div class="bg-white rounded-lg p-3">
                  <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-gray-700"
                      >CNSS Employeur (16.4%)</span
                    >
                    <span class="text-sm font-bold text-blue-700"
                      >{{ formatMontant(calculs.cnssEmployeur) }} FCFA</span
                    >
                  </div>
                  <p class="text-xs text-gray-500">À charge de l'employeur</p>
                </div>

                <div
                  class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg p-4"
                >
                  <p class="text-xs opacity-80 mb-1">Coût total employeur</p>
                  <p class="text-2xl font-bold">
                    {{ formatMontant(calculs.coutTotal) }} FCFA
                  </p>
                </div>
              </div>

              <!-- Droite - Retenues -->
              <div class="space-y-3">
                <div class="bg-white rounded-lg p-3 border-l-4 border-red-500">
                  <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-700"
                      >CNSS Salarié (3.6%)</span
                    >
                    <span class="text-sm font-bold text-red-600"
                      >-{{ formatMontant(calculs.cnssSalarie) }}</span
                    >
                  </div>
                </div>

                <div
                  class="bg-white rounded-lg p-3 border-l-4 border-orange-500"
                >
                  <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-semibold text-gray-700"
                      >IR (Barème progressif)</span
                    >
                    <span class="text-sm font-bold text-orange-600"
                      >-{{ formatMontant(calculs.ir) }}</span
                    >
                  </div>
                  <p class="text-xs text-gray-500">
                    Sur {{ formatMontant(calculs.salaireImposable) }} FCFA
                  </p>
                </div>

                <div
                  class="bg-white rounded-lg p-3 border-l-4 border-purple-500"
                >
                  <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-700"
                      >PPN (2.5%)</span
                    >
                    <span class="text-sm font-bold text-purple-600"
                      >-{{ formatMontant(calculs.ppn) }}</span
                    >
                  </div>
                </div>

                <div
                  v-if="calculs.avance > 0 || calculs.sanction > 0"
                  class="bg-white rounded-lg p-3 border-l-4 border-gray-500"
                >
                  <div class="space-y-1">
                    <div
                      v-if="calculs.avance > 0"
                      class="flex justify-between items-center text-xs"
                    >
                      <span class="text-gray-600">Avances</span>
                      <span class="font-bold text-gray-700"
                        >-{{ formatMontant(calculs.avance) }}</span
                      >
                    </div>
                    <div
                      v-if="calculs.sanction > 0"
                      class="flex justify-between items-center text-xs"
                    >
                      <span class="text-gray-600">Sanctions</span>
                      <span class="font-bold text-gray-700"
                        >-{{ formatMontant(calculs.sanction) }}</span
                      >
                    </div>
                  </div>
                </div>

                <div
                  class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg p-4"
                >
                  <p class="text-xs opacity-80 mb-1">Net à payer</p>
                  <p class="text-3xl font-black">
                    {{ formatMontant(calculs.netAPayer) }} FCFA
                  </p>
                </div>
              </div>
            </div>

            <!-- Résumé des déclarations -->
            <div
              class="mt-4 p-4 bg-indigo-100 rounded-lg border-2 border-indigo-300"
            >
              <p class="text-sm font-bold text-indigo-900 mb-2">
                Déclarations automatiques à générer :
              </p>
              <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="bg-white p-2 rounded">
                  <p class="text-gray-600">CNSS Total</p>
                  <p class="font-bold text-blue-700">
                    {{ formatMontant(calculs.cnssTotal) }} FCFA
                  </p>
                </div>
                <div class="bg-white p-2 rounded">
                  <p class="text-gray-600">IR + PPN</p>
                  <p class="font-bold text-green-700">
                    {{ formatMontant(calculs.ir + calculs.ppn) }} FCFA
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Commentaire -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Commentaire
            </label>
            <textarea
              v-model="formData.commentaire"
              rows="3"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none resize-none"
              placeholder="Ajoutez un commentaire optionnel..."
            ></textarea>
          </div>

          <!-- Avertissement -->
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
                <p>
                  Cette paie générera automatiquement les écritures pour les
                  déclarations CNSS et fiscales (IR + PPN) du mois concerné.
                </p>
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
              class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg font-semibold hover:from-green-700 hover:to-green-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2"
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
              <span v-if="loading">Génération en cours...</span>
              <span v-else>Générer la paie et les déclarations</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import api from "../services/api/axios";

interface Employee {
  id: number;
  fullname: string;
  matricule: string;
  salaire_base: number;
}

const props = defineProps<{
  show: boolean;
}>();

const emit = defineEmits(["close", "submit"]);

// Taux de cotisations Bénin 2025
const TAUX = {
  CNSS_EMPLOYEUR: 0.164,
  CNSS_SALARIE: 0.036,
  PPN: 0.025,
};

// Barème IR Bénin 2025
const BAREME_IR = [
  { min: 0, max: 50000, taux: 0 },
  { min: 50001, max: 130000, taux: 0.1 },
  { min: 130001, max: 280000, taux: 0.15 },
  { min: 280001, max: 530000, taux: 0.2 },
  { min: 530001, max: 1000000, taux: 0.25 },
  { min: 1000001, max: 1500000, taux: 0.3 },
  { min: 1500001, max: Infinity, taux: 0.35 },
];

const loading = ref(false);
const error = ref("");
const employees = ref<Employee[]>([]);

const formData = ref({
  collaborator_id: "",
  mois: "",
  annee: new Date().getFullYear().toString(),
  salaire_base: 0,
  prime: 0,
  prime_motif: "",
  avance: 0,
  sanction: 0,
  sanction_motif: "",
  commentaire: "",
});

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
      salaire_base:
        parseFloat(String(c.salaire_base || "0").replace(/[^\d]/g, "")) || 0,
    }));
  } catch (error) {
    console.error("Erreur chargement employés:", error);
  }
};

const onEmployeeChange = () => {
  const employee = employees.value.find(
    (e) => e.id === Number(formData.value.collaborator_id)
  );
  if (employee) {
    formData.value.salaire_base = employee.salaire_base;
  }
};

// Fonction de calcul IR selon barème progressif
const calculerIR = (salaireImposable: number): number => {
  let ir = 0;
  let resteAImposer = salaireImposable;

  for (const tranche of BAREME_IR) {
    if (resteAImposer <= 0) break;

    const montantTranche = Math.min(
      resteAImposer,
      tranche.max - tranche.min + 1
    );

    ir += montantTranche * tranche.taux;
    resteAImposer -= montantTranche;
  }

  return Math.round(ir);
};

// Calculs automatiques
const calculs = computed(() => {
  const salaireBase = formData.value.salaire_base || 0;
  const prime = formData.value.prime || 0;
  const avance = formData.value.avance || 0;
  const sanction = formData.value.sanction || 0;

  // Salaire brut
  const salaireBrut = salaireBase + prime;

  // Cotisations CNSS
  const cnssEmployeur = Math.round(salaireBrut * TAUX.CNSS_EMPLOYEUR);
  const cnssSalarie = Math.round(salaireBrut * TAUX.CNSS_SALARIE);
  const cnssTotal = cnssEmployeur + cnssSalarie;

  // Salaire imposable (brut - cotisation salarié)
  const salaireImposable = salaireBrut - cnssSalarie;

  // IR selon barème progressif
  const ir = calculerIR(salaireImposable);

  // Contribution PPN
  const ppn = Math.round(salaireBrut * TAUX.PPN);

  // Total des retenues
  const totalRetenues = cnssSalarie + ir + ppn + avance + sanction;

  // Net à payer
  const netAPayer = salaireBrut - totalRetenues;

  // Coût total employeur
  const coutTotal = salaireBrut + cnssEmployeur;

  return {
    salaireBrut,
    cnssEmployeur,
    cnssSalarie,
    cnssTotal,
    salaireImposable,
    ir,
    ppn,
    avance,
    sanction,
    totalRetenues,
    netAPayer,
    coutTotal,
  };
});

const isFormValid = computed(() => {
  return (
    formData.value.collaborator_id &&
    formData.value.mois &&
    formData.value.annee &&
    formData.value.salaire_base > 0
  );
});

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat("fr-FR", {
    minimumFractionDigits: 0,
  }).format(montant);
};

const handleSubmit = () => {
  error.value = "";

  if (!isFormValid.value) {
    error.value = "Veuillez remplir tous les champs obligatoires";
    return;
  }

  loading.value = true;

  // Données à envoyer au backend
  const submitData = {
    collaborator_id: Number(formData.value.collaborator_id),
    mois: formData.value.mois,
    annee: Number(formData.value.annee),
    salaire_base: formData.value.salaire_base,
    prime: formData.value.prime || 0,
    prime_motif: formData.value.prime_motif || "",
    avance: formData.value.avance || 0,
    sanction: formData.value.sanction || 0,
    sanction_motif: formData.value.sanction_motif || "",
    commentaire: formData.value.commentaire,
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
