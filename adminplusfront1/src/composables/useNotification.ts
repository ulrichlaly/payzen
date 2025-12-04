import { ref } from 'vue';

interface NotificationOptions {
  title: string;
  message: string;
  description?: string;
  duration?: number;
  autoClose?: boolean;
}

// Instance globale pour les notifications
const notificationComponent = ref<any>(null);

export const setNotificationComponent = (component: any) => {
  notificationComponent.value = component;
};

export const useNotification = () => {
  const showSuccess = (options: NotificationOptions | string, p0: string) => {
    if (!notificationComponent.value) {
      console.warn('Notification component not initialized');
      return;
    }
    
    const notifOptions = typeof options === 'string' 
      ? { title: 'Succès', message: options }
      : options;
    
    notificationComponent.value.addNotification({
      type: 'success',
      ...notifOptions
    });
  };

  const showError = (options: NotificationOptions | string, p0: string) => {
    if (!notificationComponent.value) {
      console.warn('Notification component not initialized');
      return;
    }
    
    const notifOptions = typeof options === 'string' 
      ? { title: 'Erreur', message: options }
      : options;
    
    notificationComponent.value.addNotification({
      type: 'error',
      ...notifOptions,
      duration: notifOptions.duration || 7000
    });
  };

  const showWarning = (options: NotificationOptions | string, p0: string, p1: string) => {
    if (!notificationComponent.value) {
      console.warn('Notification component not initialized');
      return;
    }
    
    const notifOptions = typeof options === 'string' 
      ? { title: 'Attention', message: options }
      : options;
    
    notificationComponent.value.addNotification({
      type: 'warning',
      ...notifOptions
    });
  };

  const showInfo = (options: NotificationOptions | string) => {
    if (!notificationComponent.value) {
      console.warn('Notification component not initialized');
      return;
    }
    
    const notifOptions = typeof options === 'string' 
      ? { title: 'Information', message: options }
      : options;
    
    notificationComponent.value.addNotification({
      type: 'info',
      ...notifOptions
    });
  };

  // ===================================
  // NOTIFICATIONS SPÉCIFIQUES
  // ===================================

  // 👤 GESTION DU PERSONNEL
  const notifyEmployeeCreated = (employeeName: string) => {
    showSuccess({
      title: '✅ Collaborateur créé',
      message: `${employeeName} a été ajouté avec succès`,
      description: '📧 Un email avec les identifiants a été envoyé'
    });
  };

  const notifyEmployeeUpdated = (employeeName: string) => {
    showSuccess({
      title: '✅ Modifications enregistrées',
      message: `Le profil de ${employeeName} a été mis à jour`
    });
  };

  const notifyEmployeeDeleted = (employeeName: string) => {
    showSuccess({
      title: '🗑️ Collaborateur supprimé',
      message: `${employeeName} a été retiré de la base de données`
    });
  };

  // 💰 PAIE & BULLETINS
  const notifyPayrollGenerated = (month: string, count: number) => {
    showSuccess({
      title: '💰 Paie générée',
      message: `${count} bulletin(s) créé(s) pour ${month}`,
      description: 'Les bulletins sont disponibles au téléchargement'
    });
  };

  const notifyBulletinSent = (employeeName: string) => {
    showSuccess({
      title: '📧 Bulletin envoyé',
      message: `Bulletin envoyé à ${employeeName} par email`
    });
  };

  const notifyBulletinDownloaded = (employeeName: string) => {
    showInfo({
      title: '📥 Téléchargement',
      message: `Bulletin de ${employeeName} téléchargé`
    });
  };

  // 📋 DÉCLARATIONS
  const notifyDeclarationSubmitted = (type: string) => {
    showSuccess({
      title: '📋 Déclaration envoyée',
      message: `Déclaration ${type} soumise avec succès`,
      description: 'Un récapitulatif a été envoyé par email'
    });
  };

  const notifyDeclarationGenerated = (type: string) => {
    showSuccess({
      title: '📄 Déclaration générée',
      message: `Déclaration ${type} créée avec succès`
    });
  };

  // 🏖️ CONGÉS
  const notifyLeaveApproved = (employeeName: string) => {
    showSuccess({
      title: '✅ Congé approuvé',
      message: `Demande de ${employeeName} validée`,
      description: '📧 Le collaborateur a été notifié par email'
    });
  };

  const notifyLeaveRejected = (employeeName: string, reason?: string) => {
    showWarning({
      title: '❌ Congé refusé',
      message: `Demande de ${employeeName} rejetée`,
      description: reason || '📧 Le collaborateur a été notifié par email'
    });
  };

  const notifyLeaveSubmitted = () => {
    showSuccess({
      title: '📨 Demande envoyée',
      message: 'Votre demande de congé a été soumise',
      description: 'Vous serez notifié de la décision'
    });
  };

  // 🔐 AUTHENTIFICATION
  const notifyLoginSuccess = (userName: string) => {
    showSuccess({
      title: '🎉 Bienvenue !',
      message: `Connexion réussie - Bonjour ${userName}`,
      duration: 3000
    });
  };

  const notifyLogoutSuccess = () => {
    showInfo({
      title: '🚪 Déconnexion réussie',
      message: 'À bientôt !',
      duration: 3000
    });
  };

  const notifySessionExpired = () => {
    showWarning({
      title: '⏱️ Session expirée',
      message: 'Votre session a expiré, veuillez vous reconnecter',
      autoClose: false
    });
  };

  const notifyUnauthorized = () => {
    showError({
      title: '🔒 Accès refusé',
      message: 'Vous n\'avez pas les permissions nécessaires',
      duration: 5000
    });
  };

  // ❌ ERREURS GÉNÉRALES
  const notifyError = (operation: string, error?: any) => {
    const message = error?.response?.data?.message || error?.message || 'Une erreur est survenue';
    
    showError({
      title: `❌ Erreur lors de ${operation}`,
      message: message,
      description: 'Veuillez réessayer ou contacter le support'
    });
  };

  const notifyNetworkError = () => {
    showError({
      title: '🌐 Erreur de connexion',
      message: 'Impossible de contacter le serveur',
      description: 'Vérifiez votre connexion internet',
      autoClose: false
    });
  };

  const notifyValidationError = (fields: string[]) => {
    showWarning({
      title: '⚠️ Informations manquantes',
      message: 'Veuillez remplir tous les champs requis',
      description: `Champs concernés: ${fields.join(', ')}`
    });
  };

  const notifyServerError = () => {
    showError({
      title: '🔧 Erreur serveur',
      message: 'Une erreur interne est survenue',
      description: 'Nos équipes ont été notifiées'
    });
  };

  // 📧 EMAILS
  const notifyEmailSent = (recipient: string) => {
    showSuccess({
      title: '📧 Email envoyé',
      message: `Email envoyé à ${recipient}`,
      duration: 4000
    });
  };

  const notifyEmailError = () => {
    showWarning({
      title: '⚠️ Erreur d\'envoi',
      message: 'L\'email n\'a pas pu être envoyé',
      description: 'Vérifiez l\'adresse email'
    });
  };

  // 💾 SAUVEGARDE
  const notifySaveSuccess = (item: string) => {
    showSuccess({
      title: '✅ Enregistré',
      message: `${item} enregistré avec succès`
    });
  };

  const notifyUpdateSuccess = (item: string) => {
    showSuccess({
      title: '✅ Mis à jour',
      message: `${item} mis à jour avec succès`
    });
  };

  const notifyDeleteSuccess = (item: string) => {
    showSuccess({
      title: '🗑️ Supprimé',
      message: `${item} supprimé avec succès`
    });
  };

  // 📁 FICHIERS
  const notifyFileUploaded = (fileName: string) => {
    showSuccess({
      title: '📤 Fichier envoyé',
      message: `${fileName} téléchargé avec succès`
    });
  };

  const notifyFileDownloaded = (fileName: string) => {
    showInfo({
      title: '📥 Téléchargement',
      message: `${fileName} téléchargé`
    });
  };

  const notifyFileError = (fileName: string) => {
    showError({
      title: '❌ Erreur de fichier',
      message: `Impossible de traiter ${fileName}`,
      description: 'Vérifiez le format et la taille du fichier'
    });
  };

  return {
    // Méthodes de base
    showSuccess,
    showError,
    showWarning,
    showInfo,
    
    // Personnel
    notifyEmployeeCreated,
    notifyEmployeeUpdated,
    notifyEmployeeDeleted,
    
    // Paie
    notifyPayrollGenerated,
    notifyBulletinSent,
    notifyBulletinDownloaded,
    
    // Déclarations
    notifyDeclarationSubmitted,
    notifyDeclarationGenerated,
    
    // Congés
    notifyLeaveApproved,
    notifyLeaveRejected,
    notifyLeaveSubmitted,
    
    // Authentification
    notifyLoginSuccess,
    notifyLogoutSuccess,
    notifySessionExpired,
    notifyUnauthorized,
    
    // Erreurs
    notifyError,
    notifyNetworkError,
    notifyValidationError,
    notifyServerError,
    
    // Emails
    notifyEmailSent,
    notifyEmailError,
    
    // CRUD
    notifySaveSuccess,
    notifyUpdateSuccess,
    notifyDeleteSuccess,
    
    // Fichiers
    notifyFileUploaded,
    notifyFileDownloaded,
    notifyFileError
  };
};