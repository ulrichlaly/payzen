<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Mes prêts et avances</h2>
        <p class="text-sm text-gray-500 mt-1">
          Gérez vos demandes de prêts et avances sur salaire
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

    <!-- Message de succès -->
    <div
      v-if="successMessage"
      class="bg-green-50 border-2 border-green-200 rounded-lg p-4 flex items-center gap-3"
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
      <p class="text-green-700 font-semibold">{{ successMessage }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Total prêts</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ stats.total }}</h3>
          </div>
          <div
            class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center"
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

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En cours</p>
            <h3 class="text-3xl font-bold text-blue-600">
              {{ stats.enCours }}
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
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">À rembourser</p>
            <h3 class="text-2xl font-bold text-orange-600">
              {{ formatMontantShort(stats.totalRestant) }}
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
    </div>

    <!-- Liste des prêts -->
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
        <div v-if="loans.length === 0" class="py-12 text-center text-gray-500">
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
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <p class="text-lg font-semibold text-gray-700">
            Aucun prêt ou avance
          </p>
          <p class="text-sm text-gray-500 mt-2">
            Cliquez sur "Nouvelle demande" pour faire une demande
          </p>
        </div>

        <div
          v-for="loan in loans"
          :key="loan.id"
          class="bg-gray-50 border-2 border-gray-200 rounded-xl p-5 hover:border-purple-300 transition-colors"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
              <span
                :class="getTypeClass(loan.type)"
                class="px-3 py-1 rounded-full text-xs font-semibold"
              >
                {{ loan.type }}
              </span>
              <span
                :class="loan.statusClass"
                class="px-3 py-1 rounded-full text-xs font-semibold"
              >
                {{ loan.statut }}
              </span>
            </div>
            <button
              v-if="loan.statut === 'En attente'"
              @click="deleteLoan(loan)"
              class="p-2 hover:bg-red-50 rounded-lg transition-colors group"
              title="Annuler"
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

          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
            <div>
              <p class="text-xs text-gray-600 mb-1">Montant</p>
              <p class="text-lg font-bold text-gray-900">
                {{ formatMontant(loan.montant) }}
              </p>
            </div>
            <div>
              <p class="text-xs text-gray-600 mb-1">Durée</p>
              <p class="text-lg font-bold text-gray-900">
                {{ loan.duree }} mois
              </p>
            </div>
            <div>
              <p class="text-xs text-gray-600 mb-1">Mensualité</p>
              <p class="text-lg font-bold text-blue-600">
                {{ formatMontant(loan.montant / loan.duree) }}
              </p>
            </div>
            <div>
              <p class="text-xs text-gray-600 mb-1">Restant</p>
              <p class="text-lg font-bold text-orange-600">
                {{ formatMontant(loan.montantRestant) }}
              </p>
            </div>
          </div>

          <!-- Barre de progression -->
          <div v-if="loan.statut === 'En cours'" class="mb-3">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs text-gray-600">Progression</span>
              <span class="text-xs font-semibold text-purple-600">
                {{
                  Math.round(
                    ((loan.montant - loan.montantRestant) / loan.montant) * 100
                  )
                }}%
              </span>
            </div>
            <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
              <div
                class="bg-gradient-to-r from-purple-500 to-pink-500 h-full transition-all duration-300"
                :style="{
                  width: `${
                    ((loan.montant - loan.montantRestant) / loan.montant) * 100
                  }%`,
                }"
              ></div>
            </div>
          </div>

          <p v-if="loan.motif" class="text-sm text-gray-700 italic">
            "{{ loan.motif }}"
          </p>
        </div>
      </div>
    </div>

    <!-- Modal création -->
    <CreateLoanModal
      v-if="showCreateModal"
      :show="showCreateModal"
      @close="closeCreateModal"
      @submit="handleSubmit"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import CreateLoanModal from "./CreateLoanModal.vue";
import api from "../services/api/axios";

interface Loan {
  id: number;
  type: string;
  montant: number;
  duree: number;
  montantRestant: number;
  statut: string;
  statusClass: string;
  motif?: string;
}

const loans = ref<Loan[]>([]);
const loading = ref(false);
const successMessage = ref("");
const showCreateModal = ref(false);

const stats = computed(() => {
  const total = loans.value.length;
  const enCours = loans.value.filter((l) => l.statut === "En cours").length;
  const totalRestant = loans.value
    .filter((l) => l.statut === "En cours")
    .reduce((sum, l) => sum + l.montantRestant, 0);

  return { total, enCours, totalRestant };
});

onMounted(() => {
  loadLoans();
});

const loadLoans = async () => {
  loading.value = true;
  try {
    const response = await api.get("/loans/my-loans");
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    loans.value = data.map((l: any) => ({
      id: l.id,
      type: l.type || "Prêt",
      montant: parseFloat(l.montant || 0),
      duree: parseInt(l.duree || 0),
      montantRestant: parseFloat(l.montant_restant || l.montant || 0),
      statut: l.statut || "En attente",
      statusClass: getStatusClass(l.statut),
      motif: l.motif,
    }));
  } catch (error) {
    console.error("Erreur chargement prêts:", error);
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
    await api.post("/loans", data);
    successMessage.value = "Demande soumise avec succès !";
    await loadLoans();
    closeCreateModal();
    setTimeout(() => (successMessage.value = ""), 5000);
  } catch (error: any) {
    console.error("Erreur:", error);
    alert(error.response?.data?.message || "Erreur lors de la soumission");
  }
};

const deleteLoan = async (loan: Loan) => {
  if (confirm("Annuler cette demande ?")) {
    try {
      await api.delete(`/loans/${loan.id}`);
      successMessage.value = "Demande annulée";
      await loadLoans();
      setTimeout(() => (successMessage.value = ""), 5000);
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur lors de l'annulation");
    }
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

const getStatusClass = (statut: string) => {
  const map: Record<string, string> = {
    "En attente": "bg-orange-100 text-orange-700",
    Approuvé: "bg-green-100 text-green-700",
    Rejeté: "bg-red-100 text-red-700",
    "En cours": "bg-blue-100 text-blue-700",
    Remboursé: "bg-gray-100 text-gray-700",
  };
  return map[statut] || "bg-gray-100 text-gray-700";
};

const getTypeClass = (type: string) => {
  const map: Record<string, string> = {
    Prêt: "bg-purple-100 text-purple-700",
    Avance: "bg-blue-100 text-blue-700",
  };
  return map[type] || "bg-gray-100 text-gray-700";
};
</script>
