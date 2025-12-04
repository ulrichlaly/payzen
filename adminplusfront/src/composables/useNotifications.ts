// src/composables/useNotifications.ts
import { ref, onMounted, onUnmounted } from 'vue';
import echo from '../services/echo';
import { useAuthStore } from '../stores/auth';

export interface Notification {
  id: string;
  type: string;
  title: string;
  message: string;
  data: any;
  timestamp: string;
  read: boolean;
}

export function useNotifications() {
  const authStore = useAuthStore();
  const notifications = ref<Notification[]>([]);
  const unreadCount = ref(0);

  // Charger les notifications depuis localStorage
  const loadNotifications = () => {
    const stored = localStorage.getItem('notifications');
    if (stored) {
      notifications.value = JSON.parse(stored);
      unreadCount.value = notifications.value.filter(n => !n.read).length;
    }
  };

  // Sauvegarder les notifications
  const saveNotifications = () => {
    localStorage.setItem('notifications', JSON.stringify(notifications.value));
  };

  // Ajouter une notification
  const addNotification = (notification: Omit<Notification, 'read'>) => {
    const newNotif: Notification = {
      ...notification,
      read: false,
    };
    notifications.value.unshift(newNotif);
    unreadCount.value++;
    saveNotifications();
  };

  // Marquer comme lue
  const markAsRead = (id: string) => {
    const notif = notifications.value.find(n => n.id === id);
    if (notif && !notif.read) {
      notif.read = true;
      unreadCount.value--;
      saveNotifications();
    }
  };

  // Marquer toutes comme lues
  const markAllAsRead = () => {
    notifications.value.forEach(n => n.read = true);
    unreadCount.value = 0;
    saveNotifications();
  };

  // Supprimer une notification
  const removeNotification = (id: string) => {
    const index = notifications.value.findIndex(n => n.id === id);
    if (index !== -1) {
      const notif = notifications.value[index];
      if (!notif.read) unreadCount.value--;
      notifications.value.splice(index, 1);
      saveNotifications();
    }
  };

  // Vider toutes les notifications
  const clearAll = () => {
    notifications.value = [];
    unreadCount.value = 0;
    localStorage.removeItem('notifications');
  };

  // Écouter les événements Pusher
  const setupListeners = () => {
    const user = authStore.user;
    if (!user) return;

    // Pour les admins : écouter les demandes
    if (user.roles?.includes('Admin') || user.roles?.includes('Administrateur')) {
      console.log('🔊 Admin listening on admin-notifications');

      echo.channel('admin-notifications')
        .listen('.loan.requested', (data: any) => {
          console.log('🔔 Loan request received:', data);
          addNotification(data);
        })
        .listen('.conge.requested', (data: any) => {
          console.log('🔔 Conge request received:', data);
          addNotification(data);
        })
        .listen('.expense.requested', (data: any) => {
          console.log('🔔 Expense request received:', data);
          addNotification(data);
        });
    }

    // Pour tous les utilisateurs : écouter leur canal privé
    console.log(`🔊 User listening on user.${user.id}`);

    echo.channel(`user.${user.id}`)
      .listen('.loan.status.changed', (data: any) => {
        console.log('🔔 Loan status changed:', data);
        addNotification(data);
      })
      .listen('.conge.status.changed', (data: any) => {
        console.log('🔔 Conge status changed:', data);
        addNotification(data);
      });
  };

  // Nettoyer les listeners
  const cleanup = () => {
    const user = authStore.user;
    if (!user) return;

    if (user.roles?.includes('Admin') || user.roles?.includes('Administrateur')) {
      echo.leave('admin-notifications');
    }
    echo.leave(`user.${user.id}`);
  };

  onMounted(() => {
    loadNotifications();
    setupListeners();
  });

  onUnmounted(() => {
    cleanup();
  });

  return {
    notifications,
    unreadCount,
    addNotification,
    markAsRead,
    markAllAsRead,
    removeNotification,
    clearAll,
  };
}