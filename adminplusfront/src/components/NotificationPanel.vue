<template>
  <div class="relative">
    <!-- Bouton notification -->
    <button
      @click="isOpen = !isOpen"
      class="relative p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all"
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
          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
        />
      </svg>
      <span
        v-if="unreadCount > 0"
        class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
      >
        {{ unreadCount > 9 ? "9+" : unreadCount }}
      </span>
    </button>

    <!-- Panel de notifications -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        class="absolute right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl z-50 overflow-hidden"
      >
        <!-- Header -->
        <div
          class="bg-gradient-to-r from-purple-600 to-blue-600 text-white p-4 flex items-center justify-between"
        >
          <h3 class="font-bold text-lg flex items-center gap-2">
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
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
              />
            </svg>
            Notifications
          </h3>
          <div class="flex gap-2">
            <button
              v-if="unreadCount > 0"
              @click.stop="markAllAsRead"
              class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition-all"
            >
              Tout lire
            </button>
            <button
              v-if="notifications.length > 0"
              @click.stop="clearAll"
              class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg transition-all"
            >
              Effacer
            </button>
          </div>
        </div>

        <!-- Liste des notifications -->
        <div class="max-h-96 overflow-y-auto">
          <div v-if="notifications.length === 0" class="p-8 text-center">
            <svg
              class="w-16 h-16 text-gray-300 mx-auto mb-3"
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
            <p class="text-gray-500 font-medium">Aucune notification</p>
            <p class="text-xs text-gray-400 mt-1">Vous êtes à jour !</p>
          </div>

          <div
            v-for="notif in notifications"
            :key="notif.id"
            @click="handleNotificationClick(notif)"
            :class="[
              'p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-all',
              !notif.read && 'bg-blue-50',
            ]"
          >
            <div class="flex items-start gap-3">
              <!-- Icône -->
              <div
                :class="[
                  'w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0',
                  getNotificationColor(notif.type),
                ]"
              >
                <svg
                  class="w-5 h-5 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    :d="getNotificationIcon(notif.type)"
                  />
                </svg>
              </div>

              <!-- Contenu -->
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 text-sm">
                  {{ notif.title }}
                </p>
                <p class="text-sm text-gray-600 mt-1">{{ notif.message }}</p>
                <p class="text-xs text-gray-400 mt-2">
                  {{ formatTime(notif.timestamp) }}
                </p>
              </div>

              <!-- Badge non lu -->
              <div
                v-if="!notif.read"
                class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-2"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Overlay pour fermer -->
    <div v-if="isOpen" @click="isOpen = false" class="fixed inset-0 z-40"></div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useNotifications } from "../composables/useNotifications";

const router = useRouter();
const isOpen = ref(false);

const { notifications, unreadCount, markAsRead, markAllAsRead, clearAll } =
  useNotifications();

const handleNotificationClick = (notif: any) => {
  markAsRead(notif.id);
  isOpen.value = false;

  // Redirection selon le type
  if (notif.type === "loan_request" || notif.type === "loan_status") {
    router.push("/dashboard/loans");
  } else if (notif.type === "conge_request" || notif.type === "conge_status") {
    router.push("/dashboard/leaves");
  } else if (notif.type === "expense_request") {
    router.push("/dashboard/expenses");
  }
};

const getNotificationColor = (type: string) => {
  const colors: Record<string, string> = {
    loan_request: "bg-gradient-to-br from-blue-500 to-blue-600",
    loan_status: "bg-gradient-to-br from-green-500 to-green-600",
    conge_request: "bg-gradient-to-br from-orange-500 to-orange-600",
    conge_status: "bg-gradient-to-br from-purple-500 to-purple-600",
    expense_request: "bg-gradient-to-br from-pink-500 to-pink-600",
  };
  return colors[type] || "bg-gradient-to-br from-gray-500 to-gray-600";
};

const getNotificationIcon = (type: string) => {
  const icons: Record<string, string> = {
    loan_request:
      "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    loan_status: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    conge_request:
      "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
    conge_status: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    expense_request:
      "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
  };
  return (
    icons[type] || "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
  );
};

const formatTime = (timestamp: string) => {
  const date = new Date(timestamp);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  const minutes = Math.floor(diff / 60000);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);

  if (minutes < 1) return "À l'instant";
  if (minutes < 60) return `Il y a ${minutes} min`;
  if (hours < 24) return `Il y a ${hours}h`;
  if (days < 7) return `Il y a ${days}j`;
  return date.toLocaleDateString("fr-FR", { day: "numeric", month: "short" });
};
</script>
