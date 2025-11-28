<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import { useRouter } from "vue-router";
import CongeModal from "./CongeModal.vue";
import api from "../services/api/axios";

const authStore = useAuthStore();
const router = useRouter();
const user = authStore.user;

const stats = ref({
  solde_conges: 30,
  conges_en_attente: 0,
  paies_count: 0,
});

const collaboratorInfo = ref<any>(null);
const recentBulletins = ref<any[]>([]);
const recentConges = ref<any[]>([]);
const loading = ref(false);
const loadingConges = ref(false);
const showCongeModal = ref(false);
const error = ref("");

const userInitials = computed(() => {
  if (!user?.fullname) return "NA";
  const parts = user.fullname.split(" ");
  return parts
    .map((p: string) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
});

onMounted(async () => {
  await loadDashboardData();
});

const loadDashboardData = async () => {
  try {
    console.log("=== CHARGEMENT DASHBOARD COLLABORATEUR ===");

    try {
      const dashResponse = await api.get("/dashboard");
      console.log("Stats dashboard:", dashResponse.data);
      stats.value = dashResponse.data.stats || stats.value;
    } catch (err) {
      console.warn("Erreur chargement stats dashboard:", err);
    }

    const collabResponse = await api.get("/me");
    console.log("Infos utilisateur:", collabResponse.data);
    const userId = collabResponse.data.id;

    try {
      const collaborators = await api.get("/collaborators");
      console.log("Réponse collaborators:", collaborators.data);

      const collabData = Array.isArray(collaborators.data)
        ? collaborators.data
        : collaborators.data.data || [];

      console.log("Liste collaborateurs:", collabData);

      const myCollab = collabData.find((c: any) => c.user_id === userId);
      console.log("Mon profil collaborateur:", myCollab);

      if (myCollab) {
        collaboratorInfo.value = myCollab;
        await loadBulletins(myCollab.id);
        await loadConges(myCollab.id);
      } else {
        console.error("Profil collaborateur non trouvé pour user_id:", userId);
        error.value =
          "Votre profil collaborateur n'est pas encore créé. Veuillez contacter l'administrateur.";
      }
    } catch (err: any) {
      console.error("Erreur chargement profil collaborateur:", err);
      if (err.response?.status === 404) {
        error.value =
          "Votre profil collaborateur n'existe pas. Veuillez contacter l'administrateur.";
      } else {
        error.value = "Erreur lors du chargement de votre profil.";
      }
    }
  } catch (error) {
    console.error("Erreur chargement dashboard:", error);
  }
};

const loadBulletins = async (collaboratorId: number) => {
  loading.value = true;
  try {
    console.log("Chargement bulletins pour collaborator_id:", collaboratorId);
    const response = await api.get(`/collaborators/${collaboratorId}/paies`);
    console.log("Bulletins reçus:", response.data);

    const data = Array.isArray(response.data)
      ? response.data
      : response.data.historique || [];
    recentBulletins.value = data.slice(0, 3).map((p: any) => ({
      id: p.id,
      periode: `${p.mois}/${p.annee}`,
      net: parseFloat(p.net_a_payer || 0),
    }));

    stats.value.paies_count = data.length;
  } catch (error) {
    console.error("Erreur chargement bulletins:", error);
  } finally {
    loading.value = false;
  }
};

const loadConges = async (collaboratorId: number) => {
  loadingConges.value = true;
  try {
    console.log("Chargement congés pour collaborator_id:", collaboratorId);
    const response = await api.get(`/collaborators/${collaboratorId}/conges`);
    console.log("Congés reçus:", response.data);

    const data = Array.isArray(response.data)
      ? response.data
      : response.data.historique || [];
    recentConges.value = data.slice(0, 3).map((c: any) => ({
      id: c.id,
      periode: `${formatDate(c.date_debut)} - ${formatDate(c.date_fin)}`,
      nb_jours: c.nb_jours,
      type: getTypeLabel(c.type),
      statut: c.statut,
      statutLabel: getStatutLabel(c.statut),
      statutClass: getStatutClass(c.statut),
    }));

    stats.value.conges_en_attente = data.filter(
      (c: any) => c.statut === "en_attente"
    ).length;
  } catch (error) {
    console.error("Erreur chargement congés:", error);
  } finally {
    loadingConges.value = false;
  }
};

const handleCongeSubmit = async (data: any) => {
  if (!collaboratorInfo.value?.id) {
    alert(
      "Votre profil collaborateur n'est pas disponible. Veuillez contacter l'administrateur."
    );
    return;
  }

  try {
    await api.post("/conges", {
      ...data,
      collaborator_id: collaboratorInfo.value.id,
    });
    showCongeModal.value = false;
    alert("Demande de congé envoyée avec succès !");
    await loadConges(collaboratorInfo.value.id);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'envoi de la demande");
  }
};

const goToBulletins = () => {
  router.push("/dashboard/my-bulletins");
};

const goToHistorique = () => {
  router.push("/dashboard/my-history");
};

const downloadBulletin = (bulletin: any) => {
  alert(`Téléchargement du bulletin ${bulletin.periode}...`);
};

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", { minimumFractionDigits: 0 }).format(
      montant
    ) + " FCFA"
  );
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return "N/A";
  const date = new Date(dateStr);
  return date.toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    annuel: "Congé annuel",
    maladie: "Congé maladie",
    maternite: "Congé maternité",
    autre: "Autre",
  };
  return labels[type] || type;
};

const getStatutLabel = (statut: string) => {
  const labels: Record<string, string> = {
    en_attente: "En attente",
    approuvé: "Approuvé",
    refusé: "Refusé",
  };
  return labels[statut] || statut;
};

