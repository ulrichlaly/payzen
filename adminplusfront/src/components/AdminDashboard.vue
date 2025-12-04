<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto p-6 space-y-6">
      <!-- Header Hero Section -->
      <div
        class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden"
      >
        <!-- Decorative circles -->
        <div
          class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"
        ></div>
        <div
          class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"
        ></div>

        <div
          class="relative z-10 flex items-center justify-between flex-wrap gap-6"
        >
          <div class="flex items-center gap-6">
            <div
              class="w-24 h-24 bg-white/20 backdrop-blur-xl rounded-2xl flex items-center justify-center text-3xl font-black border-4 border-white/40 shadow-lg"
            >
              {{ userInitials }}
            </div>
            <div>
              <p class="text-sm text-white/80 mb-1">Bienvenue</p>
              <h1 class="text-4xl font-black mb-2">{{ user?.fullname }}</h1>
              <p class="text-blue-100 flex items-center gap-2">
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
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                  />
                </svg>
                {{ user?.email }}
              </p>
            </div>
          </div>

          <!-- Admin Badge & Notifications -->
          <div class="flex items-center gap-3">
            <span
              class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-bold text-sm shadow-lg"
            >
              Administrateur
            </span>
            <!-- 🔔 Composant NotificationBell avec z-index élevé -->
            <div class="relative z-50">
              <NotificationBell />
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Collaborateurs -->
        <router-link to="/dashboard/employees">
          <div
            class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-blue-500"
          >
            <div class="flex items-center justify-between mb-4">
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
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
              </div>
              <span class="text-4xl font-black text-blue-600">{{
                stats.total_employees || 0
              }}</span>
            </div>
            <h3 class="text-gray-700 font-bold text-lg">Collaborateurs</h3>
            <p class="text-sm text-gray-500">Total actif</p>
          </div>
        </router-link>

        <!-- Paies -->
        <router-link to="/dashboard/payroll">
          <div
            class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-green-500"
          >
            <div class="flex items-center justify-between mb-4">
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
              <span class="text-4xl font-black text-green-600">{{
                stats.total_paies || 0
              }}</span>
            </div>
            <h3 class="text-gray-700 font-bold text-lg">Paies</h3>
            <p class="text-sm text-gray-500">Total générées</p>
          </div>
        </router-link>

        <!-- Déclarations -->
        <router-link to="/dashboard/declarations">
          <div
            class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-purple-500"
          >
            <div class="flex items-center justify-between mb-4">
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
              <span class="text-4xl font-black text-purple-600">{{
                stats.total_declarations || 0
              }}</span>
            </div>
            <h3 class="text-gray-700 font-bold text-lg">Déclarations</h3>
            <p class="text-sm text-gray-500">CNSS + IR</p>
          </div>
        </router-link>

        <!-- Congés -->
        <router-link to="/dashboard/leaves">
          <div
            class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-orange-500"
          >
            <div class="flex items-center justify-between mb-4">
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
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                  />
                </svg>
              </div>
              <span class="text-4xl font-black text-orange-600">{{
                stats.total_conges || 0
              }}</span>
            </div>
            <h3 class="text-gray-700 font-bold text-lg">Congés</h3>
            <p class="text-sm text-gray-500">En attente</p>
          </div>
        </router-link>

        <!-- Pret et Avance  -->
        <router-link to="/dashboard/loans">
          <div
            class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all border-l-4 border-indigo-400"
          >
            <div class="flex items-center justify-between mb-4">
              <div
                class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg"
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
              <span class="text-4xl font-black text-indigo-600">{{
                stats.loans_en_attente || 0
              }}</span>
            </div>
            <h3 class="text-gray-700 font-bold text-lg">Prêts/Avances</h3>
            <p class="text-sm text-gray-500">En attente</p>
          </div>
        </router-link>
      </div>

      <!-- Actions rapides -->
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2
          class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3"
        >
          <div
            class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center"
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
                d="M13 10V3L4 14h7v7l9-11h-7z"
              />
            </svg>
          </div>
          Actions rapides
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <router-link
            to="/dashboard/employees"
            class="flex flex-col items-center gap-3 p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl hover:shadow-lg transition-all group border border-blue-200"
          >
            <div
              class="w-14 h-14 bg-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md"
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
                  d="M12 4v16m8-8H4"
                />
              </svg>
            </div>
            <p
              class="text-sm font-bold text-gray-700 group-hover:text-blue-600 text-center"
            >
              Ajouter collaborateur
            </p>
          </router-link>

          <router-link
            to="/dashboard/payroll"
            class="flex flex-col items-center gap-3 p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl hover:shadow-lg transition-all group border border-green-200"
          >
            <div
              class="w-14 h-14 bg-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md"
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
                  d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                />
              </svg>
            </div>
            <p
              class="text-sm font-bold text-gray-700 group-hover:text-green-600 text-center"
            >
              Générer paies
            </p>
          </router-link>

          <router-link
            to="/dashboard/declarations"
            class="flex flex-col items-center gap-3 p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl hover:shadow-lg transition-all group border border-purple-200"
          >
            <div
              class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md"
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
            <p
              class="text-sm font-bold text-gray-700 group-hover:text-purple-600 text-center"
            >
              Déclarations
            </p>
          </router-link>

          <router-link
            to="/dashboard/leaves"
            class="flex flex-col items-center gap-3 p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl hover:shadow-lg transition-all group border border-orange-200"
          >
            <div
              class="w-14 h-14 bg-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-md"
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
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
            <p
              class="text-sm font-bold text-gray-700 group-hover:text-orange-600 text-center"
            >
              Gérer congés
            </p>
          </router-link>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

              <!-- Vue d'ensemble -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <h2
            class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3"
          >
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
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                />
              </svg>
            </div>
            Vue d'ensemble
          </h2>
          <div class="space-y-3">
            <div
              class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl"
            >
              <span class="text-sm font-semibold text-gray-700"
                >Collaborateurs actifs</span
              >
              <span class="text-2xl font-black text-blue-600">{{
                stats.total_employees
              }}</span>
            </div>
            <div
              class="flex items-center justify-between p-5 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl"
            >
              <span class="text-sm font-semibold text-gray-700"
                >Masse salariale mensuelle</span
              >
              <span class="font-bold text-green-700"
                >{{stats.masseSalariale}}</span
              >
            </div>
            <div
              class="flex items-center justify-between p-5 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl"
            >
              <span class="text-sm font-semibold text-gray-700"
                >Congés à valider</span
              >
              <span class="text-2xl font-black text-orange-700">{{
                stats.total_conges
              }}</span>
            </div>
          </div>
        </div>


        <div class="bg-white rounded-2xl shadow-lg p-6">
          <h2
            class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3"
          >
            <div
              class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center"
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
                  d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                />
              </svg>
            </div>
            Répartition des activités
          </h2>

          <!-- Canvas Chart.js -->
          <div class="w-full h-[260px]">
            <canvas id="pieChart"></canvas>
          </div>

          <!-- Légendes -->
          <div class="grid grid-cols-2 gap-3 mt-4">
            <div
              v-for="(item, index) in pieData"
              :key="index"
              class="flex items-center gap-2"
            >
              <div
                class="w-4 h-4 rounded"
                :style="{ backgroundColor: item.color }"
              ></div>
              <span class="text-xs font-semibold text-gray-700">{{
                item.name
              }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Notifications Panel (Slide-in) -->
    <transition name="slide">
      <div
        v-if="showNotifications"
        class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl z-50 overflow-y-auto"
      >
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-black text-gray-900">Notifications</h2>
            <button
              @click="showNotifications = false"
              class="text-gray-400 hover:text-gray-600"
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
                  d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                />
              </svg>
            </div>
            Répartition des activités
          </h2>

          <!-- Canvas Chart.js -->
          <div class="w-full h-[260px]">
            <canvas id="pieChart"></canvas>
          </div>

          <!-- Légendes -->
          <div class="grid grid-cols-2 gap-3 mt-4">
            <div
              v-for="(item, index) in pieData"
              :key="index"
              class="flex items-center gap-2"
            >
              <div
                class="w-4 h-4 rounded"
                :style="{ backgroundColor: item.color }"
              ></div>
              <span class="text-xs font-semibold text-gray-700">{{
                item.name
              }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import NotificationBell from "../components/NotificationBell.vue";
import api from "../services/api/axios";
import Chart from "chart.js/auto";

const authStore = useAuthStore();
const user = authStore.user;

const stats = ref({
  total_employees: 0,
  total_paies: 0,
  total_declarations: 0,
  total_conges: 0,
  total_loans: 0,
  loans_en_attente: 0,
  masseSalariale: "0 FCFA",
});

const showNotifications = ref(false);

const notifications = ref([
  {
    id: 1,
    title: "Nouvelle demande de congé",
    message: "Jean Dupont a demandé un congé du 15-20 décembre",
    date: "Il y a 1h",
  },
  {
    id: 2,
    title: "Paies générées",
    message: "Les bulletins de novembre 2025 sont prêts",
    date: "Il y a 3h",
  },
  {
    id: 3,
    title: "Déclaration CNSS",
    message: "Date limite: 15 novembre 2025",
    date: "Yesterday",
  },
]);

const userInitials = computed(() => {
  if (!user?.fullname) return "AD";
  const parts = user.fullname.split(" ");
  return parts
    .map((p: string) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
});

// ===== Données pour Chart.js (calculées dynamiquement) =====
const pieData = ref([
  { name: "Collaborateurs", value: 0, color: "#3B82F6" }, // Bleu - Changez cette couleur ici
  { name: "Paies", value: 0, color: "#22C55E" },
  { name: "Déclarations", value: 0, color: "#A855F7" },
  { name: "Congés", value: 0, color: "#F97316" },
  { name: "Prêts/Avances", value: 0, color: "#6366F1" },
]);

let chartInstance = null;

onMounted(async () => {
  try {
    // Charger les stats principales
    const response = await api.get("/dashboard");
    stats.value = {
      ...stats.value,
      ...(response.data.stats || {}),
    };

    // Charger spécifiquement les stats des prêts
    try {
      const loansResponse = await api.get("/loans");
      const loansData = Array.isArray(loansResponse.data)
        ? loansResponse.data
        : loansResponse.data.data || [];

      // Calculer les stats des prêts
      const totalLoans = loansData.length;
      const loansEnAttente = loansData.filter(
        (l: any) => l.statut === "En attente"
      ).length;

      stats.value.total_loans = totalLoans;
      stats.value.loans_en_attente = loansEnAttente;
    } catch (error) {
      console.error("Erreur chargement prêts:", error);
    }

    // Mettre à jour les données du graphique avec les vraies valeurs
    const total = 
      stats.value.total_employees +
      stats.value.total_paies +
      stats.value.total_declarations +
      stats.value.total_conges +
      stats.value.total_loans;

    if (total > 0) {
      pieData.value[0].value = Math.round((stats.value.total_employees / total) * 100);
      pieData.value[1].value = Math.round((stats.value.total_paies / total) * 100);
      pieData.value[2].value = Math.round((stats.value.total_declarations / total) * 100);
      pieData.value[3].value = Math.round((stats.value.total_conges / total) * 100);
      pieData.value[4].value = Math.round((stats.value.total_loans / total) * 100);
    }

    // ===== Init Chart.js =====
    const ctx = document.getElementById("pieChart") as HTMLCanvasElement;

    if (ctx) {
      chartInstance = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: pieData.value.map((i) => i.name),
          datasets: [
            {
              data: pieData.value.map((i) => i.value),
              backgroundColor: pieData.value.map((i) => i.color),
              borderWidth: 0,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: (ctx) => `${ctx.label}: ${ctx.raw}%`,
              },
            },
          },
          cutout: "55%",
        },
      });
    }
  } catch (error) {
    console.error("Erreur chargement stats:", error);
  }
});
</script>

<style scoped>
/* Animations personnalisées */
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.stat-card {
  animation: slideIn 0.5s ease-out;
}
</style>
