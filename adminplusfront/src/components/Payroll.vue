<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 p-6">
    <div class="max-w-7xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-3xl font-black text-gray-900">Paie et bulletins</h2>
          <p class="text-sm text-gray-500 mt-1">
            Gestion des salaires et bulletins de paie
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button
            @click="exportAll"
            class="px-4 py-2 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-semibold hover:border-blue-500 hover:text-blue-600 transition-all flex items-center gap-2"
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
            Exporter tout
          </button>
          <button
            @click="openCreateModal"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl font-bold hover:shadow-lg transition-all flex items-center gap-2"
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
                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
              />
            </svg>
            Calculer les salaires
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <div
          class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-green-500"
        >
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">
                Masse salariale
              </p>
              <h3 class="text-3xl font-black text-gray-900 mb-2">
                {{ formatMontantShort(stats.masseSalariale) }}
              </h3>
              <p class="text-xs text-green-600 font-semibold">
                {{ stats.total }} paies
              </p>
            </div>
            <div
              class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg"
            >
              <svg
                class="w-7 h-7 text-white"
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

        <div
          class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-blue-500"
        >
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">
                Bulletins générés
              </p>
              <h3 class="text-3xl font-black text-gray-900 mb-2">
                {{ stats.total }}
              </h3>
              <p class="text-xs text-blue-600 font-semibold">Ce mois</p>
            </div>
            <div
              class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg"
            >
              <svg
                class="w-7 h-7 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>
          </div>
        </div>

        <div
          class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-purple-500"
        >
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">
                Paiements envoyés
              </p>
              <h3 class="text-3xl font-black text-gray-900 mb-2">
                {{ stats.payees }}
              </h3>
              <p class="text-xs text-green-600 font-semibold">
                {{ calculatePercentage(stats.payees, stats.total) }}% effectués
              </p>
            </div>
            <div
              class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg"
            >
              <svg
                class="w-7 h-7 text-white"
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

        <div
          class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-orange-500"
        >
          <div class="flex items-start justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600 mb-1">
                Déductions prêts
              </p>
              <h3 class="text-2xl font-black text-orange-600 mb-2">
                {{ formatMontantShort(stats.deductionsPrets) }}
              </h3>
              <p class="text-xs text-orange-600 font-semibold">
                {{ stats.pretActifs }} prêts actifs
              </p>
            </div>
            <div
              class="w-14 h-14 bg-gradient-to-br from-orange-400 to-orange-600 rounded-xl flex items-center justify-center shadow-lg"
            >
              <svg
                class="w-7 h-7 text-white"
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

      <!-- Table -->
      <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-2xl font-black text-gray-900 flex items-center gap-3">
            <div
              class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"
            >
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
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
            </div>
            Bulletins de paie
          </h3>
          <div class="flex items-center gap-3">
            <select
              v-model="filters.mois"
              @change="loadPaies"
              class="px-4 py-2 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
            >
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
            <select
              v-model="filters.annee"
              @change="loadPaies"
              class="px-4 py-2 bg-white border-2 border-gray-200 rounded-xl font-semibold text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
            >
              <option value="2025">2025</option>
              <option value="2024">2024</option>
              <option value="2023">2023</option>
            </select>
          </div>
        </div>

        <!-- Loader -->
        <div v-if="loading" class="py-12 flex justify-center">
          <div class="text-center">
            <div
              class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent mb-4"
            ></div>
            <p class="text-gray-600 font-semibold">Chargement des paies...</p>
          </div>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full">
            <thead
              class="bg-gradient-to-r from-gray-50 to-blue-50 border-b-2 border-blue-100"
            >
              <tr>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Collaborateur
                </th>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Salaire Brut
                </th>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Retenues
                </th>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Prêts
                </th>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Salaire Net
                </th>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Statut
                </th>
                <th
                  class="text-left py-4 px-6 text-xs font-black text-gray-700 uppercase"
                >
                  Actions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="paies.length === 0">
                <td colspan="7" class="py-12 text-center text-gray-500">
                  <svg
                    class="w-20 h-20 text-gray-300 mx-auto mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                  <p class="text-xl font-bold text-gray-700">
                    Aucune paie trouvée
                  </p>
                  <p class="text-sm text-gray-500 mt-2">
                    Cliquez sur "Calculer les salaires" pour générer les paies
                  </p>
                </td>
              </tr>
              <tr
                v-for="paie in paies"
                :key="paie.id"
                class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all"
              >
                <td class="py-5 px-6">
                  <div class="flex items-center gap-4">
                    <div
                      :class="paie.avatarColor"
                      class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold shadow-md"
                    >
                      {{ paie.initials }}
                    </div>
                    <div>
                      <p class="text-sm font-bold text-gray-900">
                        {{ paie.employeeName }}
                      </p>
                      <p class="text-xs text-gray-500 font-semibold">
                        {{ paie.poste }}
                      </p>
                    </div>
                  </div>
                </td>
                <td class="py-5 px-6">
                  <span class="text-sm font-bold text-gray-900">
                    {{ formatMontant(paie.salaireBrut) }}
                    <span class="text-xs text-gray-500">FCFA</span>
                  </span>
                </td>
                <td class="py-5 px-6">
                  <span class="text-sm font-bold text-red-600">
                    -{{ formatMontant(paie.retenue) }}
                    <span class="text-xs">FCFA</span>
                  </span>
                </td>
                <td class="py-5 px-6">
                  <span
                    v-if="paie.deductionPret > 0"
                    class="text-sm font-bold text-orange-600"
                  >
                    -{{ formatMontant(paie.deductionPret) }}
                    <span class="text-xs">FCFA</span>
                  </span>
                  <span v-else class="text-sm text-gray-400 font-semibold"
                    >-</span
                  >
                </td>
                <td class="py-5 px-6">
                  <span class="text-base font-black text-green-600">
                    {{ formatMontant(paie.netAPayer) }}
                    <span class="text-xs">FCFA</span>
                  </span>
                </td>
                <td class="py-5 px-6">
                  <span
                    :class="paie.statusClass"
                    class="px-4 py-2 rounded-xl text-xs font-bold"
                  >
                    {{ paie.statusLabel }}
                  </span>
                </td>
                <td class="py-5 px-6">
                  <div class="flex items-center gap-2">
                    <button
                      @click="viewBulletin(paie)"
                      class="p-2 hover:bg-blue-100 rounded-xl transition-all group"
                      title="Voir le bulletin"
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
                      @click="downloadBulletin(paie)"
                      class="p-2 hover:bg-green-100 rounded-xl transition-all group"
                      title="Télécharger"
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
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                        />
                      </svg>
                    </button>
                    <button
                      @click="sendEmail(paie)"
                      class="p-2 hover:bg-purple-100 rounded-xl transition-all group"
                      title="Envoyer par email"
                    >
                      <svg
                        class="w-5 h-5 text-gray-400 group-hover:text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
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

      <!-- Modals -->
      <PaieModal
        v-if="showModal"
        :show="showModal"
        @close="closeModal"
        @submit="handleSubmit"
      />

      <BulletinModal
        v-if="showBulletinModal"
        :show="showBulletinModal"
        :paie="selectedPaie"
        @close="showBulletinModal = false"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import PaieModal from "../components/PaieModal.vue";
