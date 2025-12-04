<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Présences et pointage</h2>
        <p class="text-sm text-gray-500 mt-1">Suivi quotidien des présences</p>
      </div>
      <div class="flex items-center gap-3">
        <button class="btn-secondary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
          </svg>
          Exporter
        </button>
        <button class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Marquer présence
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Présents</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">1,189</h3>
            <p class="text-xs text-green-600 font-semibold">95.3% du total</p>
          </div>
          <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En retard</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">34</h3>
            <p class="text-xs text-orange-600 font-semibold">2.7% du total</p>
          </div>
          <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Absents</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">24</h3>
            <p class="text-xs text-red-600 font-semibold">1.9% du total</p>
          </div>
          <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Taux de présence</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">95.3%</h3>
            <p class="text-xs text-blue-600 font-semibold">+2.1% vs hier</p>
          </div>
          <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Pointages d'aujourd'hui</h3>
        <div class="flex items-center gap-3">
          <input
            type="date"
            class="input-field w-auto"
            :value="currentDate"
          />
          <select class="input-field w-auto">
            <option value="">Tous les statuts</option>
            <option>Présent</option>
            <option>En retard</option>
            <option>Absent</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Employé</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Département</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Arrivée</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Départ</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Durée</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Statut</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="record in attendanceRecords"
              :key="record.id"
              class="table-row"
            >
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div :class="record.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ record.initials }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-900">{{ record.name }}</p>
                    <p class="text-xs text-gray-500">{{ record.position }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">
                  {{ record.department }}
                </span>
              </td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ record.checkIn }}</td>
              <td class="py-4 px-6 text-sm text-gray-600">{{ record.checkOut }}</td>
              <td class="py-4 px-6 text-sm text-gray-900">{{ record.duration }}</td>
              <td class="py-4 px-6">
                <span :class="record.statusClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ record.status }}
                </span>
              </td>
              <td class="py-4 px-6">
                <button class="text-primary-600 hover:text-primary-700 text-sm font-medium">Modifier</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Retards récurrents</h3>
        <div class="space-y-3">
          <div
            v-for="late in lateEmployees"
            :key="late.id"
            class="flex items-center justify-between p-4 rounded-lg border border-gray-200 hover:border-orange-300 transition-colors"
          >
            <div class="flex items-center gap-3">
              <div :class="late.avatarColor" class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold">
                {{ late.initials }}
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900">{{ late.name }}</p>
                <p class="text-xs text-gray-500">{{ late.department }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm font-bold text-orange-600">{{ late.lateCount }} retards</p>
              <p class="text-xs text-gray-500">Ce mois</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Statistiques hebdomadaires</h3>
        <div class="space-y-4">
          <div
            v-for="day in weeklyStats"
            :key="day.day"
          >
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">{{ day.day }}</span>
              <span class="text-sm font-bold text-gray-900">{{ day.rate }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-500"
                :class="day.color"
                :style="{ width: day.rate + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const currentDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

const attendanceRecords = [
  { id: 1, name: 'Kokou Mensah', position: 'Analyste RH', department: 'RH', checkIn: '07:45', checkOut: '17:30', duration: '9h 45m', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'KM', avatarColor: 'bg-blue-500' },
  { id: 2, name: 'Amina Saidou', position: 'Comptable', department: 'Finance', checkIn: '08:15', checkOut: '17:45', duration: '9h 30m', status: 'En retard', statusClass: 'bg-orange-100 text-orange-700', initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 3, name: 'Jean Baptiste', position: 'Développeur', department: 'Technique', checkIn: '07:30', checkOut: '18:00', duration: '10h 30m', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'JB', avatarColor: 'bg-primary-500' },
  { id: 4, name: 'Thomas Agbodjan', position: 'Chef de Projet', department: 'Technique', checkIn: '-', checkOut: '-', duration: '-', status: 'Absent', statusClass: 'bg-red-100 text-red-700', initials: 'TA', avatarColor: 'bg-orange-500' },
  { id: 5, name: 'Marie Kossou', position: 'Support', department: 'Support', checkIn: '08:00', checkOut: '17:15', duration: '9h 15m', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'MK', avatarColor: 'bg-red-500' },
  { id: 6, name: 'Serge Akplogan', position: 'Directeur', department: 'Finance', checkIn: '08:20', checkOut: '18:30', duration: '10h 10m', status: 'En retard', statusClass: 'bg-orange-100 text-orange-700', initials: 'SA', avatarColor: 'bg-teal-500' },
  { id: 7, name: 'Rachelle Dossou', position: 'Analyste', department: 'Technique', checkIn: '07:50', checkOut: '17:20', duration: '9h 30m', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'RD', avatarColor: 'bg-pink-500' },
  { id: 8, name: 'Rodrigue Hounsou', position: 'Responsable RH', department: 'RH', checkIn: '07:40', checkOut: '17:40', duration: '10h 00m', status: 'Présent', statusClass: 'bg-green-100 text-green-700', initials: 'RH', avatarColor: 'bg-cyan-500' }
]

const lateEmployees = [
  { id: 1, name: 'Amina Saidou', department: 'Finance', lateCount: 8, initials: 'AS', avatarColor: 'bg-green-500' },
  { id: 2, name: 'Serge Akplogan', department: 'Finance', lateCount: 6, initials: 'SA', avatarColor: 'bg-teal-500' },
  { id: 3, name: 'Sylvie Ahmedou', department: 'Admin', lateCount: 5, initials: 'SA', avatarColor: 'bg-lime-500' },
  { id: 4, name: 'Paul Ndiaye', department: 'Support', lateCount: 4, initials: 'PN', avatarColor: 'bg-indigo-500' }
]

const weeklyStats = [
  { day: 'Lundi', rate: 96, color: 'bg-green-500' },
  { day: 'Mardi', rate: 94, color: 'bg-green-500' },
  { day: 'Mercredi', rate: 97, color: 'bg-green-500' },
  { day: 'Jeudi', rate: 95, color: 'bg-green-500' },
  { day: 'Vendredi', rate: 93, color: 'bg-green-500' }
]
</script>