const getStatutClass = (statut: string) => {
  const classes: Record<string, string> = {
    en_attente: "bg-orange-100 text-orange-700",
    approuvé: "bg-green-100 text-green-700",
    refusé: "bg-red-100 text-red-700",
  };
  return classes[statut] || "bg-gray-100 text-gray-700";
};
</script>

<template>
  <div class="space-y-6 p-6">
    <div v-if="error" class="max-w-4xl mx-auto">
      <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-6">
        <div class="flex items-start gap-4">
          <svg
            class="w-10 h-10 text-orange-500 flex-shrink-0"
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
          <div>
            <h3 class="text-lg font-bold text-orange-800 mb-2">
              Profil incomplet
            </h3>
            <p class="text-orange-700">{{ error }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <div
        class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl p-8 mb-4 text-white shadow-xl"
      >
        <div class="flex items-center justify-between flex-wrap gap-4">
          <div class="flex items-center gap-6">
            <div
              class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-2xl font-bold border-4 border-white/30"
            >
              {{ userInitials }}
            </div>
            <div>
              <h1 class="text-3xl font-bold mb-1">
                Bonjour, {{ user?.fullname }}
              </h1>
              <p class="text-blue-100 text-lg">{{ user?.email }}</p>
            </div>
          </div>
          <button
            @click="showCongeModal = true"
            class="bg-white text-blue-600 px-6 py-3 rounded-xl font-bold hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1"
          >
            + Demander un congé
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <div
              class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"
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
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
            <span class="text-3xl font-black text-green-600">{{
              stats.solde_conges
            }}</span>
          </div>
          <h3 class="text-gray-600 font-semibold">Solde de congés</h3>
          <p class="text-sm text-gray-500 mt-1">Jours disponibles</p>
        </div>

        <!-- Demandes en attente -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <div
              class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-6 h-6 text-orange-600"
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
            <span class="text-3xl font-black text-orange-600">{{
              stats.conges_en_attente
            }}</span>
          </div>
          <h3 class="text-gray-600 font-semibold">En attente</h3>
          <p class="text-sm text-gray-500 mt-1">Demandes de congés</p>
        </div>

        <!-- Bulletins de paie -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <div
              class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"
            >
              <svg
                class="w-6 h-6 text-blue-600"
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
            <span class="text-3xl font-black text-blue-600">{{
              stats.paies_count
            }}</span>
          </div>
          <h3 class="text-gray-600 font-semibold">Bulletins de paie</h3>
          <p class="text-sm text-gray-500 mt-1">Documents disponibles</p>
        </div>
      </div>

      <!-- Section Bulletins récents -->
      <div
        class="bg-white rounded-xl shadow-lg p-6 mt-6 border border-gray-100"
      >
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-gray-900">
            Bulletins de paie récents
          </h2>
          <button
            @click="goToBulletins"
            class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2"
          >
            Voir tout
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
                d="M9 5l7 7-7 7"
              />
            </svg>
          </button>
        </div>

        <div v-if="loading" class="text-center py-8">
          <div
            class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"
          ></div>
          <p class="text-gray-500 mt-2">Chargement...</p>
        </div>

        <div
          v-else-if="recentBulletins.length === 0"
          class="text-center py-8 text-gray-500"
        >
          <svg
            class="w-16 h-16 mx-auto text-gray-300 mb-4"
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
          <p>Aucun bulletin de paie disponible</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="bulletin in recentBulletins"
            :key="bulletin.id"
            class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <div class="flex items-center gap-4">
              <div
                class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
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
              <div>
                <p class="font-semibold text-gray-900">
                  Période: {{ bulletin.periode }}
                </p>
                <p class="text-sm text-gray-500">
                  Net à payer: {{ formatMontant(bulletin.net) }}
                </p>
              </div>
            </div>
            <button
              @click="downloadBulletin(bulletin)"
              class="text-blue-600 hover:text-blue-700 p-2 hover:bg-blue-50 rounded-lg transition-colors"
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
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Section Congés récents -->
      <div
        class="bg-white rounded-xl shadow-lg p-6 mt-6 border border-gray-100"
      >
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold text-gray-900">
            Demandes de congés récentes
          </h2>
          <button
            @click="goToHistorique"
            class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2"
          >
            Voir tout
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
                d="M9 5l7 7-7 7"
              />
            </svg>
          </button>
        </div>

        <div v-if="loadingConges" class="text-center py-8">
          <div
            class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"
          ></div>
          <p class="text-gray-500 mt-2">Chargement...</p>
        </div>

        <div
          v-else-if="recentConges.length === 0"
          class="text-center py-8 text-gray-500"
        >
          <svg
            class="w-16 h-16 mx-auto text-gray-300 mb-4"
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
          <p>Aucune demande de congé</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="conge in recentConges"
            :key="conge.id"
            class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <div class="flex items-center gap-4 flex-1">
              <div
                class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center"
              >
                <svg
                  class="w-5 h-5 text-purple-600"
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
              </div>
              <div class="flex-1">
                <p class="font-semibold text-gray-900">{{ conge.type }}</p>
                <p class="text-sm text-gray-500">
                  {{ conge.periode }} • {{ conge.nb_jours }} jours
                </p>
              </div>
            </div>
            <span
              :class="[
                'px-3 py-1 rounded-full text-xs font-semibold',
                conge.statutClass,
              ]"
            >
              {{ conge.statutLabel }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de demande de congé -->
    <CongeModal
      :show="showCongeModal"
      :on-close="() => (showCongeModal = false)"
      :on-submit="handleCongeSubmit"
    />
  </div>
</template>