import BulletinModal from "../components/BulletinModal.vue";
import api from "../services/api/axios";
import Swal from "sweetalert2";

interface Paie {
  id: number;
  employeeName: string;
  employeeMatricule: string;
  initials: string;
  avatarColor: string;
  poste: string;
  salaireBrut: number;
  primes: number;
  retenue: number;
  deductionPret: number;
  netAPayer: number;
  statusLabel: string;
  statusClass: string;
  periode?: string;
  salaireBase?: number;
  retenues?: number;
}

const paies = ref<Paie[]>([]);
const loading = ref(false);
const showModal = ref(false);
const showBulletinModal = ref(false);
const selectedPaie = ref<Paie | null>(null);
const successMessage = ref("");

const filters = ref({
  mois:
    new Date().getMonth() + 1 < 10
      ? "0" + (new Date().getMonth() + 1)
      : String(new Date().getMonth() + 1),
  annee: new Date().getFullYear().toString(),
});

const stats = computed(() => {
  const total = paies.value.length;
  const payees = paies.value.filter(
    (p: Paie) => p.statusLabel === "Payé" || p.statusLabel === "Payée"
  ).length;
  const masseSalariale = paies.value.reduce(
    (sum: number, p: Paie) => sum + p.netAPayer,
    0
  );
  const deductionsPrets = paies.value.reduce(
    (sum: number, p: Paie) => sum + p.deductionPret,
    0
  );
  const pretActifs = paies.value.filter(
    (p: Paie) => p.deductionPret > 0
  ).length;

  return {
    total,
    payees,
    masseSalariale,
    deductionsPrets,
    pretActifs,
  };
});

onMounted(() => {
  loadPaies();
});

