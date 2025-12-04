import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '../services/api/axios';

interface User {
  id: number;
  fullname: string;
  email: string;
  tel?: string;
  role: string;
  roles: string[];
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null);
  const token = ref<string | null>(localStorage.getItem('token'));

  const isAuthenticated = computed(() => !!token.value);
  const userRole = computed(() => user.value?.role || 'Collaborateur');
  const userRoles = computed(() => user.value?.roles || []);
  
  const isAdmin = computed(() => {
    const roles = user.value?.roles || [];
    return roles.some(role => 
      role === 'Admin' || 
      role === 'Administrateur' || 
      role === 'Admin Général'
    );
  });
  
  const isComptable = computed(() => {
    const roles = user.value?.roles || [];
    return roles.includes('Comptable');
  });
  
  const isCollaborateur = computed(() => {
    const roles = user.value?.roles || [];
    return roles.includes('Collaborateur');
  });

  const login = async (credentials: { email: string; password: string }) => {
    try {
      const response = await api.post('/login', credentials);
      
      console.log('=== RÉPONSE LOGIN ===');
      console.log('Response:', response.data);
      
      // CORRECTION : Le backend retourne 'access_token', pas 'token'
      token.value = response.data.access_token;
      localStorage.setItem('token', response.data.access_token);
      
      // Stocker l'utilisateur
      user.value = response.data.user;
      localStorage.setItem('user', JSON.stringify(response.data.user));
      
      console.log('User après login:', user.value);
      console.log('Rôles:', user.value?.roles);
      console.log('isAdmin:', isAdmin.value);
      console.log('isComptable:', isComptable.value);
      console.log('isCollaborateur:', isCollaborateur.value);
      console.log('====================');
      
      return response.data;
    } catch (error) {
      console.error('Erreur de connexion:', error);
      throw error;
    }
  };

  const fetchUser = async () => {
    try {
      const response = await api.get('/me');
      user.value = response.data;
      localStorage.setItem('user', JSON.stringify(response.data));
      
      console.log('User après fetchUser:', user.value);
      console.log('Rôles après fetchUser:', user.value?.roles);
      
      return response.data;
    } catch (error) {
      console.error('Erreur récupération utilisateur:', error);
      logout();
      throw error;
    }
  };

  const logout = async () => {
    try {
      await api.post('/logout');
    } catch (error) {
      console.error('Erreur logout:', error);
    } finally {
      user.value = null;
      token.value = null;
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    }
  };

  const restoreUser = () => {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      try {
        user.value = JSON.parse(storedUser);
        console.log('User restauré depuis localStorage:', user.value);
      } catch (error) {
        console.error('Erreur restauration utilisateur:', error);
        localStorage.removeItem('user');
      }
    }
  };

  return {
    user,
    token,
    isAuthenticated,
    userRole,
    userRoles,
    isAdmin,
    isComptable,
    isCollaborateur,
    login,
    fetchUser,
    logout,
    restoreUser
  };
});