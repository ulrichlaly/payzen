<template>
  <router-view />
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, watch } from "vue";
import { useAuthStore } from "./stores/auth";
import { useNotificationStore } from "./stores/notifications";

const authStore = useAuthStore();
const notificationStore = useNotificationStore();

// Surveiller l'état d'authentification
watch(
  () => authStore.isAuthenticated,
  (isAuth) => {
    if (isAuth) {
      // Charger les notifications quand l'utilisateur se connecte
      notificationStore.loadNotifications();
      notificationStore.listenToNotifications();
    } else {
      // Arrêter l'écoute quand l'utilisateur se déconnecte
      notificationStore.stopListening();
    }
  }
);

onMounted(() => {
  // Restaurer l'utilisateur depuis le localStorage
  authStore.restoreUser();

  // Si l'utilisateur est déjà authentifié, charger les notifications
  if (authStore.isAuthenticated) {
    notificationStore.loadNotifications();
    notificationStore.listenToNotifications();
  }
});

onUnmounted(() => {
  // Arrêter l'écoute des notifications lors de la destruction du composant
  notificationStore.stopListening();
});
</script>

<style>
body {
  margin: 0;
  padding: 0;
  font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
    sans-serif;
}

::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