const loadPaies = async () => {
  loading.value = true;
  try {
    const response = await api.get("/paies");
    console.log("📦 RÉPONSE COMPLÈTE:", response.data);

    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    paies.value = data.map((p: any) => {
      const collaborator = p.collaborator || {};
      const user = collaborator.user || {};

      const nom =
        user.fullname || collaborator.nom_complet || user.name || "Non défini";

      const deductionPret = parseFloat(p.deduction_pret || 0);

      return {
        id: p.id,
        employeeName: nom,
        employeeMatricule: collaborator.matricule || "N/A",
        initials: getInitials(nom),
        avatarColor: getRandomColor(),
        poste: collaborator.poste || "N/A",
        salaireBrut:
          parseFloat(p.salaire_base || 0) +
          parseFloat(p.prime || 0) +
          parseFloat(p.indemnite || 0),
        primes: parseFloat(p.prime || 0) + parseFloat(p.indemnite || 0),
        retenue: parseFloat(p.retenue || 0),
        deductionPret: deductionPret,
        netAPayer: parseFloat(p.net_a_payer || 0) - deductionPret,
        statusLabel: "Payé",
        statusClass: "bg-green-100 text-green-700",
      };
    });

    console.log("PAIES FINALES:", paies.value);
  } catch (error) {
    console.error("ERREUR:", error);
    Swal.fire({
      icon: "error",
      title: "Erreur de chargement",
      text: "Impossible de charger les paies",
      confirmButtonColor: "#3b82f6",
    });
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleSubmit = async (data: any) => {
  try {
    await api.post("/paies", data);

    await Swal.fire({
      icon: "success",
      title: "Paies générées !",
      text: "Les salaires ont été calculés avec succès",
      confirmButtonColor: "#3b82f6",
      timer: 2000,
      showConfirmButton: false,
    });

    await loadPaies();
    closeModal();
  } catch (error: any) {
    console.error("Erreur:", error);

    Swal.fire({
      icon: "error",
      title: "Erreur",
      text:
        error.response?.data?.message ||
        "Erreur lors de la génération des paies",
      confirmButtonColor: "#3b82f6",
    });
  }
};

const viewBulletin = (paie: Paie) => {
  selectedPaie.value = {
    ...paie,
    periode: `${getMoisLabel(filters.value.mois)} ${filters.value.annee}`,
    salaireBase: paie.salaireBrut - paie.primes,
    retenues: paie.retenue + paie.deductionPret,
  };
  showBulletinModal.value = true;
};

const downloadBulletin = async (paie: Paie) => {
  await Swal.fire({
    icon: "info",
    title: "Téléchargement",
    html: `Téléchargement du bulletin de <strong>${paie.employeeName}</strong>`,
    confirmButtonColor: "#3b82f6",
    timer: 2000,
  });
};

const sendEmail = async (paie: Paie) => {
  const result = await Swal.fire({
    title: "Envoyer le bulletin par email ?",
    html: `Le bulletin de paie sera envoyé à <strong>${paie.employeeName}</strong>`,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#8b5cf6",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Envoyer",
    cancelButtonText: "Annuler",
  });

  if (result.isConfirmed) {
    try {
      // Simuler l'envoi d'email - remplacer par votre API
      await new Promise((resolve) => setTimeout(resolve, 1000));

      await Swal.fire({
        icon: "success",
        title: "Email envoyé !",
        text: `Le bulletin a été envoyé à ${paie.employeeName}`,
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });
    } catch (error) {
      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible d'envoyer l'email",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const exportAll = async () => {
  await Swal.fire({
    icon: "info",
    title: "Export en cours...",
    text: "Génération du fichier Excel pour toutes les paies",
    confirmButtonColor: "#3b82f6",
    timer: 2000,
  });
};

const formatMontant = (montant: number) => {
  return new Intl.NumberFormat("fr-FR", {
    minimumFractionDigits: 0,
  }).format(montant);
};

const formatMontantShort = (montant: number) => {
  if (montant >= 1000000) {
    return (montant / 1000000).toFixed(1) + "M FCFA";
  }
  return formatMontant(montant) + " FCFA";
};

const calculatePercentage = (value: number, total: number) => {
  return total > 0 ? Math.round((value / total) * 100) : 0;
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
    "bg-indigo-500",
    "bg-pink-500",
  ];
  return colors[Math.floor(Math.random() * colors.length)];
};

const getMoisLabel = (mois: string) => {
  const labels: Record<string, string> = {
    "01": "Janvier",
    "02": "Février",
    "03": "Mars",
    "04": "Avril",
    "05": "Mai",
    "06": "Juin",
    "07": "Juillet",
    "08": "Août",
    "09": "Septembre",
    "10": "Octobre",
    "11": "Novembre",
    "12": "Décembre",
  };
  return labels[mois] || mois;
};
</script>
