import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api/axios';
import echo from '../services/echo';
import { useAuthStore } from './auth';

export interface Notification {
  id: number;
  type: string;
  titre: string;
  message: string;
  data: any;
  lu: boolean;
  temps_ecoule: string;
  created_at: string;
}

export const useNotificationStore = defineStore('notifications', () => {
  const authStore = useAuthStore();
  
  const notifications = ref<Notification[]>([]);
  const loading = ref(false);
  const showPanel = ref(false);

  const nonLues = computed(() => 
    notifications.value.filter(n => !n.lu)
  );

  const countNonLues = computed(() => nonLues.value.length);

  // Charger toutes les notifications
  const loadNotifications = async () => {
    loading.value = true;
    try {
      const response = await api.get('/notifications');
      notifications.value = response.data.notifications.data || [];
    } catch (error) {
      console.error('Erreur chargement notifications:', error);
    } finally {
      loading.value = false;
    }
  };

  // Charger uniquement les non lues
  const loadNonLues = async () => {
    try {
      const response = await api.get('/notifications/non-lues');
      notifications.value = response.data.notifications || [];
    } catch (error) {
      console.error('Erreur chargement notifications non lues:', error);
    }
  };

  // Écouter les notifications en temps réel
  const listenToNotifications = () => {
    const userId = authStore.user?.id;
    if (!userId) return;

    echo.channel(`user.${userId}`)
      .listen('.notification.sent', (data: Notification) => {
        console.log('📬 Nouvelle notification reçue:', data);
        
        // Ajouter la notification au début du tableau
        notifications.value.unshift(data);

        // Jouer un son (optionnel)
        playNotificationSound();

        // Afficher une notification navigateur (optionnel)
        showBrowserNotification(data);
      });
  };

  // Arrêter l'écoute
  const stopListening = () => {
    const userId = authStore.user?.id;
    if (!userId) return;

    echo.leave(`user.${userId}`);
  };

  // Marquer comme lue
  const marquerCommeLu = async (notificationId: number) => {
    try {
      await api.post(`/notifications/${notificationId}/marquer-lu`);
      
      const notif = notifications.value.find(n => n.id === notificationId);
      if (notif) {
        notif.lu = true;
      }
    } catch (error) {
      console.error('Erreur marquage notification:', error);
    }
  };

  // Marquer toutes comme lues
  const marquerToutCommeLu = async () => {
    try {
      await api.post('/notifications/marquer-tout-lu');
      notifications.value.forEach(n => n.lu = true);
    } catch (error) {
      console.error('Erreur marquage toutes notifications:', error);
    }
  };

  // Supprimer une notification
  const supprimerNotification = async (notificationId: number) => {
    try {
      await api.delete(`/notifications/${notificationId}`);
      notifications.value = notifications.value.filter(n => n.id !== notificationId);
    } catch (error) {
      console.error('Erreur suppression notification:', error);
    }
  };

  // Toggle panneau
  const togglePanel = () => {
    showPanel.value = !showPanel.value;
  };

  // Notification sonore
  const playNotificationSound = () => {
    const audio = new Audio('/notification.mp3');
    audio.volume = 0.3;
    audio.play().catch(e => console.log('Impossible de jouer le son:', e));
  };

  // Notification navigateur
  const showBrowserNotification = (notification: Notification) => {
    if ('Notification' in window && Notification.permission === 'granted') {
      new Notification(notification.titre, {
        body: notification.message,
        icon: '/logo.png',
        tag: `notif-${notification.id}`
      });
    }
  };

  // Demander permission notifications navigateur
  const requestNotificationPermission = async () => {
    if ('Notification' in window && Notification.permission === 'default') {
      await Notification.requestPermission();
    }
  };

  return {
    notifications,
    nonLues,
    countNonLues,
    loading,
    showPanel,
    loadNotifications,
    loadNonLues,
    listenToNotifications,
    stopListening,
    marquerCommeLu,
    marquerToutCommeLu,
    supprimerNotification,
    togglePanel,
    requestNotificationPermission
  };
});