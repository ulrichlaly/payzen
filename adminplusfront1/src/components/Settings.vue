<template>
  <div class="space-y-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-900">Paramètres</h2>
      <p class="text-sm text-gray-500 mt-1">Gérez les paramètres de votre compte et de l'application</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="xl:col-span-1">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <div class="flex flex-col items-center">
            <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
              AP
            </div>
            <h3 class="text-lg font-bold text-gray-900">Admin Principal</h3>
            <p class="text-sm text-gray-500 mb-4">admin@benin.gov</p>
            <button class="btn-primary w-full">Modifier le profil</button>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-100">
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Rôle</span>
                <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-semibold">Super Admin</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Département</span>
                <span class="text-sm font-semibold text-gray-900">Administration</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Membre depuis</span>
                <span class="text-sm font-semibold text-gray-900">Jan 2023</span>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mt-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Actions rapides</h3>
          <div class="space-y-2">
            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              <span class="text-sm font-medium text-gray-700">Exporter les données</span>
            </button>
            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-3">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              <span class="text-sm font-medium text-gray-700">Sécurité avancée</span>
            </button>
            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-red-50 transition-colors flex items-center gap-3">
              <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span class="text-sm font-medium text-red-600">Déconnexion</span>
            </button>
          </div>
        </div>
      </div>

      <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-6">Informations personnelles</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
              <input type="text" value="Admin" class="input-field" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
              <input type="text" value="Principal" class="input-field" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input type="email" value="admin@benin.gov" class="input-field" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
              <input type="tel" value="+229 97 12 34 56" class="input-field" />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
              <input type="text" value="Cotonou, Bénin" class="input-field" />
            </div>
          </div>
          <div class="flex items-center justify-end gap-3 mt-6">
            <button class="btn-secondary">Annuler</button>
            <button class="btn-primary">Enregistrer</button>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-6">Sécurité</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel</label>
              <input type="password" placeholder="••••••••" class="input-field" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe</label>
              <input type="password" placeholder="••••••••" class="input-field" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe</label>
              <input type="password" placeholder="••••••••" class="input-field" />
            </div>
          </div>
          <div class="flex items-center justify-end gap-3 mt-6">
            <button class="btn-secondary">Annuler</button>
            <button class="btn-primary">Changer le mot de passe</button>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-6">Préférences d'affichage</h3>
          <div class="space-y-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-900">Thème sombre</p>
                <p class="text-xs text-gray-500">Activer le mode sombre</p>
              </div>
              <button class="relative w-12 h-6 bg-gray-200 rounded-full transition-colors">
                <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform"></span>
              </button>
            </div>
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-900">Notifications</p>
                <p class="text-xs text-gray-500">Recevoir les notifications push</p>
              </div>
              <button class="relative w-12 h-6 bg-primary-600 rounded-full transition-colors">
                <span class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full transition-transform"></span>
              </button>
            </div>
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm font-medium text-gray-900">Notifications email</p>
                <p class="text-xs text-gray-500">Recevoir les alertes par email</p>
              </div>
              <button class="relative w-12 h-6 bg-primary-600 rounded-full transition-colors">
                <span class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full transition-transform"></span>
              </button>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Langue</label>
              <select class="input-field">
                <option>Français</option>
                <option>English</option>
                <option>Português</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-900 mb-2">Fuseau horaire</label>
              <select class="input-field">
                <option>Africa/Porto-Novo (GMT+1)</option>
                <option>UTC</option>
                <option>Africa/Dakar (GMT)</option>
              </select>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-6">Gestion des accès</h3>
          <div class="space-y-4">
            <div
              v-for="user in accessUsers"
              :key="user.id"
              class="flex items-center justify-between p-4 rounded-lg border border-gray-200"
            >
              <div class="flex items-center gap-4">
                <div :class="user.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                  {{ user.initials }}
                </div>
                <div>
                  <p class="text-sm font-semibold text-gray-900">{{ user.name }}</p>
                  <p class="text-xs text-gray-500">{{ user.email }}</p>
                </div>
              </div>
              <div class="flex items-center gap-4">
                <select class="input-field w-auto text-sm">
                  <option :selected="user.role === 'Admin'">Admin</option>
                  <option :selected="user.role === 'Manager'">Manager</option>
                  <option :selected="user.role === 'Employé'">Employé</option>
                </select>
                <button class="p-2 hover:bg-red-50 rounded-lg transition-colors">
                  <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <button class="btn-secondary w-full mt-4 flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Ajouter un utilisateur
          </button>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Personnalisation</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'organisation</label>
              <input type="text" value="Admin+ Bénin" class="input-field" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Logo de l'organisation</label>
              <button class="btn-secondary w-full">Télécharger le logo</button>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Couleur principale</label>
              <div class="flex items-center gap-3">
                <input type="color" value="#0284c7" class="w-12 h-12 rounded-lg border border-gray-300 cursor-pointer" />
                <input type="text" value="#0284c7" class="input-field flex-1" />
              </div>
            </div>
          </div>
          <div class="flex items-center justify-end gap-3 mt-6">
            <button class="btn-secondary">Réinitialiser</button>
            <button class="btn-primary">Appliquer</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const accessUsers = [
  { id: 1, name: 'Kokou Mensah', email: 'k.mensah@benin.gov', role: 'Manager', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', email: 'a.saidou@benin.gov', role: 'Admin', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', email: 'j.baptiste@benin.gov', role: 'Employé', initials: 'JB', avatarColor: 'bg-primary-500' }
]
</script>
