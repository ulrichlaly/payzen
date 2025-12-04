<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Départements et postes</h2>
        <p class="text-sm text-gray-500 mt-1">Gérez la structure organisationnelle</p>
      </div>
      <button class="btn-primary flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Nouveau département
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
      <div
        v-for="dept in departments"
        :key="dept.id"
        class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-200 cursor-pointer group"
      >
        <div class="flex items-start justify-between mb-4">
          <div
            class="w-12 h-12 rounded-xl flex items-center justify-center"
            :class="dept.iconBg"
          >
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
          <button class="opacity-0 group-hover:opacity-100 transition-opacity p-2 hover:bg-gray-100 rounded-lg">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
          </button>
        </div>

        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ dept.name }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ dept.description }}</p>

        <div class="flex items-center justify-between mb-4">
          <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ dept.employees }}</p>
            <p class="text-xs text-gray-500">Employés</p>
          </div>
          <div class="text-center">
            <p class="text-2xl font-bold text-gray-900">{{ dept.positions }}</p>
            <p class="text-xs text-gray-500">Postes</p>
          </div>
          <div class="text-center">
            <p class="text-2xl font-bold text-primary-600">{{ dept.budget }}</p>
            <p class="text-xs text-gray-500">Budget (M)</p>
          </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
          <p class="text-xs text-gray-500 mb-2">Responsable</p>
          <div class="flex items-center gap-3">
            <div :class="dept.managerColor" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold">
              {{ dept.managerInitials }}
            </div>
            <div>
              <p class="text-sm font-semibold text-gray-900">{{ dept.manager }}</p>
              <p class="text-xs text-gray-500">{{ dept.managerRole }}</p>
            </div>
          </div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
          <p class="text-xs font-semibold text-gray-600 mb-2">Postes disponibles</p>
          <div class="flex flex-wrap gap-2">
            <span
              v-for="position in dept.availablePositions"
              :key="position"
              class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs"
            >
              {{ position }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4">Liste des postes</h3>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Poste</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Département</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Niveau</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Employés</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Salaire Moyen</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="position in allPositions"
              :key="position.id"
              class="table-row"
            >
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ position.title }}</td>
              <td class="py-4 px-6">
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                  {{ position.department }}
                </span>
              </td>
              <td class="py-4 px-6">
                <span :class="position.levelClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ position.level }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm text-gray-700">{{ position.count }}</td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ position.avgSalary }}</td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button class="p-2 hover:bg-blue-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button class="p-2 hover:bg-red-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const departments = [
  {
    id: 1,
    name: 'Administration',
    description: 'Gestion administrative et coordination',
    employees: 245,
    positions: 12,
    budget: '18.5',
    manager: 'Fatou Diallo',
    managerRole: 'Directrice Administrative',
    managerInitials: 'FD',
    managerColor: 'bg-blue-500',
    iconBg: 'bg-blue-500',
    availablePositions: ['Secrétaire', 'Assistant', 'Coordinateur']
  },
  {
    id: 2,
    name: 'Finance',
    description: 'Comptabilité et gestion financière',
    employees: 189,
    positions: 8,
    budget: '22.3',
    manager: 'Serge Akplogan',
    managerRole: 'Directeur Financier',
    managerInitials: 'SA',
    managerColor: 'bg-green-500',
    iconBg: 'bg-green-500',
    availablePositions: ['Comptable', 'Analyste', 'Auditeur']
  },
  {
    id: 3,
    name: 'Ressources Humaines',
    description: 'Gestion du personnel et recrutement',
    employees: 156,
    positions: 10,
    budget: '15.8',
    manager: 'Rodrigue Hounsou',
    managerRole: 'Responsable RH',
    managerInitials: 'RH',
    managerColor: 'bg-primary-500',
    iconBg: 'bg-primary-500',
    availablePositions: ['Recruteur', 'Gestionnaire', 'Formateur']
  },
  {
    id: 4,
    name: 'Technique',
    description: 'Développement et support technique',
    employees: 298,
    positions: 15,
    budget: '28.9',
    manager: 'Thomas Agbodjan',
    managerRole: 'Chef de Projet',
    managerInitials: 'TA',
    managerColor: 'bg-orange-500',
    iconBg: 'bg-orange-500',
    availablePositions: ['Développeur', 'DevOps', 'Architecte']
  },
  {
    id: 5,
    name: 'Support',
    description: 'Service client et assistance',
    employees: 142,
    positions: 6,
    budget: '12.4',
    manager: 'Marie Kossou',
    managerRole: 'Responsable Support',
    managerInitials: 'MK',
    managerColor: 'bg-red-500',
    iconBg: 'bg-red-500',
    availablePositions: ['Technicien', 'Agent', 'Superviseur']
  },
  {
    id: 6,
    name: 'Marketing',
    description: 'Communication et relations publiques',
    employees: 87,
    positions: 7,
    budget: '9.2',
    manager: 'Rachelle Dossou',
    managerRole: 'Chef Marketing',
    managerInitials: 'RD',
    managerColor: 'bg-pink-500',
    iconBg: 'bg-pink-500',
    availablePositions: ['Chargé de com', 'Designer', 'Community Manager']
  }
]

const allPositions = [
  { id: 1, title: 'Directeur Général', department: 'Direction', level: 'Exécutif', levelClass: 'bg-red-100 text-red-700', count: 1, avgSalary: '1,200,000 FCFA' },
  { id: 2, title: 'Directeur Administratif', department: 'Administration', level: 'Cadre Supérieur', levelClass: 'bg-orange-100 text-orange-700', count: 3, avgSalary: '850,000 FCFA' },
  { id: 3, title: 'Chef de Service', department: 'Technique', level: 'Cadre', levelClass: 'bg-blue-100 text-blue-700', count: 12, avgSalary: '720,000 FCFA' },
  { id: 4, title: 'Analyste RH', department: 'RH', level: 'Intermédiaire', levelClass: 'bg-green-100 text-green-700', count: 8, avgSalary: '480,000 FCFA' },
  { id: 5, title: 'Comptable', department: 'Finance', level: 'Intermédiaire', levelClass: 'bg-green-100 text-green-700', count: 15, avgSalary: '520,000 FCFA' },
  { id: 6, title: 'Développeur', department: 'Technique', level: 'Intermédiaire', levelClass: 'bg-green-100 text-green-700', count: 45, avgSalary: '580,000 FCFA' },
  { id: 7, title: 'Assistant Administratif', department: 'Administration', level: 'Junior', levelClass: 'bg-gray-100 text-gray-700', count: 22, avgSalary: '380,000 FCFA' },
  { id: 8, title: 'Agent Support', department: 'Support', level: 'Junior', levelClass: 'bg-gray-100 text-gray-700', count: 18, avgSalary: '350,000 FCFA' }
]
</script>
