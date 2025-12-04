<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Rapports et statistiques</h2>
        <p class="text-sm text-gray-500 mt-1">Analyses et rapports détaillés</p>
      </div>
      <div class="flex items-center gap-3">
        <button class="btn-secondary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Exporter PDF
        </button>
        <button class="btn-primary flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          Générer rapport
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div
        v-for="report in reportTypes"
        :key="report.id"
        class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all cursor-pointer group"
      >
        <div :class="report.iconBg" class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ report.title }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ report.description }}</p>
        <div class="flex items-center justify-between">
          <span class="text-xs text-gray-500">{{ report.lastGenerated }}</span>
          <button class="text-primary-600 hover:text-primary-700 text-sm font-medium">Générer</button>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Évolution des effectifs</h3>
          <select class="input-field w-auto">
            <option>12 derniers mois</option>
            <option>6 derniers mois</option>
            <option>Cette année</option>
          </select>
        </div>
        <div class="h-64 flex items-end justify-between gap-2">
          <div
            v-for="(bar, index) in employeeGrowth"
            :key="index"
            class="flex-1 bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg relative group cursor-pointer hover:from-primary-600 hover:to-primary-500 transition-all"
            :style="{ height: bar.height + '%' }"
          >
            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-2 py-1 rounded text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
              {{ bar.value }} employés
            </div>
            <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-xs text-gray-600 font-medium">{{ bar.month }}</span>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Taux de présence mensuel</h3>
          <select class="input-field w-auto">
            <option>6 derniers mois</option>
            <option>12 derniers mois</option>
          </select>
        </div>
        <div class="space-y-4">
          <div
            v-for="month in attendanceRate"
            :key="month.month"
          >
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">{{ month.month }}</span>
              <span class="text-sm font-bold" :class="month.rateClass">{{ month.rate }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
              <div
                class="h-full rounded-full transition-all duration-500"
                :class="month.barColor"
                :style="{ width: month.rate + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-6">Analyse comparative par département</h3>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Département</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Effectif</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Budget</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Taux présence</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Turnover</th>
              <th class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase">Performance</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="dept in departmentAnalysis"
              :key="dept.id"
              class="table-row"
            >
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div :class="dept.iconBg" class="w-10 h-10 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </div>
                  <span class="text-sm font-semibold text-gray-900">{{ dept.name }}</span>
                </div>
              </td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ dept.headcount }}</td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">{{ dept.budget }}</td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <div class="flex-1 bg-gray-100 rounded-full h-2">
                    <div :class="dept.attendanceColor" class="h-full rounded-full" :style="{ width: dept.attendance + '%' }"></div>
                  </div>
                  <span class="text-sm font-semibold text-gray-900 w-12">{{ dept.attendance }}%</span>
                </div>
              </td>
              <td class="py-4 px-6">
                <span :class="dept.turnoverClass" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ dept.turnover }}%
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-1">
                  <svg
                    v-for="n in 5"
                    :key="n"
                    class="w-4 h-4"
                    :class="n <= dept.performance ? 'text-yellow-400' : 'text-gray-300'"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Répartition par âge</h3>
        <div class="space-y-3">
          <div
            v-for="age in ageDistribution"
            :key="age.range"
            class="flex items-center justify-between"
          >
            <span class="text-sm text-gray-700">{{ age.range }}</span>
            <div class="flex items-center gap-3 flex-1 ml-4">
              <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div :class="age.color" class="h-full rounded-full" :style="{ width: age.percentage + '%' }"></div>
              </div>
              <span class="text-sm font-semibold text-gray-900 w-12">{{ age.count }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Ancienneté</h3>
        <div class="space-y-3">
          <div
            v-for="seniority in seniorityDistribution"
            :key="seniority.range"
            class="flex items-center justify-between"
          >
            <span class="text-sm text-gray-700">{{ seniority.range }}</span>
            <div class="flex items-center gap-3 flex-1 ml-4">
              <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div :class="seniority.color" class="h-full rounded-full" :style="{ width: seniority.percentage + '%' }"></div>
              </div>
              <span class="text-sm font-semibold text-gray-900 w-12">{{ seniority.count }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Niveau d'études</h3>
        <div class="space-y-3">
          <div
            v-for="edu in educationLevel"
            :key="edu.level"
            class="flex items-center justify-between"
          >
            <span class="text-sm text-gray-700">{{ edu.level }}</span>
            <div class="flex items-center gap-3 flex-1 ml-4">
              <div class="flex-1 bg-gray-100 rounded-full h-2">
                <div :class="edu.color" class="h-full rounded-full" :style="{ width: edu.percentage + '%' }"></div>
              </div>
              <span class="text-sm font-semibold text-gray-900 w-12">{{ edu.count }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const reportTypes = [
  { id: 1, title: 'Rapport RH Global', description: 'Vue d\'ensemble complète des ressources humaines', lastGenerated: 'Il y a 2 jours', iconBg: 'bg-blue-500' },
  { id: 2, title: 'Rapport de Paie', description: 'Analyse détaillée de la masse salariale', lastGenerated: 'Il y a 1 semaine', iconBg: 'bg-green-500' },
  { id: 3, title: 'Rapport de Présence', description: 'Statistiques d\'assiduité et retards', lastGenerated: 'Il y a 3 jours', iconBg: 'bg-orange-500' },
  { id: 4, title: 'Rapport de Congés', description: 'Analyse des absences et congés', lastGenerated: 'Il y a 5 jours', iconBg: 'bg-primary-500' },
  { id: 5, title: 'Rapport Turnover', description: 'Analyse du roulement du personnel', lastGenerated: 'Il y a 1 mois', iconBg: 'bg-red-500' },
  { id: 6, title: 'Rapport Performance', description: 'Évaluation des performances par équipe', lastGenerated: 'Il y a 2 semaines', iconBg: 'bg-teal-500' }
]

const employeeGrowth = [
  { month: 'Mai', value: 1180, height: 85 },
  { month: 'Juin', value: 1195, height: 88 },
  { month: 'Juil', value: 1210, height: 90 },
  { month: 'Août', value: 1225, height: 92 },
  { month: 'Sept', value: 1235, height: 94 },
  { month: 'Oct', value: 1247, height: 96 }
]

const attendanceRate = [
  { month: 'Mai', rate: 94, rateClass: 'text-green-600', barColor: 'bg-green-500' },
  { month: 'Juin', rate: 96, rateClass: 'text-green-600', barColor: 'bg-green-500' },
  { month: 'Juillet', rate: 93, rateClass: 'text-green-600', barColor: 'bg-green-500' },
  { month: 'Août', rate: 95, rateClass: 'text-green-600', barColor: 'bg-green-500' },
  { month: 'Septembre', rate: 97, rateClass: 'text-green-600', barColor: 'bg-green-500' },
  { month: 'Octobre', rate: 95, rateClass: 'text-green-600', barColor: 'bg-green-500' }
]

const departmentAnalysis = [
  { id: 1, name: 'Administration', headcount: 245, budget: '18.5M', attendance: 96, attendanceColor: 'bg-green-500', turnover: 3.2, turnoverClass: 'bg-green-100 text-green-700', performance: 4, iconBg: 'bg-blue-500' },
  { id: 2, name: 'Finance', headcount: 189, budget: '22.3M', attendance: 97, attendanceColor: 'bg-green-500', turnover: 2.8, turnoverClass: 'bg-green-100 text-green-700', performance: 5, iconBg: 'bg-green-500' },
  { id: 3, name: 'RH', headcount: 156, budget: '15.8M', attendance: 95, attendanceColor: 'bg-green-500', turnover: 4.1, turnoverClass: 'bg-orange-100 text-orange-700', performance: 4, iconBg: 'bg-primary-500' },
  { id: 4, name: 'Technique', headcount: 298, budget: '28.9M', attendance: 94, attendanceColor: 'bg-green-500', turnover: 5.5, turnoverClass: 'bg-orange-100 text-orange-700', performance: 4, iconBg: 'bg-orange-500' },
  { id: 5, name: 'Support', headcount: 142, budget: '12.4M', attendance: 93, attendanceColor: 'bg-green-500', turnover: 6.2, turnoverClass: 'bg-red-100 text-red-700', performance: 3, iconBg: 'bg-red-500' }
]

const ageDistribution = [
  { range: '18-25 ans', count: 145, percentage: 70, color: 'bg-blue-500' },
  { range: '26-35 ans', count: 487, percentage: 95, color: 'bg-green-500' },
  { range: '36-45 ans', count: 398, percentage: 82, color: 'bg-orange-500' },
  { range: '46-55 ans', count: 172, percentage: 60, color: 'bg-red-500' },
  { range: '56+ ans', count: 45, percentage: 30, color: 'bg-gray-500' }
]

const seniorityDistribution = [
  { range: '< 1 an', count: 156, percentage: 65, color: 'bg-blue-500' },
  { range: '1-3 ans', count: 398, percentage: 88, color: 'bg-green-500' },
  { range: '3-5 ans', count: 342, percentage: 75, color: 'bg-orange-500' },
  { range: '5-10 ans', count: 245, percentage: 60, color: 'bg-red-500' },
  { range: '10+ ans', count: 106, percentage: 40, color: 'bg-gray-500' }
]

const educationLevel = [
  { level: 'Doctorat', count: 23, percentage: 25, color: 'bg-red-500' },
  { level: 'Master', count: 287, percentage: 75, color: 'bg-orange-500' },
  { level: 'Licence', count: 542, percentage: 95, color: 'bg-green-500' },
  { level: 'Bac+2', count: 298, percentage: 70, color: 'bg-blue-500' },
  { level: 'Bac', count: 97, percentage: 40, color: 'bg-gray-500' }
]
</script>
