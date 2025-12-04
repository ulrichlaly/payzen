<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto p-6 space-y-6">
      <!-- Header Hero Section -->
      <div
        class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden"
      >
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
          <div class="flex gap-3">
            <span
              class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl font-bold text-sm shadow-lg"
            >
              Administrateur
            </span>

            <!-- 🔥 COMPOSANT NOTIFICATION AVEC PUSHER -->
            <NotificationPanel />
          </div>
        </div>
      </div>

      <!-- Stats Cards Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Collaborateurs -->
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

        <!-- Paies -->
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

        <!-- Déclarations -->
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

        <!-- Congés -->
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
                >Calculé automatiquement</span
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

        <!-- Accès rapide -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <h2
            class="text-2xl font-black text-gray-900 mb-6 flex items-center gap-3"
          >
            <div
              class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"
            >
              <svg
                class="w-5 h-5 text-green-600"
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
            Accès rapide
          </h2>
          <div class="space-y-2">
            <router-link
              to="/dashboard/employees"
              class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl hover:shadow-md transition-all group"
            >
              <div
                class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"
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
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                  />
                </svg>
              </div>
              <div>
                <p class="font-bold text-gray-900">Gestion du personnel</p>
                <p class="text-xs text-gray-500">
                  Gérer les collaborateurs, contrats, informations
                </p>
              </div>
            </router-link>

            <router-link
              to="/dashboard/payroll"
              class="flex items-center gap-4 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl hover:shadow-md transition-all group"
            >
              <div
                class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
              <div>
                <p class="font-bold text-gray-900">Paie et bulletins</p>
                <p class="text-xs text-gray-500">
                  Générer, consulter, télécharger
                </p>
              </div>
            </router-link>

            <router-link
              to="/dashboard/declarations"
              class="flex items-center gap-4 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:shadow-md transition-all group"
            >
              <div
                class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm"
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>
              </div>
              <div>
                <p class="font-bold text-gray-900">Déclarations sociales</p>
                <p class="text-xs text-gray-500">
                  CNSS, IR, obligations fiscales
                </p>
              </div>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import NotificationPanel from "../components/NotificationPanel.vue";
import api from "../services/api/axios";

const authStore = useAuthStore();
const user = authStore.user;

const stats = ref({
  total_employees: 0,
  total_paies: 0,
  total_declarations: 0,
  total_conges: 0,
});

const userInitials = computed(() => {
  if (!user?.fullname) return "AD";
  const parts = user.fullname.split(" ");
  return parts
    .map((p: string) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
});

onMounted(async () => {
  try {
    const response = await api.get("/dashboard");
    stats.value = response.data.stats || {};
  } catch (error) {
    console.error("Erreur chargement stats:", error);
  }
});
</script>

<style scoped>
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
