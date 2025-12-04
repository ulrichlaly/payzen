import api from './axios';

export interface Collaborator {
  id?: number;
  fullname: string;
  email: string;
  poste: string;
  salaire_base: number;
  telephone?: string;
  date_embauche: string;
  date_naissance?: string;
  genre?: 'M' | 'F';
  adresse?: string;
  statut?: string;
}

export const collaboratorsService = {
  getAll: async () => {
    const response = await api.get('/collaborators');
    return response.data;
  },

  getById: async (id: number) => {
    const response = await api.get(`/collaborators/${id}`);
    return response.data;
  },

  create: async (collaborator: Collaborator) => {
    const response = await api.post('/collaborators', collaborator);
    return response.data;
  },

  update: async (id: number, collaborator: Partial<Collaborator>) => {
    const response = await api.put(`/collaborators/${id}`, collaborator);
    return response.data;
  },

  delete: async (id: number) => {
    const response = await api.delete(`/collaborators/${id}`);
    return response.data;
  },
};