<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Notes de frais</h2>
        <p class="text-sm text-gray-500 mt-1">
          Gestion des demandes de remboursement
        </p>
      </div>
      <button
        @click="exportExpenses"
        class="btn-secondary flex items-center gap-2"
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
        Exporter
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total demandes</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ stats.total }}</h3>
          </div>
          <div
            class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center"
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
                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
            <h3 class="text-3xl font-bold text-orange-600">
              {{ stats.enAttente }}
            </h3>
          </div>
          <div
            class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center"
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
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Approuvées</p>
            <h3 class="text-3xl font-bold text-green-600">
              {{ stats.approuvees }}
            </h3>
          </div>
          <div
            class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center"
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
                d="M5 13l4 4L19 7"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Montant total</p>
            <h3 class="text-2xl font-bold text-gray-900">
              {{ formatMontantShort(stats.montantTotal) }}
            </h3>
          </div>
          <div
            class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center"
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
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
      <div class="flex items-center gap-4">
        <select
          v-model="filters.statut"
          @change="loadExpenses"
          class="input-field w-auto"
        >
          <option value="">Tous les statuts</option>
          <option value="En attente">En attente</option>
          <option value="Approuvée">Approuvée</option>
          <option value="Rejetée">Rejetée</option>
          <option value="Remboursée">Remboursée</option>
        </select>

        <select
          v-model="filters.categorie"
          @change="loadExpenses"
          class="input-field w-auto"
        >
          <option value="">Toutes les catégories</option>
          <option value="Transport">Transport</option>
          <option value="Hébergement">Hébergement</option>
          <option value="Restauration">Restauration</option>
          <option value="Fournitures">Fournitures</option>
          <option value="Autre">Autre</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-6">
        Demandes de remboursement
      </h3>

      <div v-if="loading" class="py-12 flex justify-center">
        <div class="text-center">
          <svg
            class="animate-spin h-12 w-12 text-primary-600 mx-auto mb-4"
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
          <p class="text-gray-600">Chargement...</p>
        </div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Collaborateur
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Catégorie
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Date
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Montant
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Statut
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="expenses.length === 0">
              <td colspan="6" class="py-12 text-center text-gray-500">
                <svg
                  class="w-16 h-16 text-gray-300 mx-auto mb-4"
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
                <p class="text-lg font-semibold text-gray-700">
                  Aucune note de frais
                </p>
              </td>
            </tr>
            <tr v-for="expense in expenses" :key="expense.id" class="table-row">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div
                    :class="expense.avatarColor"
                    class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold"
                  >
                    {{ expense.initials }}
                  </div>
                  <p class="text-sm font-semibold text-gray-900">
                    {{ expense.employeeName }}
                  </p>
                </div>
              </td>
              <td class="py-4 px-6">
                <span
                  :class="getCategoryClass(expense.categorie)"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ expense.categorie }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm text-gray-900">
                {{ formatDate(expense.date) }}
              </td>
              <td class="py-4 px-6 text-sm font-bold text-gray-900">
                {{ formatMontant(expense.montant) }}
              </td>
              <td class="py-4 px-6">
                <span
                  :class="expense.statusClass"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ expense.statut }}
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button
                    @click="viewExpense(expense)"
                    class="p-2 hover:bg-blue-50 rounded-lg transition-colors group"
                    title="Voir les détails"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 group-hover:text-blue-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                      />
                    </svg>
                  </button>
                  <button
                    v-if="expense.statut === 'En attente'"
                    @click="approveExpense(expense)"
                    class="p-2 hover:bg-green-50 rounded-lg transition-colors group"
                    title="Approuver"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 group-hover:text-green-600"
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
                  </button>
                  <button
                    v-if="expense.statut === 'En attente'"
                    @click="rejectExpense(expense)"
                    class="p-2 hover:bg-red-50 rounded-lg transition-colors group"
                    title="Rejeter"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 group-hover:text-red-600"
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
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal détails -->
    <ExpenseDetailModal
      v-if="showDetailModal"
      :show="showDetailModal"
      :expense="selectedExpense"
      @close="showDetailModal = false"
      @approve="approveExpense"
      @reject="rejectExpense"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import ExpenseDetailModal from "./ExpenseDetailModal.vue";
import api from "../services/api/axios";
import Swal from "sweetalert2";

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

const expenses = ref<Expense[]>([]);
const loading = ref(false);
const successMessage = ref("");
const showDetailModal = ref(false);
const selectedExpense = ref<Expense | null>(null);

const filters = ref({
  statut: "",
  categorie: "",
});

