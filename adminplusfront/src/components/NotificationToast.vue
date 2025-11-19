<template>
  <teleport to="body">
    <div
      class="fixed top-4 right-4 z-[9999] space-y-3 max-w-md pointer-events-none"
    >
      <transition-group name="toast">
        <div
          v-for="notification in notifications"
          :key="notification.id"
          :class="[
            'relative overflow-hidden rounded-xl shadow-2xl border-l-4 p-4 backdrop-blur-xl pointer-events-auto',
            'transform transition-all duration-300 ease-out',
            'hover:scale-105 hover:shadow-3xl cursor-pointer',
            getNotificationClass(notification.type),
          ]"
          @click="removeNotification(notification.id)"
        >
          <!-- Progress bar -->
          <div
            v-if="notification.autoClose"
            class="absolute bottom-0 left-0 h-1 bg-white/30 transition-all duration-100"
            :style="{ width: `${notification.progress}%` }"
          ></div>

          <div class="flex items-start gap-3">
            <!-- Icon -->
            <div
              :class="[
                'flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center',
                getIconBgClass(notification.type),
              ]"
            >
              <svg
                v-if="notification.type === 'success'"
                class="w-6 h-6 text-white"
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
              <svg
                v-else-if="notification.type === 'error'"
                class="w-6 h-6 text-white"
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
              <svg
                v-else-if="notification.type === 'warning'"
                class="w-6 h-6 text-white"
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
              <svg
                v-else
                class="w-6 h-6 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <h4 class="text-sm font-bold mb-1 text-white">
                {{ notification.title }}
              </h4>
              <p class="text-sm opacity-90 text-white">
                {{ notification.message }}
              </p>
              <p
                v-if="notification.description"
                class="text-xs mt-1 opacity-75 text-white"
              >
                {{ notification.description }}
              </p>
            </div>

            <!-- Close button -->
            <button
              @click.stop="removeNotification(notification.id)"
              class="flex-shrink-0 p-1 hover:bg-white/20 rounded-lg transition-colors text-white"
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
        </div>
      </transition-group>
    </div>
  </teleport>
</template>

<script setup lang="ts">
import { ref } from "vue";

interface Notification {
  id: string;
  type: "success" | "error" | "warning" | "info";
  title: string;
  message: string;
  description?: string;
  autoClose?: boolean;
  duration?: number;
  progress?: number;
}

const notifications = ref<Notification[]>([]);

const getNotificationClass = (type: string) => {
  const classes = {
    success: "bg-gradient-to-r from-green-500 to-green-600 border-green-400",
    error: "bg-gradient-to-r from-red-500 to-red-600 border-red-400",
    warning: "bg-gradient-to-r from-orange-500 to-orange-600 border-orange-400",
    info: "bg-gradient-to-r from-blue-500 to-blue-600 border-blue-400",
  };
  return classes[type] || classes.info;
};

const getIconBgClass = (type: string) => {
  return "bg-white/20";
};

const addNotification = (
  notification: Omit<Notification, "id" | "progress">
) => {
  const id = `notif-${Date.now()}-${Math.random()}`;
  const newNotification: Notification = {
    ...notification,
    id,
    autoClose: notification.autoClose !== false,
    duration: notification.duration || 5000,
    progress: 100,
  };

  notifications.value.push(newNotification);

  if (newNotification.autoClose) {
    startAutoClose(newNotification);
  }
};

const startAutoClose = (notification: Notification) => {
  const duration = notification.duration || 5000;
  const interval = 50;
  const step = (interval / duration) * 100;

  const timer = setInterval(() => {
    const notif = notifications.value.find((n) => n.id === notification.id);
    if (!notif) {
      clearInterval(timer);
      return;
    }

    notif.progress = Math.max(0, notif.progress - step);

    if (notif.progress <= 0) {
      clearInterval(timer);
      removeNotification(notification.id);
    }
  }, interval);
};

const removeNotification = (id: string) => {
  const index = notifications.value.findIndex((n) => n.id === id);
  if (index > -1) {
    notifications.value.splice(index, 1);
  }
};

defineExpose({
  addNotification,
  removeNotification,
});
</script>

<style scoped>
.toast-enter-active {
  animation: slideIn 0.3s ease-out;
}

.toast-leave-active {
  animation: slideOut 0.3s ease-in;
}

@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0) scale(1);
    opacity: 1;
  }
  to {
    transform: translateX(400px) scale(0.9);
    opacity: 0;
  }
}
</style>
