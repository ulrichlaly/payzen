<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Prêts et avances</h2>
        <p class="text-sm text-gray-500 mt-1">
          Gestion des demandes de prêts et avances sur salaire
        </p>
      </div>
      <button
        @click="exportLoans"
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
            <p class="text-sm font-medium text-gray-600 mb-1">Total prêts</p>
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
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
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
            <p class="text-sm font-medium text-gray-600 mb-1">Montant total</p>
            <h3 class="text-2xl font-bold text-gray-900">
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
                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
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
          @change="loadLoans"
          class="input-field w-auto"
        >
          <option value="">Tous les statuts</option>
          <option value="En attente">En attente</option>
          <option value="Approuvé">Approuvé</option>
          <option value="Rejeté">Rejeté</option>
          <option value="En cours">En cours</option>
          <option value="Remboursé">Remboursé</option>
        </select>

        <select
          v-model="filters.type"
          @change="loadLoans"
          class="input-field w-auto"
        >
          <option value="">Tous les types</option>
          <option value="Prêt">Prêt</option>
          <option value="Avance">Avance sur salaire</option>
        </select>
      </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-6">
        Demandes de prêts et avances
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
                Type
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Montant
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Durée
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Restant
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
            <tr v-if="loans.length === 0">
              <td colspan="7" class="py-12 text-center text-gray-500">
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
              </td>
            </tr>
            <tr v-for="loan in loans" :key="loan.id" class="table-row">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div
                    :class="loan.avatarColor"
                    class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold"
                  >
                    {{ loan.initials }}
                  </div>
                  <p class="text-sm font-semibold text-gray-900">
                    {{ loan.employeeName }}
                  </p>
                </div>
              </td>
              <td class="py-4 px-6">
                <span
                  :class="getTypeClass(loan.type)"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ loan.type }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm font-bold text-gray-900">
                {{ formatMontant(loan.montant) }}
              </td>
              <td class="py-4 px-6 text-sm text-gray-900">
                {{ loan.duree }} mois
              </td>
              <td class="py-4 px-6 text-sm font-semibold text-blue-600">
                {{ formatMontant(loan.montantRestant) }}
              </td>
              <td class="py-4 px-6">
                <span
                  :class="loan.statusClass"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ loan.statut }}
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button
                    @click="viewLoan(loan)"
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
                    v-if="loan.statut === 'En attente'"
                    @click="approveLoan(loan)"
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
                    v-if="loan.statut === 'En attente'"
                    @click="rejectLoan(loan)"
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
    <LoanDetailModal
      v-if="showDetailModal"
      :show="showDetailModal"
      :loan="selectedLoan"
      @close="showDetailModal = false"
      @approve="approveLoan"
      @reject="rejectLoan"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import LoanDetailModal from "./LoanDetailModal.vue";
import api from "../services/api/axios";
import Swal from "sweetalert2";

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

const loans = ref<Loan[]>([]);
const loading = ref(false);
const successMessage = ref("");
const showDetailModal = ref(false);
const selectedLoan = ref<Loan | null>(null);

const filters = ref({
  statut: "",
  type: "",
});

const stats = computed(() => {
  const total = loans.value.length;
  const enAttente = loans.value.filter((l) => l.statut === "En attente").length;
  const enCours = loans.value.filter((l) => l.statut === "En cours").length;
  const montantTotal = loans.value.reduce((sum, l) => sum + l.montant, 0);

  return { total, enAttente, enCours, montantTotal };
});

onMounted(() => {
  loadLoans();
});

const loadLoans = async () => {
  loading.value = true;
  try {
    const response = await api.get("/loans", { params: filters.value });
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    loans.value = data.map((l: any) => {
      const nomComplet = l.collaborator?.nom_complet || "Collaborateur inconnu";

      return {
        id: l.id,
        employeeName: nomComplet,
        initials: getInitials(nomComplet),
        avatarColor: getRandomColor(),
        type: l.type || "Prêt",
        montant: parseFloat(l.montant || 0),
        duree: parseInt(l.duree || 0),
        montantRestant: parseFloat(l.montant_restant || l.montant || 0),
        statut: l.statut || "En attente",
        statusClass: getStatusClass(l.statut),
        motif: l.motif,
        date_demande: l.created_at,
      };
    });
  } catch (error) {
    console.error("Erreur chargement prêts:", error);
    Swal.fire({
      icon: "error",
      title: "Erreur de chargement",
      text: "Impossible de charger les prêts et avances",
      confirmButtonColor: "#3b82f6",
    });
  } finally {
    loading.value = false;
  }
};

const viewLoan = (loan: Loan) => {
  selectedLoan.value = loan;
  showDetailModal.value = true;
};

const approveLoan = async (loan: Loan) => {
  const result = await Swal.fire({
    title: "Approuver ce prêt ?",
    html: `
      <div class="text-left space-y-2">
        <p><strong>Collaborateur :</strong> ${loan.employeeName}</p>
        <p><strong>Type :</strong> ${loan.type}</p>
        <p><strong>Montant :</strong> ${formatMontant(loan.montant)}</p>
        <p><strong>Durée :</strong> ${loan.duree} mois</p>
        <p class="text-sm text-gray-600 mt-3">Le prêt sera automatiquement déduit du salaire sur ${
          loan.duree
        } mois</p>
      </div>
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
      await api.patch(`/loans/${loan.id}`, { statut: "En cours" });

      await Swal.fire({
        icon: "success",
        title: "Prêt approuvé !",
        text: `Le prêt de ${loan.employeeName} a été approuvé`,
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadLoans();
      showDetailModal.value = false;
    } catch (error) {
      console.error("Erreur:", error);
      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible d'approuver le prêt",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const rejectLoan = async (loan: Loan) => {
  const { value: motif } = await Swal.fire({
    title: "Rejeter ce prêt",
    html: `
      <p class="text-gray-700 mb-4">Collaborateur : <strong>${
        loan.employeeName
      }</strong></p>
      <p class="text-gray-700 mb-4">Montant demandé : <strong>${formatMontant(
        loan.montant
      )}</strong></p>
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
      await api.patch(`/loans/${loan.id}`, {
        statut: "Rejeté",
        motif_rejet: motif,
      });

      await Swal.fire({
        icon: "success",
        title: "Prêt rejeté",
        text: "Le collaborateur sera notifié du rejet",
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadLoans();
      showDetailModal.value = false;
    } catch (error) {
      console.error("Erreur:", error);
      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible de rejeter le prêt",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const exportLoans = async () => {
  await Swal.fire({
    icon: "info",
    title: "Export en cours...",
    text: "Génération du fichier Excel des prêts et avances",
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

const getInitials = (name: string) => {
  if (!name || name === "N/A" || name === "Collaborateur inconnu") return "??";
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
