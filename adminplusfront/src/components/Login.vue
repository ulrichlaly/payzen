<template>
  <div class="min-h-screen flex">
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
      <div class="absolute inset-0" style="background-image: url('src/assets/loginimage.png'); background-size: cover; background-position: center;"></div>
      
      <div class="absolute inset-0 bg-gradient-to-br from-blue-900/95 via-blue-800/90 to-indigo-900/95"></div>
      
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-cyan-400 rounded-full blur-3xl"></div>
      </div>

      <div class="absolute top-0 right-0 bottom-0 w-20 z-20">
        <svg class="h-full w-full text-gray-50" viewBox="0 0 100 100" preserveAspectRatio="none">
          <path d="M0,0 C30,20 30,40 0,60 C30,70 30,90 0,100 L100,100 L100,0 Z" fill="currentColor" />
        </svg>
      </div>

      <div class="relative z-10 flex flex-col justify-between p-8 text-white w-full overflow-y-auto scrollbar-hide">
         <div class="flex-1 flex flex-col justify-center space-y-6">
          <div class="space-y-3">
            <h2 class="text-4xl lg:text-5xl font-black leading-tight bg-gradient-to-r from-white via-cyan-100 to-white bg-clip-text text-transparent">
              Système de Gestion<br />Administrative
            </h2>
            <div class="w-20 h-1 bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full"></div>
          </div>

          <p class="text-lg font-medium text-cyan-50 leading-relaxed max-w-md">
            Plateforme unifiée pour la gestion des ressources humaines, présences et paies
          </p>

          <div class="space-y-3 pt-2">
            <div class="group flex items-center gap-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-cyan-500/20">
              <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-cyan-400/50 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <span class="text-base font-bold text-white">Gestion du personnel</span>
            </div>
            
            <div class="group flex items-center gap-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-blue-500/20">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-blue-400/50 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-base font-bold text-white">Suivi des présences</span>
            </div>
            
            <div class="group flex items-center gap-3 bg-white/10 backdrop-blur-md p-4 rounded-xl border border-white/20 hover:bg-white/15 transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-indigo-500/20">
              <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg group-hover:shadow-indigo-400/50 transition-all duration-300">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
              </div>
              <span class="text-base font-bold text-white">Rapports & Analyses</span>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-white/20 mt-6">
          <p class="text-xs font-medium text-cyan-200">© 2025 PAYZEN Bénin</p>
          <p class="text-xs font-medium text-cyan-300">Tous droits réservés</p>
        </div>
      </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-gradient-to-br from-gray-50 to-blue-50/30 overflow-y-auto">
      <div class="w-full max-w-md">
        <div class="flex items-center justify-center mb-2">
          <div class="w-28 h-28 sm:w-32 sm:h-32 md:w-40 md:h-40 flex items-center justify-center">
            <img src="/LogoPAYZEN.png" alt="PAYZEN Logo" class="w-full h-full object-contain"></img>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl shadow-blue-500/10 p-6 sm:p-8 border border-gray-100">
          <div class="mb-6 sm:mb-8">
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 mb-2">Connexion</h2>
            <p class="text-sm sm:text-base text-gray-600 font-medium">Accédez à votre espace de gestion</p>
          </div>

          <form @submit.prevent="handleLogin" class="space-y-4 sm:space-y-5">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Adresse email
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                  </svg>
                </div>
                <input
                  v-model="email"
                  type="email"
                  required
                  class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-medium text-gray-900 placeholder-gray-400"
                  placeholder="email"
                />
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">
                Mot de passe
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <input
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  class="w-full pl-12 pr-14 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-medium text-gray-900 placeholder-gray-400"
                  placeholder="••••••••••"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-4 flex items-center"
                >
                  <svg v-if="!showPassword" class="w-5 h-5 text-gray-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="w-5 h-5 text-gray-400 hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
            </div>

            <div class="flex items-center justify-between">
              <label class="flex items-center cursor-pointer group">
                <input
                  v-model="rememberMe"
                  type="checkbox"
                  class="w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                />
                <span class="ml-2 text-sm font-semibold text-gray-600 group-hover:text-gray-900 transition-colors">Se souvenir de moi</span>
              </label>
              <a href="#" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">
                Mot de passe oublié ?
              </a>
            </div>

            <div v-if="errorMessage" class="bg-red-50 border-2 border-red-200 rounded-xl p-3 flex items-start gap-2">
              <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-red-700 text-sm font-semibold">{{ errorMessage }}</p>
            </div>

            <button
              type="submit"
              :disabled="loading"
              class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white py-3.5 rounded-xl font-bold text-base transition-all duration-300 shadow-lg shadow-blue-500/50 hover:shadow-xl hover:shadow-blue-600/60 transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
            >
              <span v-if="loading" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Connexion en cours...
              </span>
              <span v-else>Se connecter</span>
            </button>
          </form>

          <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 font-medium">
              Besoin d'assistance ?
              <a href="#" class="font-bold text-blue-600 hover:text-blue-700 transition-colors ml-1">
                Contactez le support
              </a>
            </p>
          </div>
        </div>

        <div class="mt-4 sm:mt-6 text-center">
          <p class="text-xs sm:text-sm text-gray-500 font-medium">
            Sécurisé par PAYZEN • Protection des données
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import api from "../services/api/axios";
import router from "../router";
import { getRoleDashboard } from "../router/roleRedirect";

const email = ref("");
const password = ref("");
const showPassword = ref(false);
const rememberMe = ref(false);
const loading = ref(false);
const errorMessage = ref("");

const handleLogin = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.post("/login", {
      email: email.value,
      password: password.value,
    });

    if (response.data && response.data.access_token) {
      localStorage.setItem("token", response.data.access_token);
      
      const user = response.data.user;
      localStorage.setItem("user", JSON.stringify(user));

      const userRole = user.role || 'Collaborateur';
      console.log('Rôle de l\'utilisateur:', userRole);
      
      const dashboardRoute = getRoleDashboard(userRole);
      // const dashboardRoute = "/comptable/dashboard";
      console.log('Redirection vers:', dashboardRoute);
      
      await router.replace(dashboardRoute);
      location.reload()
      
    } else {
      errorMessage.value = "Réponse du serveur invalide. Veuillez réessayer.";
    }
  } catch (error: any) {
    console.error("Erreur de connexion:", error);
    
    if (error.response?.status === 401) {
      errorMessage.value = "Identifiants incorrects. Veuillez réessayer.";
    } else if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    } else if (error.message === 'Network Error') {
      errorMessage.value = "Impossible de se connecter au serveur. Vérifiez votre connexion.";
    } else {
      errorMessage.value = "Erreur de connexion. Veuillez réessayer.";
    }
  } finally {
    loading.value = false;
  }
};
</script>