<template>
  <NotificationToast ref="notificationToastRef" />
  <router-view />
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useAuthStore } from "./stores/auth";
import { setNotificationComponent } from "./composables/useNotification";
import NotificationToast from "./components/NotificationToast.vue";

const authStore = useAuthStore();
const notificationToastRef = ref();

onMounted(() => {
  authStore.restoreUser();

  if (notificationToastRef.value) {
    setNotificationComponent(notificationToastRef.value);
    console.log("✅ Système de notifications initialisé");
  }
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
