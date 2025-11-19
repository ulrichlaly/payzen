<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Mes notes de frais</h2>
        <p class="text-sm text-gray-500 mt-1">
          Soumettez vos demandes de remboursement
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="btn-primary flex items-center gap-2"
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
            d="M12 4v16m8-8H4"
          />
        </svg>
        Nouvelle demande
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total demandes</p>
            <h3 class="text-3xl font-bold text-gray-900">
              {{ stats.total }}
            </h3>
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
            <p class="text-sm font-medium text-gray-600 mb-1">Montant total</p>
            <h3 class="text-2xl font-bold text-green-700">
              {{ formatMontantShort(stats.montantTotal) }}
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
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Liste des demandes -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-6">Mes demandes</h3>

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

      <div v-else class="space-y-4">
        <div
          v-if="expenses.length === 0"
          class="py-12 text-center text-gray-500"
        >
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
          <p class="text-sm text-gray-500 mt-2">
            Cliquez sur "Nouvelle demande" pour créer une note de frais
          </p>
        </div>

        <div
          v-for="expense in expenses"
          :key="expense.id"
          class="bg-gray-50 border-2 border-gray-200 rounded-xl p-5 hover:border-blue-300 transition-colors"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-3">
                <span
                  :class="getCategoryClass(expense.categorie)"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ expense.categorie }}
                </span>
                <span
                  :class="expense.statusClass"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ expense.statut }}
                </span>
                <span class="text-sm text-gray-600">
                  {{ formatDate(expense.date) }}
                </span>
              </div>

              <p v-if="expense.description" class="text-sm text-gray-700 mb-3">
                {{ expense.description }}
              </p>

              <div class="flex items-center gap-4">
                <div class="text-2xl font-bold text-gray-900">
                  {{ formatMontant(expense.montant) }}
                </div>
                <button
                  v-if="expense.justificatif"
                  @click="viewJustificatif(expense)"
                  class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1"
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
                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                    />
                  </svg>
                  Justificatif
                </button>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <button
                v-if="expense.statut === 'En attente'"
                @click="deleteExpense(expense)"
                class="p-2 hover:bg-red-50 rounded-lg transition-colors group"
                title="Supprimer"
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
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal création -->
    <CreateExpenseModal
      v-if="showCreateModal"
      :show="showCreateModal"
      @close="closeCreateModal"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import CreateExpenseModal from "./CreateExpenseModal.vue";
import api from "../services/api/axios";
import Swal from "sweetalert2";

interface Expense {
  id: number;
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
const showCreateModal = ref(false);

const stats = computed(() => {
  const total = expenses.value.length;
  const enAttente = expenses.value.filter(
    (e) => e.statut === "En attente"
  ).length;
  const montantTotal = expenses.value.reduce((sum, e) => sum + e.montant, 0);

  return { total, enAttente, montantTotal };
});

onMounted(() => {
  loadExpenses();
});

const loadExpenses = async () => {
  loading.value = true;
  try {
    const response = await api.get("/expenses/my-expenses");
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    expenses.value = data.map((e: any) => ({
      id: e.id,
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

const openCreateModal = () => {
  showCreateModal.value = true;
};

const closeCreateModal = () => {
  showCreateModal.value = false;
};

const handleSubmit = async (data: any) => {
  try {
    await api.post("/expenses", data);

    await Swal.fire({
      icon: "success",
      title: "Demande envoyée !",
      text: "Votre note de frais a été soumise avec succès",
      confirmButtonColor: "#3b82f6",
      timer: 3000,
    });

    await loadExpenses();
    closeCreateModal();
  } catch (error: any) {
    console.error("Erreur:", error);

    Swal.fire({
      icon: "error",
      title: "Erreur",
      text:
        error.response?.data?.message ||
        "Erreur lors de la soumission de la demande",
      confirmButtonColor: "#3b82f6",
    });
  }
};

const deleteExpense = async (expense: Expense) => {
  const result = await Swal.fire({
    title: "Supprimer cette note de frais ?",
    text: "Cette action est irréversible",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#dc2626",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Oui, supprimer",
    cancelButtonText: "Annuler",
  });

  if (result.isConfirmed) {
    try {
      await api.delete(`/expenses/${expense.id}`);

      await Swal.fire({
        icon: "success",
        title: "Supprimée !",
        text: "La note de frais a été supprimée",
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadExpenses();
    } catch (error) {
      console.error("Erreur:", error);

      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible de supprimer la note de frais",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const viewJustificatif = (expense: Expense) => {
  if (expense.justificatif) {
    window.open(expense.justificatif, "_blank");
  }
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
