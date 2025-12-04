import api from './axios';

export interface Employee {
  id?: number;
  fullname: string;
  email: string;
  tel: string;
  role_id: number;
  password?: string;
  password_confirmation?: string;
}

export const employeesService = {
  // Récupérer tous les employés
  getAll: async () => {
    const response = await api.get('/collaborators');
    return response.data;
  },

  // Récupérer un employé par ID
  getById: async (id: number) => {
    const response = await api.get(`/collaborators/${id}`);
    return response.data;
  },

  // Créer un employé
  create: async (employee: Employee) => {
    const response = await api.post('/collaborators', employee);
    return response.data;
  },

  // Mettre à jour un employé
  update: async (id: number, employee: Partial<Employee>) => {
    const response = await api.put(`/collaborators/${id}`, employee);
    return response.data;
  },

  // Supprimer un employé
  delete: async (id: number) => {
    const response = await api.delete(`/collaborators/${id}`);
    return response.data;
  },
};