<template>
  <div class="relative">
    <!-- Bouton cloche -->
    <button
      @click="togglePanel"
      class="relative p-2 text-white hover:bg-white/20 rounded-lg transition-colors"
      :class="{ 'bg-white/20': showPanel }"
    >
      <svg
        class="w-6 h-6"
        :class="{ 'animate-wiggle': hasNewNotifications }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
        />
      </svg>

      <!-- Badge nombre -->
      <span
        v-if="countNonLues > 0"
        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse"
      >
        {{ countNonLues > 9 ? "9+" : countNonLues }}
      </span>
    </button>

    <!-- Overlay -->
    <div
      v-if="showPanel"
      @click="closePanel"
      class="fixed inset-0 z-[9998] bg-black/20"
    ></div>

    <!-- Panneau notifications -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform scale-100 opacity-100"
      leave-to-class="transform scale-95 opacity-0"
    >
      <div
        v-if="showPanel"
        class="fixed right-6 top-20 w-96 max-h-[600px] bg-white rounded-xl shadow-2xl border border-gray-200 z-[9999] overflow-hidden"
        @click.stop
      >
        <!-- Header -->
        <div
          class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white"
        >
          <div class="flex items-center justify-between mb-2">
            <h3 class="font-bold text-lg">Notifications</h3>
            <button
              @click="closePanel"
              class="p-1 hover:bg-white/20 rounded-lg transition-colors"
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
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-sm opacity-90"
              >{{ countNonLues }} non lue(s)</span
            >
            <button
              v-if="countNonLues > 0"
              @click="handleMarquerToutLu"
              class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1 rounded-full transition-colors"
            >
              Tout marquer lu
            </button>
          </div>
        </div>

        <!-- Liste notifications -->
        <div class="overflow-y-auto max-h-[500px]">
          <!-- Erreur de chargement -->
          <div v-if="error" class="p-8 text-center">
            <svg
              class="w-16 h-16 mx-auto mb-3 text-red-400"
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
            <p class="font-semibold text-gray-700 mb-2">Erreur de chargement</p>
            <p class="text-sm text-gray-500 mb-4">{{ error }}</p>
            <button
              @click="retryLoad"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Réessayer
            </button>
          </div>

          <!-- Chargement -->
          <div v-else-if="loading" class="p-8 text-center">
            <svg
              class="animate-spin h-8 w-8 text-blue-600 mx-auto"
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
            <p class="text-sm text-gray-500 mt-2">Chargement...</p>
          </div>

          <!-- Aucune notification -->
          <div
            v-else-if="notifications.length === 0"
            class="p-8 text-center text-gray-500"
          >
            <svg
              class="w-16 h-16 mx-auto mb-3 text-gray-300"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
              />
            </svg>
            <p class="font-semibold">Aucune notification</p>
          </div>

          <!-- Liste des notifications -->
          <div v-else>
            <div
              v-for="notif in notifications"
              :key="notif.id"
              @click="handleNotificationClick(notif)"
              class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer"
              :class="{ 'bg-blue-50': !notif.lu }"
            >
              <div class="flex items-start gap-3">
                <!-- Icône selon le type -->
                <div
                  class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                  :class="getTypeClass(notif.type)"
                >
                  <svg
                    class="w-5 h-5 text-white"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      v-if="notif.type === 'conge'"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                    <path
                      v-else-if="notif.type === 'loan'"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                    <path
                      v-else
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                </div>

                <div class="flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-2">
                    <p class="font-bold text-gray-900 text-sm line-clamp-1">
                      {{ notif.titre }}
                    </p>
                    <button
                      @click.stop="handleSupprimer(notif.id)"
                      class="p-1 hover:bg-red-50 rounded transition-colors group flex-shrink-0"
                    >
                      <svg
                        class="w-4 h-4 text-gray-400 group-hover:text-red-600"
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
                  <p class="text-sm text-gray-700 mt-1 line-clamp-2">
                    {{ notif.message }}
                  </p>
                  <p class="text-xs text-gray-500 mt-2">
                    {{ notif.temps_ecoule }}
                  </p>
                </div>

                <!-- Pastille non lu -->
                <div
                  v-if="!notif.lu"
                  class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-2"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from "vue";
import { useNotificationStore } from "../stores/notifications";
import { useRouter } from "vue-router";

const store = useNotificationStore();
const router = useRouter();

const error = ref<string | null>(null);

const notifications = computed(() => store.notifications);
const countNonLues = computed(() => store.countNonLues);
const showPanel = computed(() => store.showPanel);
const loading = computed(() => store.loading);
const hasNewNotifications = computed(() => countNonLues.value > 0);

const loadData = async () => {
  try {
    error.value = null;
    await store.loadNotifications();
    store.listenToNotifications();
    await store.requestNotificationPermission();
  } catch (err: any) {
    console.error("Erreur chargement notifications:", err);
    error.value = err.message || "Impossible de charger les notifications";
  }
};

onMounted(async () => {
  await loadData();
});

onUnmounted(() => {
  store.stopListening();
});

const togglePanel = () => {
  store.togglePanel();
};

const closePanel = () => {
  store.showPanel = false;
};

const retryLoad = async () => {
  await loadData();
};

const handleNotificationClick = async (notif: any) => {
  try {
    // Marquer comme lue
    if (!notif.lu) {
      await store.marquerCommeLu(notif.id);
    }

    // Redirection selon le type
    if (notif.data?.action_url) {
      router.push(notif.data.action_url);
      closePanel();
    }
  } catch (err) {
    console.error("Erreur lors du clic sur notification:", err);
  }
};

const handleMarquerToutLu = async () => {
  try {
    await store.marquerToutCommeLu();
  } catch (err) {
    console.error("Erreur marquer tout lu:", err);
  }
};

const handleSupprimer = async (id: number) => {
  try {
    await store.supprimerNotification(id);
  } catch (err) {
    console.error("Erreur suppression notification:", err);
  }
};

const getTypeClass = (type: string) => {
  const classes: Record<string, string> = {
    conge: "bg-purple-500",
    loan: "bg-blue-500",
    paie: "bg-green-500",
    expense: "bg-orange-500",
  };
  return classes[type] || "bg-gray-500";
};
</script>

<style scoped>
@keyframes wiggle {
  0%,
  100% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(-10deg);
  }
  75% {
    transform: rotate(10deg);
  }
}

.animate-wiggle {
  animation: wiggle 0.5s ease-in-out infinite;
}
</style>
