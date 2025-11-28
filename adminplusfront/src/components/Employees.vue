<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Gestion du Personnel</h2>
        <p class="text-sm text-gray-500 mt-1">
          Gérez toutes les informations de vos collaborateurs
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-semibold shadow-lg flex items-center gap-2"
      >
        <svg
          class="w-5 h-5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
          />
        </svg>
        Ajouter un collaborateur
      </button>
    </div>

    <!-- Message de succès -->
    <div
      v-if="successMessage"
      class="bg-green-50 border-2 border-green-200 rounded-lg p-4 flex items-center gap-3"
    >
      <svg
        class="w-6 h-6 text-green-600"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      <p class="text-green-700 font-semibold">{{ successMessage }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Liste des collaborateurs -->
      <div
        class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-200"
      >
        <div class="p-6 border-b border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-4">Collaborateurs</h3>

          <div class="space-y-3">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher..."
                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
              />
              <svg
                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
              </svg>
            </div>

            <select
              v-model="statusFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
            >
              <option value="">Tous les statuts</option>
              <option value="actif">Actif</option>
              <option value="en_conge">En congé</option>
              <option value="inactif">Inactif</option>
            </select>

            <button
              @click="loadEmployees"
              class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium flex items-center justify-center gap-2"
            >
              <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                />
              </svg>
              Actualiser
            </button>
          </div>
        </div>

        <!-- Liste -->
        <div v-if="loading" class="p-12 flex justify-center">
          <div class="text-center">
            <svg
              class="animate-spin h-12 w-12 text-blue-600 mx-auto mb-4"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                class="opacity-25"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                stroke-width="4"
              ></circle>
              <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
            <p class="text-gray-600">Chargement...</p>
          </div>
        </div>

        <div v-else class="overflow-y-auto max-h-[calc(100vh-300px)]">
          <div
            v-for="employee in filteredEmployees"
            :key="employee.id"
            @click="selectEmployee(employee)"
            :class="[
              'p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors',
              selectedEmployee?.id === employee.id
                ? 'bg-blue-50 border-l-4 border-l-blue-600'
                : '',
            ]"
          >
            <div class="flex items-center gap-3">
              <div
                class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold"
                :class="employee.avatarColor"
              >
                {{ employee.initials }}
              </div>
              <div class="flex-1 min-w-0">
                <p class="font-semibold text-gray-900 truncate">
                  {{ employee.name }}
                </p>
                <p class="text-sm text-gray-600 truncate">
                  {{ employee.position }}
                </p>
                <span
                  class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold mt-1"
                  :class="employee.statusClass"
                >
                  {{ employee.status }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Détails du collaborateur -->
      <div
        class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200"
      >
        <div v-if="!selectedEmployee" class="p-12 text-center">
          <svg
            class="w-24 h-24 text-gray-300 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
            />
          </svg>
          <p class="text-xl font-semibold text-gray-700 mb-2">
            Aucun collaborateur sélectionné
          </p>
          <p class="text-gray-500">
            Sélectionnez un collaborateur pour voir ses détails
          </p>
        </div>

        <div v-else class="p-6">
          <!-- En-tête -->
          <div
            class="flex items-start justify-between mb-6 pb-6 border-b border-gray-200"
          >
            <div class="flex items-center gap-4">
              <div
                class="w-20 h-20 rounded-full flex items-center justify-center text-white font-bold text-2xl"
                :class="selectedEmployee.avatarColor"
              >
                {{ selectedEmployee.initials }}
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-900">
                  {{ selectedEmployee.name }}
                </h2>
                <p class="text-gray-600">{{ selectedEmployee.position }}</p>
                <span
                  class="inline-block px-3 py-1 rounded-full text-sm font-semibold mt-2"
                  :class="selectedEmployee.statusClass"
                >
                  {{ selectedEmployee.status }}
                </span>
              </div>
            </div>
            <button
              @click="openEditModal(selectedEmployee)"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
            >
              <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                />
              </svg>
              Modifier
            </button>
          </div>

          <!-- Sections -->
          <div class="space-y-4">
            <!-- Données Publiques -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('public')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900"
                    >Données Publiques</span
                  >
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.public ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.public"
                class="p-4 border-t border-gray-200 grid grid-cols-2 gap-4"
              >
                <div>
                  <p class="text-sm text-gray-600">Email</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.email }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Téléphone</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.phone }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Département</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.departement || "N/A" }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Date d'embauche</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.date_embauche || "N/A" }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Informations RH -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('rh')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-green-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900"
                    >Informations RH</span
                  >
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.rh ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.rh"
                class="p-4 border-t border-gray-200 grid grid-cols-2 gap-4"
              >
                <div>
                  <p class="text-sm text-gray-600">Salaire de base</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.salary }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Date de naissance</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.date_naissance || "N/A" }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Situation familiale</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.situation_familiale || "N/A" }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Nombre d'enfants</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.nombre_enfants || 0 }}
                  </p>
                </div>
                <div class="col-span-2">
                  <p class="text-sm text-gray-600">Adresse</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.adresse || "N/A" }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Parcours Professionnel -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('parcours')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-purple-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900"
                    >Parcours Professionnel</span
                  >
                  <span
                    class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold"
                  >
                    {{ parcoursData.length }}
                  </span>
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.parcours ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.parcours"
                class="p-4 border-t border-gray-200"
              >
                <div v-if="parcoursData.length > 0" class="space-y-3">
                  <div
                    v-for="parcours in parcoursData"
                    :key="parcours.id"
                    class="p-4 bg-gray-50 rounded-lg border border-gray-200"
                  >
                    <div class="flex items-center gap-2 mb-2">
                      <span
                        class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-semibold"
                      >
                        {{ parcours.type_mouvement }}
                      </span>
                      <span class="text-sm text-gray-600">{{
                        parcours.date
                      }}</span>
                    </div>
                    <p class="font-semibold text-gray-900">
                      {{ parcours.poste }}
                    </p>
                    <p class="text-sm text-gray-600">
                      {{ parcours.departement }}
                    </p>
                  </div>
                </div>
                <div v-else class="text-center py-4 text-gray-500">
                  Aucun mouvement enregistré par le collaborateur
                </div>
              </div>
            </div>

            <!-- Formations - LECTURE SEULE -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('formations')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                      d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900">Formations</span>
                  <span
                    class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold"
                  >
                    {{ formationsData.length }}
                  </span>
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.formations ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.formations"
                class="p-4 border-t border-gray-200"
              >
                <div v-if="formationsData.length > 0" class="space-y-3">
                  <div
                    v-for="formation in formationsData"
                    :key="formation.id"
                    class="p-4 bg-gray-50 rounded-lg border border-gray-200"
                  >
                    <div class="flex items-center gap-2 mb-2">
                      <p class="font-semibold text-gray-900">
                        {{ formation.titre }}
                      </p>
                      <span
                        v-if="formation.certifie"
                        class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold"
                      >
                        Certifié
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                      {{ formation.organisme }}
                    </p>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                      <div>
                        <p class="text-gray-500">Période</p>
                        <p class="font-semibold text-gray-700">
                          {{ formation.periode }}
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Durée</p>
                        <p class="font-semibold text-gray-700">
                          {{ formation.duree }}h
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Coût</p>
                        <p class="font-semibold text-gray-700">
                          {{ formation.cout }} FCFA
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-4 text-gray-500">
                  Aucune formation enregistrée par le collaborateur
                </div>
              </div>
            </div>

            <!-- Entretiens Annuels -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('entretiens')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-orange-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900"
                    >Entretiens Annuels</span
                  >
                  <span
                    class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold"
                  >
                    {{ entretiensData.length }}
                  </span>
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.entretiens ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.entretiens"
                class="p-4 border-t border-gray-200"
              >
                <button
                  @click="openEntretienModal"
                  class="mb-4 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 flex items-center gap-2"
                >
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                  Nouvel entretien
                </button>
                <div class="space-y-3">
                  <div
                    v-for="entretien in entretiensData"
                    :key="entretien.id"
                    class="p-4 bg-gray-50 rounded-lg border border-gray-200"
                  >
                    <p class="font-semibold text-gray-900 mb-2">
                      Entretien du {{ entretien.date }}
                    </p>
                    <p class="text-sm text-gray-600 mb-3">
                      Évaluateur: {{ entretien.evaluateur }}
                    </p>
                    <div class="grid grid-cols-3 gap-3">
                      <div class="text-center p-2 bg-white rounded">
                        <p class="text-2xl font-bold text-blue-600">
                          {{ entretien.objectifs }}%
                        </p>
                        <p class="text-xs text-gray-600">Objectifs</p>
                      </div>
                      <div class="text-center p-2 bg-white rounded">
                        <p class="text-2xl font-bold text-green-600">
                          {{ entretien.technique }}%
                        </p>
                        <p class="text-xs text-gray-600">Technique</p>
                      </div>
                      <div class="text-center p-2 bg-white rounded">
                        <p class="text-2xl font-bold text-purple-600">
                          {{ entretien.comportement }}%
                        </p>
                        <p class="text-xs text-gray-600">Comportement</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Formations
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('formations')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                      d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900">Formations</span>
                  <span
                    class="px-2 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold"
                  >
                    {{ formationsData.length }}
                  </span>
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.formations ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.formations"
                class="p-4 border-t border-gray-200"
              >
                <button
                  @click="openFormationModal"
                  class="mb-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2"
                >
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                  Ajouter une formation
                </button>
                <div class="space-y-3">
                  <div
                    v-for="formation in formationsData"
                    :key="formation.id"
                    class="p-4 bg-gray-50 rounded-lg border border-gray-200"
                  >
                    <div class="flex items-center gap-2 mb-2">
                      <p class="font-semibold text-gray-900">
                        {{ formation.titre }}
                      </p>
                      <span
                        v-if="formation.certifie"
                        class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-semibold"
                      >
                        Certifié
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                      {{ formation.organisme }}
                    </p>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                      <div>
                        <p class="text-gray-500">Période</p>
                        <p class="font-semibold text-gray-700">
                          {{ formation.periode }}
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Durée</p>
                        <p class="font-semibold text-gray-700">
                          {{ formation.duree }}h
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Coût</p>
                        <p class="font-semibold text-gray-700">
                          {{ formation.cout }} FCFA
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->

            <!-- Contrats -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('contrats')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-red-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900"
                    >Informations Contractuelles</span
                  >
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.contrats ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.contrats"
                class="p-4 border-t border-gray-200 grid grid-cols-2 gap-4"
              >
                <div>
                  <p class="text-sm text-gray-600">Type de contrat</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.type_contrat || "N/A" }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Durée du contrat</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.duree_contrat || "Indéterminée" }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Date de fin</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.date_fin_contrat || "N/A" }}
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Heures hebdomadaires</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.heures_travail || "N/A" }}h
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">Jours de congés annuels</p>
                  <p class="font-semibold text-gray-900">
                    {{ selectedEmployee.jours_conges || 0 }} jours
                  </p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">IBAN</p>
                  <p class="font-semibold text-gray-900 text-xs">
                    {{ selectedEmployee.iban || "N/A" }}
                  </p>
                </div>
              </div>
            </div>

            <!-- Équipements -->
            <div class="border border-gray-200 rounded-lg">
              <button
                @click="toggleSection('equipements')"
                class="w-full flex items-center justify-between p-4 hover:bg-gray-50"
              >
                <div class="flex items-center gap-3">
                  <svg
                    class="w-5 h-5 text-teal-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                    />
                  </svg>
                  <span class="font-semibold text-gray-900"
                    >Équipements Fournis</span
                  >
                  <span
                    class="px-2 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold"
                  >
                    {{ equipementsData.length }}
                  </span>
                </div>
                <svg
                  class="w-5 h-5"
                  :class="expandedSections.equipements ? 'rotate-180' : ''"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                  />
                </svg>
              </button>
              <div
                v-if="expandedSections.equipements"
                class="p-4 border-t border-gray-200"
              >
                <button
                  @click="openEquipementModal"
                  class="mb-4 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 flex items-center gap-2"
                >
                  <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                  Attribuer un équipement
                </button>
                <div class="space-y-3">
                  <div
                    v-for="equipement in equipementsData"
                    :key="equipement.id"
                    class="p-4 bg-gray-50 rounded-lg border border-gray-200"
                  >
                    <div class="flex items-center gap-2 mb-2">
                      <p class="font-semibold text-gray-900">
                        {{ equipement.type }}
                      </p>
                      <span
                        class="px-2 py-0.5 rounded text-xs font-semibold"
                        :class="
                          equipement.etat === 'Bon état'
                            ? 'bg-green-100 text-green-700'
                            : equipement.etat === 'Moyen'
                            ? 'bg-orange-100 text-orange-700'
                            : 'bg-red-100 text-red-700'
                        "
                      >
                        {{ equipement.etat }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">
                      {{ equipement.marque }} {{ equipement.modele }}
                    </p>
                    <div class="grid grid-cols-3 gap-3 text-sm">
                      <div>
                        <p class="text-gray-500">N° Série</p>
                        <p class="font-semibold text-gray-700 text-xs">
                          {{ equipement.numero_serie }}
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Attribution</p>
                        <p class="font-semibold text-gray-700">
                          {{ equipement.date_attribution }}
                        </p>
                      </div>
                      <div>
                        <p class="text-gray-500">Valeur</p>
                        <p class="font-semibold text-gray-700">
                          {{ equipement.valeur }} FCFA
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <CollaboratorModal
      v-if="showModal"
      :show="showModal"
      :collaborator="selectedEmployeeForEdit"
      @close="closeModal"
      @submit="handleSubmit"
    />

    <ConfirmModal
      v-if="showDeleteModal"
      :show="showDeleteModal"
      title="Supprimer l'employé"
      :message="`Êtes-vous sûr de vouloir supprimer cet employé ? Cette action est irréversible.`"
      confirm-text="Oui, supprimer"
      cancel-text="Annuler"
      @confirm="handleDelete"
      @cancel="showDeleteModal = false"
    />

    <!-- Modal Parcours -->
    <div
      v-if="showParcoursModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
        <div
          class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
        >
          <h3 class="text-xl font-bold text-gray-900">Ajouter un mouvement</h3>
          <button
            @click="showParcoursModal = false"
            class="p-2 hover:bg-gray-100 rounded-lg"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Type de mouvement *</label
              >
              <select
                v-model="parcoursForm.type_mouvement"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              >
                <option value="">Sélectionner</option>
                <option value="Embauche">Embauche</option>
                <option value="Promotion">Promotion</option>
                <option value="Mutation">Mutation</option>
                <option value="Changement de poste">Changement de poste</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Date de début *</label
              >
              <input
                type="date"
                v-model="parcoursForm.date_debut"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Poste *</label
              >
              <input
                type="text"
                v-model="parcoursForm.poste"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Développeur Senior"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Département *</label
              >
              <input
                type="text"
                v-model="parcoursForm.departement"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="IT"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Notes</label
            >
            <textarea
              v-model="parcoursForm.notes"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Informations complémentaires..."
            ></textarea>
          </div>
        </div>
        <div
          class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3"
        >
          <button
            @click="showParcoursModal = false"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium"
          >
            Annuler
          </button>
          <button
            @click="saveParcours"
            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold"
          >
            Enregistrer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Entretien -->
    <div
      v-if="showEntretienModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div
        class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
      >
        <div
          class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 flex items-center justify-between"
        >
          <h3 class="text-xl font-bold text-gray-900">
            Nouvel entretien annuel
          </h3>
          <button
            @click="showEntretienModal = false"
            class="p-2 hover:bg-gray-100 rounded-lg"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Date de l'entretien *</label
              >
              <input
                type="date"
                v-model="entretienForm.date_entretien"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Évaluateur *</label
              >
              <input
                type="text"
                v-model="entretienForm.evaluateur"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Sophie Leblanc"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Objectifs atteints (%) *</label
              >
              <input
                type="number"
                min="0"
                max="100"
                v-model="entretienForm.objectifs_atteints"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Compétences techniques (%) *</label
              >
              <input
                type="number"
                min="0"
                max="100"
                v-model="entretienForm.competences_techniques"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>

            <div class="col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Compétences comportementales (%) *</label
              >
              <input
                type="number"
                min="0"
                max="100"
                v-model="entretienForm.competences_comportementales"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Notes RH</label
              >
              <input
                type="number"
                min="0"
                max="100"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Points forts *</label
            >
            <textarea
              v-model="entretienForm.points_forts"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Listez les points forts..."
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Axes d'amélioration *</label
            >
            <textarea
              v-model="entretienForm.axes_amelioration"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Listez les axes d'amélioration..."
            ></textarea>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Objectifs futurs *</label
            >
            <textarea
              v-model="entretienForm.objectifs_futurs"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Définissez les objectifs..."
            ></textarea>
          </div>
        </div>
        <div
          class="sticky bottom-0 bg-white px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3"
        >
          <button
            @click="showEntretienModal = false"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium"
          >
            Annuler
          </button>
          <button
            @click="saveEntretien"
            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-semibold"
          >
            Enregistrer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Formation -->
    <div
      v-if="showFormationModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
        <div
          class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
        >
          <h3 class="text-xl font-bold text-gray-900">Ajouter une formation</h3>
          <button
            @click="showFormationModal = false"
            class="p-2 hover:bg-gray-100 rounded-lg"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Titre de la formation *</label
              >
              <input
                type="text"
                v-model="formationForm.titre"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="React Advanced"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Organisme *</label
              >
              <input
                type="text"
                v-model="formationForm.organisme"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Udemy"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Durée (heures) *</label
              >
              <input
                type="number"
                v-model="formationForm.duree_heures"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="40"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Date de début *</label
              >
              <input
                type="date"
                v-model="formationForm.date_debut"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Date de fin *</label
              >
              <input
                type="date"
                v-model="formationForm.date_fin"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div class="col-span-2">
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Coût (FCFA)</label
              >
              <input
                type="number"
                v-model="formationForm.cout"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="150000"
              />
            </div>
            <div class="col-span-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  v-model="formationForm.certification_obtenue"
                  class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
                <span class="text-sm font-semibold text-gray-700"
                  >Certification obtenue</span
                >
              </label>
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Notes</label
            >
            <textarea
              v-model="formationForm.notes"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Informations complémentaires..."
            ></textarea>
          </div>
        </div>
        <div
          class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3"
        >
          <button
            @click="showFormationModal = false"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium"
          >
            Annuler
          </button>
          <button
            @click="saveFormation"
            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold"
          >
            Enregistrer
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Équipement -->
    <div
      v-if="showEquipementModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
    >
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
        <div
          class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
        >
          <h3 class="text-xl font-bold text-gray-900">
            Attribuer un équipement
          </h3>
          <button
            @click="showEquipementModal = false"
            class="p-2 hover:bg-gray-100 rounded-lg"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
        <div class="p-6 space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Type d'équipement *</label
              >
              <select
                v-model="equipementForm.type_equipement"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              >
                <option value="">Sélectionner</option>
                <option value="Ordinateur portable">Ordinateur portable</option>
                <option value="Ordinateur de bureau">
                  Ordinateur de bureau
                </option>
                <option value="Téléphone portable">Téléphone portable</option>
                <option value="Tablette">Tablette</option>
                <option value="Écran">Écran</option>
                <option value="Clavier">Clavier</option>
                <option value="Souris">Souris</option>
                <option value="Casque">Casque</option>
                <option value="Imprimante">Imprimante</option>
                <option value="Autre">Autre</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Marque *</label
              >
              <input
                type="text"
                v-model="equipementForm.marque"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Dell"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Modèle *</label
              >
              <input
                type="text"
                v-model="equipementForm.modele"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="XPS 15"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Numéro de série *</label
              >
              <input
                type="text"
                v-model="equipementForm.numero_serie"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="DL123456789"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Date d'attribution *</label
              >
              <input
                type="date"
                v-model="equipementForm.date_attribution"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >État *</label
              >
              <select
                v-model="equipementForm.etat"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              >
                <option value="">Sélectionner</option>
                <option value="Neuf">Neuf</option>
                <option value="Bon état">Bon état</option>
                <option value="Moyen">Moyen</option>
                <option value="Usagé">Usagé</option>
                <option value="À réparer">À réparer</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Valeur (FCFA)</label
              >
              <input
                type="number"
                v-model="equipementForm.valeur"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="1200000"
              />
            </div>
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Date de retour</label
              >
              <input
                type="date"
                v-model="equipementForm.date_retour"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2"
              >Notes</label
            >
            <textarea
              v-model="equipementForm.notes"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              placeholder="Informations complémentaires..."
            ></textarea>
          </div>
        </div>
        <div
          class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3"
        >
          <button
            @click="showEquipementModal = false"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium"
          >
            Annuler
          </button>
          <button
            @click="saveEquipement"
            class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold"
          >
            Enregistrer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import CollaboratorModal from "../components/CollaboratorModal.vue";
import ConfirmModal from "../components/ConfirmModal.vue";
import api from "../services/api/axios";

interface DisplayEmployee {
  id: number;
  name: string;
  email: string;
  position: string;
  phone: string;
  salary: string;
  status: string;
  statusClass: string;
  initials: string;
  avatarColor: string;
  departement?: string;
  date_embauche?: string;
  date_naissance?: string;
  situation_familiale?: string;
  nombre_enfants?: number;
  adresse?: string;
  type_contrat?: string;
  duree_contrat?: string;
  date_fin_contrat?: string;
  heures_travail?: number;
  jours_conges?: number;
  iban?: string;
}

const employees = ref<DisplayEmployee[]>([]);
const loading = ref(false);
const showModal = ref(false);
const showDeleteModal = ref(false);
const selectedEmployee = ref<DisplayEmployee | null>(null);
const selectedEmployeeForEdit = ref<any>(null);
const employeeToDelete = ref<DisplayEmployee | null>(null);
const successMessage = ref("");
const searchQuery = ref("");
const statusFilter = ref("");

// Sections expandables
const expandedSections = ref({
  public: true,
  rh: false,
  parcours: false,
  entretiens: false,
  formations: false,
  contrats: false,
  equipements: false,
});

// Données supplémentaires
const parcoursData = ref<any[]>([]);
const entretiensData = ref<any[]>([]);
const formationsData = ref<any[]>([]);
const equipementsData = ref<any[]>([]);

// Modals
const showParcoursModal = ref(false);
const showEntretienModal = ref(false);
const showFormationModal = ref(false);
const showEquipementModal = ref(false);

// Forms
const parcoursForm = ref({
  type_mouvement: "",
  date_debut: "",
  poste: "",
  departement: "",
  notes: "",
});

const entretienForm = ref({
  date_entretien: "",
  evaluateur: "",
  objectifs_atteints: 0,
  competences_techniques: 0,
  competences_comportementales: 0,
  points_forts: "",
  axes_amelioration: "",
  objectifs_futurs: "",
});

const formationForm = ref({
  titre: "",
  organisme: "",
  date_debut: "",
  date_fin: "",
  duree_heures: 0,
  cout: 0,
  certification_obtenue: false,
  notes: "",
});

const equipementForm = ref({
  type_equipement: "",
  marque: "",
  modele: "",
  numero_serie: "",
  date_attribution: "",
  date_retour: "",
  etat: "",
  valeur: 0,
  notes: "",
});

const filteredEmployees = computed(() => {
  let result = employees.value;

  if (searchQuery.value.trim()) {
    const query = searchQuery.value.toLowerCase().trim();
    result = result.filter(
      (employee) =>
        employee.name.toLowerCase().includes(query) ||
        employee.email.toLowerCase().includes(query) ||
        employee.position.toLowerCase().includes(query) ||
        employee.phone.toLowerCase().includes(query)
    );
  }

  if (statusFilter.value) {
    result = result.filter(
      (employee) =>
        employee.status.toLowerCase() === statusFilter.value.toLowerCase()
    );
  }

  return result;
});

onMounted(() => {
  loadEmployees();
});

const loadEmployees = async () => {
  loading.value = true;
  try {
    const response = await api.get("/collaborators");
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    employees.value = data.map((collab: any) => ({
      id: collab.id,
      name: collab.nom_complet || "Non défini",
      email: collab.email || "Non défini",
      position: collab.poste || "Non défini",
      phone: collab.telephone || "N/A",
      salary: collab.salaire_base || "0 FCFA",
      status: collab.statut || "actif",
      statusClass:
        collab.statut === "actif"
          ? "bg-green-100 text-green-700"
          : collab.statut === "en_conge"
          ? "bg-orange-100 text-orange-700"
          : "bg-red-100 text-red-700",
      initials: getInitials(collab.nom_complet || "NA"),
      avatarColor: getRandomColor(),
      departement: collab.departement,
      date_embauche: collab.date_embauche,
      date_naissance: collab.date_naissance,
      situation_familiale: collab.situation_familiale,
      nombre_enfants: collab.nombre_enfants,
      adresse: collab.adresse,
      type_contrat: collab.type_contrat,
      duree_contrat: collab.duree_contrat,
      date_fin_contrat: collab.date_fin_contrat,
      heures_travail: collab.heures_travail,
      jours_conges: collab.jours_conges,
      iban: collab.iban,
    }));
  } catch (error) {
    console.error("Erreur:", error);
  } finally {
    loading.value = false;
  }
};

const selectEmployee = async (employee: DisplayEmployee) => {
  selectedEmployee.value = employee;
  await loadEmployeeDetails(employee.id);
};

const loadEmployeeDetails = async (employeeId: number) => {
  try {
    // Charger le parcours professionnel
    const parcoursResponse = await api.get(
      `/collaborators/${employeeId}/parcours`
    );
    parcoursData.value = parcoursResponse.data.map((p: any) => ({
      id: p.id,
      type_mouvement: p.type_mouvement,
      date: new Date(p.date_debut).toLocaleDateString("fr-FR"),
      poste: p.poste,
      departement: p.departement,
    }));

    // Charger les entretiens
    const entretiensResponse = await api.get(
      `/collaborators/${employeeId}/entretiens`
    );
    entretiensData.value = entretiensResponse.data.map((e: any) => ({
      id: e.id,
      date: new Date(e.date_entretien).toLocaleDateString("fr-FR"),
      evaluateur: e.evaluateur,
      objectifs: e.objectifs_atteints,
      technique: e.competences_techniques,
      comportement: e.competences_comportementales,
    }));

    // Charger les formations
    const formationsResponse = await api.get(
      `/collaborators/${employeeId}/formations`
    );
    formationsData.value = formationsResponse.data.map((f: any) => ({
      id: f.id,
      titre: f.titre,
      organisme: f.organisme,
      periode: `${new Date(f.date_debut).toLocaleDateString(
        "fr-FR"
      )} - ${new Date(f.date_fin).toLocaleDateString("fr-FR")}`,
      duree: f.duree_heures,
      cout: f.cout || 0,
      certifie: f.certification_obtenue,
    }));

    // Charger les équipements
    const equipementsResponse = await api.get(
      `/collaborators/${employeeId}/equipements`
    );
    equipementsData.value = equipementsResponse.data.map((eq: any) => ({
      id: eq.id,
      type: eq.type_equipement,
      marque: eq.marque,
      modele: eq.modele,
      numero_serie: eq.numero_serie,
      date_attribution: new Date(eq.date_attribution).toLocaleDateString(
        "fr-FR"
      ),
      etat: eq.etat,
      valeur: eq.valeur || 0,
    }));
  } catch (error) {
    console.error("Erreur lors du chargement des détails:", error);
    // Utiliser des données vides en cas d'erreur
    parcoursData.value = [];
    entretiensData.value = [];
    formationsData.value = [];
    equipementsData.value = [];
  }
};

const toggleSection = (section: string) => {
  expandedSections.value[section] = !expandedSections.value[section];
};

const openCreateModal = () => {
  selectedEmployeeForEdit.value = null;
  showModal.value = true;
};

const openEditModal = (employee: DisplayEmployee) => {
  selectedEmployeeForEdit.value = {
    id: employee.id,
    fullname: employee.name,
    email: employee.email,
    poste: employee.position,
    telephone: employee.phone,
    salaire_base: parseFloat(employee.salary.replace(/[^\d]/g, "")),
    date_embauche:
      employee.date_embauche || new Date().toISOString().split("T")[0],
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedEmployeeForEdit.value = null;
};

const handleSubmit = async (data: any, uploadCallback?: Function) => {
  console.log("🔵 handleSubmit appelé", {
    data,
    hasCallback: !!uploadCallback,
    hasContrat: data.hasContrat,
  });

  try {
    let collaboratorId: number | undefined;

    if (data.isEdit && selectedEmployeeForEdit.value?.id) {
      // Préparer les données pour la mise à jour
      const updateData = {
        nom_complet: data.fullname,
        email: data.email,
        telephone: data.telephone || data.tel,
        poste: data.poste,
        salaire_base: data.salaire_base,
        date_embauche: data.date_embauche,
        date_naissance: data.date_naissance,
        genre: data.genre,
        adresse: data.adresse,
        departement: data.departement,
        situation_familiale: data.situation_familiale,
        nombre_enfants: data.nombre_enfants,
        type_contrat: data.type_contrat,
        duree_contrat: data.duree_contrat,
        date_fin_contrat: data.date_fin_contrat,
        heures_travail: data.heures_travail,
        jours_conges: data.jours_conges,
        iban: data.iban,
        statut: data.statut,
      };

      await api.put(
        `/collaborators/${selectedEmployeeForEdit.value.id}`,
        updateData
      );
      collaboratorId = selectedEmployeeForEdit.value.id;
      successMessage.value = "Employé modifié avec succès !";
      console.log("✅ Employé modifié, ID:", collaboratorId);
    } else {
      console.log("⚠️ Pas d'upload:", {
        hasCallback: !!uploadCallback,
        hasContrat: data.hasContrat,
        hasCollaboratorId: !!collaboratorId,
      });
    }

    await loadEmployees();
    closeModal();

    setTimeout(() => {
      successMessage.value = "";
    }, 5000);
  } catch (error: any) {
    console.error("❌ Erreur handleSubmit:", error);
    alert(error.response?.data?.message || "Erreur lors de l'opération");
  }
};

const confirmDelete = (employee: DisplayEmployee) => {
  employeeToDelete.value = employee;
  showDeleteModal.value = true;
};

const handleDelete = async () => {
  if (employeeToDelete.value?.id) {
    try {
      await api.delete(`/collaborators/${employeeToDelete.value.id}`);
      successMessage.value = "Employé supprimé avec succès !";
      await loadEmployees();

      setTimeout(() => {
        successMessage.value = "";
      }, 3000);
    } catch (error) {
      console.error("Erreur:", error);
      alert("Erreur lors de la suppression");
    }
  }
  showDeleteModal.value = false;
  employeeToDelete.value = null;
};

// Fonctions pour les modals supplémentaires
const openParcoursModal = () => {
  parcoursForm.value = {
    type_mouvement: "",
    date_debut: "",
    poste: "",
    departement: "",
    notes: "",
  };
  showParcoursModal.value = true;
};

const saveParcours = async () => {
  try {
    await api.post(
      `/collaborators/${selectedEmployee.value?.id}/parcours`,
      parcoursForm.value
    );
    successMessage.value = "Parcours ajouté avec succès !";
    showParcoursModal.value = false;
    if (selectedEmployee.value) {
      await loadEmployeeDetails(selectedEmployee.value.id);
    }
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'ajout du parcours");
  }
};

const openEntretienModal = () => {
  entretienForm.value = {
    date_entretien: "",
    evaluateur: "",
    objectifs_atteints: 0,
    competences_techniques: 0,
    competences_comportementales: 0,
    points_forts: "",
    axes_amelioration: "",
    objectifs_futurs: "",
  };
  showEntretienModal.value = true;
};

const saveEntretien = async () => {
  try {
    await api.post(
      `/collaborators/${selectedEmployee.value?.id}/entretiens`,
      entretienForm.value
    );
    successMessage.value = "Entretien enregistré avec succès !";
    showEntretienModal.value = false;
    if (selectedEmployee.value) {
      await loadEmployeeDetails(selectedEmployee.value.id);
    }
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'enregistrement de l'entretien");
  }
};

const openFormationModal = () => {
  formationForm.value = {
    titre: "",
    organisme: "",
    date_debut: "",
    date_fin: "",
    duree_heures: 0,
    cout: 0,
    certification_obtenue: false,
    notes: "",
  };
  showFormationModal.value = true;
};

const saveFormation = async () => {
  try {
    await api.post(
      `/collaborators/${selectedEmployee.value?.id}/formations`,
      formationForm.value
    );
    successMessage.value = "Formation ajoutée avec succès !";
    showFormationModal.value = false;
    if (selectedEmployee.value) {
      await loadEmployeeDetails(selectedEmployee.value.id);
    }
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'ajout de la formation");
  }
};

const openEquipementModal = () => {
  equipementForm.value = {
    type_equipement: "",
    marque: "",
    modele: "",
    numero_serie: "",
    date_attribution: "",
    date_retour: "",
    etat: "",
    valeur: 0,
    notes: "",
  };
  showEquipementModal.value = true;
};

const saveEquipement = async () => {
  try {
    await api.post(
      `/collaborators/${selectedEmployee.value?.id}/equipements`,
      equipementForm.value
    );
    successMessage.value = "Équipement attribué avec succès !";
    showEquipementModal.value = false;
    if (selectedEmployee.value) {
      await loadEmployeeDetails(selectedEmployee.value.id);
    }
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'attribution de l'équipement");
  }
};

const getInitials = (name: string) => {
  if (!name || name === "Non défini") return "NA";
  const parts = name.split(" ");
  return parts
    .map((p) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
};

const getRandomColor = () => {
  const colors = [
    "bg-blue-500",
    "bg-green-500",
    "bg-purple-500",
    "bg-orange-500",
    "bg-red-500",
    "bg-teal-500",
    "bg-indigo-500",
    "bg-pink-500",
    "bg-cyan-500",
    "bg-lime-500",
  ];
  return colors[Math.floor(Math.random() * colors.length)];
};
</script>