const stats = computed(() => {
  const total = expenses.value.length;
  const enAttente = expenses.value.filter(
    (e) => e.statut === "En attente"
  ).length;
  const approuvees = expenses.value.filter(
    (e) => e.statut === "Approuvée"
  ).length;
  const montantTotal = expenses.value.reduce((sum, e) => sum + e.montant, 0);

  return { total, enAttente, approuvees, montantTotal };
});

onMounted(() => {
  loadExpenses();
});

const loadExpenses = async () => {
  loading.value = true;
  try {
    const response = await api.get("/expenses", { params: filters.value });
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    expenses.value = data.map((e: any) => ({
      id: e.id,
      employeeName: e.collaborator?.nom_complet || "N/A",
      initials: getInitials(e.collaborator?.nom_complet || "N/A"),
      avatarColor: getRandomColor(),
      categorie: e.categorie || "Autre",
      date: e.date,
      montant: parseFloat(e.montant || 0),
      statut: e.statut || "En attente",
      statusClass: getStatusClass(e.statut),
      description: e.description,
      justificatif: e.justificatif,
    }));
  } catch (error) {
    console.error("Erreur chargement notes de frais:", error);
    Swal.fire({
      icon: "error",
      title: "Erreur de chargement",
      text: "Impossible de charger les notes de frais",
      confirmButtonColor: "#3b82f6",
    });
  } finally {
    loading.value = false;
  }
};

const viewExpense = (expense: Expense) => {
  selectedExpense.value = expense;
  showDetailModal.value = true;
};

const approveExpense = async (expense: Expense) => {
  const result = await Swal.fire({
    title: "Approuver cette demande ?",
    html: `
      <p class="text-gray-700 mb-2">Collaborateur : <strong>${
        expense.employeeName
      }</strong></p>
      <p class="text-gray-700 mb-2">Montant : <strong>${formatMontant(
        expense.montant
      )}</strong></p>
    `,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#10b981",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Oui, approuver",
    cancelButtonText: "Annuler",
  });

  if (result.isConfirmed) {
    try {
      await api.patch(`/expenses/${expense.id}`, { statut: "Approuvée" });

      await Swal.fire({
        icon: "success",
        title: "Approuvée !",
        text: "La note de frais a été approuvée avec succès",
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadExpenses();
      showDetailModal.value = false;
    } catch (error) {
      console.error("Erreur:", error);
      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible d'approuver la note de frais",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const rejectExpense = async (expense: Expense) => {
  const { value: motif } = await Swal.fire({
    title: "Rejeter cette demande",
    html: `
      <p class="text-gray-700 mb-4">Collaborateur : <strong>${expense.employeeName}</strong></p>
    `,
    input: "textarea",
    inputLabel: "Motif du rejet",
    inputPlaceholder: "Expliquez la raison du rejet...",
    inputAttributes: {
      "aria-label": "Motif du rejet",
    },
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Rejeter",
    cancelButtonText: "Annuler",
    inputValidator: (value) => {
      if (!value) {
        return "Veuillez indiquer un motif de rejet";
      }
    },
  });

  if (motif) {
    try {
      await api.patch(`/expenses/${expense.id}`, {
        statut: "Rejetée",
        motif_rejet: motif,
      });

      await Swal.fire({
        icon: "success",
        title: "Rejetée",
        text: "La note de frais a été rejetée",
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadExpenses();
      showDetailModal.value = false;
    } catch (error) {
      console.error("Erreur:", error);
      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible de rejeter la note de frais",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const exportExpenses = async () => {
  await Swal.fire({
    icon: "info",
    title: "Export en cours...",
    text: "Fonctionnalité à venir",
    confirmButtonColor: "#3b82f6",
    timer: 2000,
  });
};

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", {
      minimumFractionDigits: 0,
    }).format(montant) + " FCFA"
  );
};

const formatMontantShort = (montant: number) => {
  if (montant >= 1000000) return (montant / 1000000).toFixed(1) + "M";
  return formatMontant(montant);
};

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString("fr-FR");
};

const getInitials = (name: string) => {
  if (!name || name === "N/A") return "NA";
  const parts = name.split(" ");
  return parts
    .map((p) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
};

const getRandomColor = () => {
  const colors = [
    "bg-blue-500",
    "bg-green-500",
    "bg-purple-500",
    "bg-orange-500",
    "bg-red-500",
    "bg-teal-500",
  ];
  return colors[Math.floor(Math.random() * colors.length)];
};

const getStatusClass = (statut: string) => {
  const map: Record<string, string> = {
    "En attente": "bg-orange-100 text-orange-700",
    Approuvée: "bg-green-100 text-green-700",
    Rejetée: "bg-red-100 text-red-700",
    Remboursée: "bg-blue-100 text-blue-700",
  };
  return map[statut] || "bg-gray-100 text-gray-700";
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
