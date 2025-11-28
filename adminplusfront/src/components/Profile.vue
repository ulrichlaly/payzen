<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">Mon profil</h2>
        <p class="text-sm text-gray-500 mt-1">
          Gérez vos informations personnelles
        </p>
      </div>
    </div>

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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="xl:col-span-1 space-y-6">
        <div
          class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center"
        >
          <!-- Photo de profil avec option d'upload -->
          <div class="relative w-32 h-32 mx-auto mb-4">
            <div
              v-if="profile.photo_url"
              class="w-full h-full rounded-full overflow-hidden border-4 border-blue-100"
            >
              <img
                :src="profile.photo_url"
                :alt="profile.fullname"
                class="w-full h-full object-cover"
              />
            </div>
            <div
              v-else
              class="w-full h-full rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-5xl font-bold"
            >
              {{ userInitials }}
            </div>

            <!-- Bouton pour changer la photo -->
            <button
              @click="triggerPhotoUpload"
              type="button"
              class="absolute bottom-0 right-0 w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center shadow-lg transition-colors"
              title="Changer la photo"
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
                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
            </button>

            <!-- Input file caché -->
            <input
              ref="photoInput"
              type="file"
              accept="image/jpeg,image/png,image/jpg"
              @change="handlePhotoChange"
              class="hidden"
            />
          </div>

          <!-- Bouton pour supprimer la photo -->
          <button
            v-if="profile.photo_url"
            @click="removePhoto"
            type="button"
            class="text-xs text-red-600 hover:text-red-700 mb-4 underline"
          >
            Supprimer la photo
          </button>

          <h3 class="text-xl font-bold text-gray-900 mb-1">
            {{ profile.fullname }}
          </h3>
          <p class="text-sm text-gray-600 mb-3">{{ profile.poste }}</p>
          <span
            class="px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold"
          >
            {{ profile.statut || "Actif" }}
          </span>

          <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-left">
            <div class="flex items-center gap-3 text-sm">
              <svg
                class="w-5 h-5 text-gray-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                />
              </svg>
              <span class="text-gray-600">Matricule :</span>
              <span class="font-semibold text-gray-900">{{
                profile.matricule
              }}</span>
            </div>

            <div class="flex items-center gap-3 text-sm">
              <svg
                class="w-5 h-5 text-gray-400"
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
              <span class="text-gray-600">Embauche :</span>
              <span class="font-semibold text-gray-900">{{
                formatDate(profile.date_embauche)
              }}</span>
            </div>
          </div>
        </div>

        <div
          class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg"
        >
          <h4 class="text-sm font-semibold opacity-90 mb-4">
            📊 Mes statistiques
          </h4>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm">Ancienneté</span>
              <span class="font-bold text-lg">{{ anciennete }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm">Congés restants</span>
              <span class="font-bold text-lg">{{ soldeConges }} jours</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm">Bulletins reçus</span>
              <span class="font-bold text-lg">{{ bulletinsCount }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">
              Informations personnelles
            </h3>
            <button
              v-if="!editMode"
              @click="editMode = true"
              type="button"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm flex items-center gap-2"
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

          <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Nom complet</label
                >
                <input
                  v-model="formData.fullname"
                  type="text"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Email</label
                >
                <input
                  v-model="formData.email"
                  type="email"
                  disabled
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                />
                <p class="text-xs text-gray-500 mt-1">
                  L'email ne peut pas être modifié
                </p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Téléphone</label
                >
                <input
                  v-model="formData.tel"
                  type="tel"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Date de naissance</label
                >
                <input
                  v-model="formData.date_naissance"
                  type="date"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Genre</label
                >
                <select
                  v-model="formData.genre"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                >
                  <option value="">Sélectionner</option>
                  <option value="M">Masculin</option>
                  <option value="F">Féminin</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2"
                  >Adresse</label
                >
                <input
                  v-model="formData.adresse"
                  type="text"
                  :disabled="!editMode"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:text-gray-500"
                />
              </div>
            </div>

            <div class="pt-6 border-t border-gray-200">
              <h4 class="text-md font-bold text-gray-900 mb-4">
                Informations professionnelles
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2"
                    >Poste</label
                  >
                  <input
                    v-model="profile.poste"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2"
                    >Date d'embauche</label
                  >
                  <input
                    :value="formatDate(profile.date_embauche)"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2"
                    >Matricule</label
                  >
                  <input
                    v-model="profile.matricule"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2"
                    >Statut</label
                  >
                  <input
                    v-model="profile.statut"
                    type="text"
                    disabled
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                  />
                </div>
              </div>
            </div>

            <!-- 📄 NOUVEAU: Section Mon Contrat -->
            <div class="pt-6 border-t border-gray-200">
              <h4
                class="text-md font-bold text-gray-900 mb-4 flex items-center gap-2"
              >
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
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                  />
                </svg>

                Mon contrat de travail
              </h4>

              <div v-if="loadingContract" class="text-center py-4">
                <svg
                  class="animate-spin h-8 w-8 text-blue-600 mx-auto"
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
                <p class="text-gray-600 mt-2">Chargement...</p>
              </div>

              <div
                v-else-if="contract"
                class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4"
              >
                <div class="flex items-start gap-4">
                  <div class="flex-shrink-0">
                    <div
                      class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center"
                    >
                      <svg
                        class="w-6 h-6 text-white"
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
                    </div>
                  </div>
                  <div class="flex-1">
                    <h5 class="font-semibold text-gray-900 mb-1">
                      {{ contract.nom_fichier }}
                    </h5>
                    <p class="text-sm text-gray-600 mb-2">
                      {{ formatFileSize(contract.taille) }} •
                      {{ contract.extension.toUpperCase() }}
                    </p>
                    <p
                      v-if="contract.description"
                      class="text-sm text-gray-600 mb-3"
                    >
                      {{ contract.description }}
                    </p>
                    <div class="flex items-center gap-2">
                      <button
                        @click="downloadContract"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm flex items-center gap-2"
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
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                          />
                        </svg>
                        Télécharger
                      </button>
                      <span class="text-xs text-gray-500">
                        Ajouté le {{ formatDate(contract.created_at) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div
                v-else
                class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 text-center"
              >
                <svg
                  class="w-12 h-12 text-gray-400 mx-auto mb-3"
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
                <p class="text-gray-600 font-medium">
                  Aucun contrat disponible
                </p>
                <p class="text-sm text-gray-500 mt-1">
                  Veuillez contacter les ressources humaines
                </p>
              </div>
            </div>
            <!-- 📚 Parcours Professionnel -->
            <div
              class="bg-white rounded-xl p-6 shadow-sm border border-gray-100"
            >
              <div class="flex items-center justify-between mb-4">
                <h3
                  class="text-lg font-bold text-gray-900 flex items-center gap-2"
                >
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
                  Mon parcours professionnel
                </h3>
                <button
                  @click="openParcoursModal"
                  class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium text-sm flex items-center gap-2"
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
                  Ajouter un mouvement
                </button>
              </div>

              <div v-if="parcoursLoading" class="text-center py-4">
                <svg
                  class="animate-spin h-8 w-8 text-purple-600 mx-auto"
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
              </div>

              <div v-else-if="parcoursData.length > 0" class="space-y-3">
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
              <div
                v-else
                class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 text-center"
              >
                <p class="text-gray-600">Aucun mouvement enregistré</p>
              </div>
            </div>

            <!-- 📚 Formations -->
            <div
              class="bg-white rounded-xl p-6 shadow-sm border border-gray-100"
            >
              <div class="flex items-center justify-between mb-4">
                <h3
                  class="text-lg font-bold text-gray-900 flex items-center gap-2"
                >
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
                  </svg>
                  Mes formations
                </h3>
                <button
                  @click="openFormationModal"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium text-sm flex items-center gap-2"
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
              </div>

              <div v-if="formationsLoading" class="text-center py-4">
                <svg
                  class="animate-spin h-8 w-8 text-indigo-600 mx-auto"
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
              </div>

              <div v-else-if="formationsData.length > 0" class="space-y-3">
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
              <div
                v-else
                class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 text-center"
              >
                <p class="text-gray-600">Aucune formation enregistrée</p>
              </div>
            </div>

            <!-- 🖥️ Équipements Fournis -->
            <div class="pt-6 border-t border-gray-200">
              <h4
                class="text-md font-bold text-gray-900 mb-4 flex items-center gap-2"
              >
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
                Mes équipements
              </h4>

              <div v-if="equipementsLoading" class="text-center py-4">
                <svg
                  class="animate-spin h-8 w-8 text-teal-600 mx-auto"
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
              </div>

              <div v-else-if="equipementsData.length > 0" class="space-y-3">
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
              <div
                v-else
                class="bg-gray-50 border-2 border-gray-200 rounded-lg p-6 text-center"
              >
                <p class="text-gray-600">Aucun équipement attribué</p>
              </div>
            </div>

            <!-- Modal Parcours -->
            <div
              v-if="showParcoursModal"
              class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            >
              <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
                <div
                  class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
                >
                  <h3 class="text-xl font-bold text-gray-900">
                    Ajouter un mouvement
                  </h3>
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
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                        <option value="Changement de poste">
                          Changement de poste
                        </option>
                      </select>
                    </div>
                    <div>
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                        >Date de début *</label
                      >
                      <input
                        type="date"
                        v-model="parcoursForm.date_debut"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                    <label
                      class="block text-sm font-semibold text-gray-700 mb-2"
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

            <!-- Modal Formation -->
            <div
              v-if="showFormationModal"
              class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            >
              <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full">
                <div
                  class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
                >
                  <h3 class="text-xl font-bold text-gray-900">
                    Ajouter une formation
                  </h3>
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
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                        >Date de début *</label
                      >
                      <input
                        type="date"
                        v-model="formationForm.date_debut"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                      />
                    </div>
                    <div>
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
                        >Date de fin *</label
                      >
                      <input
                        type="date"
                        v-model="formationForm.date_fin"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                      />
                    </div>
                    <div class="col-span-2">
                      <label
                        class="block text-sm font-semibold text-gray-700 mb-2"
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
                    <label
                      class="block text-sm font-semibold text-gray-700 mb-2"
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

            <!-- Actions -->
            <div
              v-if="editMode"
              class="flex gap-3 pt-4 border-t border-gray-200"
            >
              <button
                type="button"
                @click="cancelEdit"
                class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
              >
                Annuler
              </button>
              <button
                type="button"
                @click="saveProfile"
                :disabled="saving"
                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
              >
                {{
                  saving ? "Enregistrement..." : "Enregistrer les modifications"
                }}
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-4">
            Changer mon mot de passe
          </h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Mot de passe actuel</label
              >
              <input
                v-model="passwordData.current"
                type="password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="••••••••"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Nouveau mot de passe</label
              >
              <input
                v-model="passwordData.new"
                type="password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="••••••••"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2"
                >Confirmer le mot de passe</label
              >
              <input
                v-model="passwordData.confirm"
                type="password"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="••••••••"
              />
            </div>

            <button
              type="button"
              @click="changePassword"
              class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all"
            >
              Mettre à jour le mot de passe
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useAuthStore } from "../stores/auth";
import api from "../services/api/axios";

const authStore = useAuthStore();
const editMode = ref(false);
const saving = ref(false);
const successMessage = ref("");
const photoInput = ref<HTMLInputElement | null>(null);
const loadingContract = ref(false);
const contract = ref<any>(null);
const showParcoursModal = ref(false);
const showFormationModal = ref(false);
const parcoursLoading = ref(false);
const formationsLoading = ref(false);
const equipementsLoading = ref(false);
const parcoursData = ref<any[]>([]);
const formationsData = ref<any[]>([]);
const equipementsData = ref<any[]>([]);

// Formulaires
const parcoursForm = ref({
  type_mouvement: "",
  date_debut: "",
  poste: "",
  departement: "",
  notes: "",
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

const profile = ref<any>({
  fullname: "",
  email: "",
  tel: "",
  matricule: "",
  poste: "",
  date_embauche: "",
  date_naissance: "",
  genre: "",
  adresse: "",
  statut: "actif",
  photo_url: "",
});

const formData = ref<any>({});

const passwordData = ref({
  current: "",
  new: "",
  confirm: "",
});

const soldeConges = ref(30);
const bulletinsCount = ref(0);

const userInitials = computed(() => {
  if (!profile.value.fullname) return "NA";
  const parts = profile.value.fullname.split(" ");
  return parts
    .map((p: string) => p[0])
    .join("")
    .toUpperCase()
    .slice(0, 2);
});

const anciennete = computed(() => {
  if (!profile.value.date_embauche) return "N/A";
  const embauche = new Date(profile.value.date_embauche);
  const now = new Date();
  const diff = now.getTime() - embauche.getTime();
  const years = Math.floor(diff / (1000 * 60 * 60 * 24 * 365));
  const months = Math.floor(
    (diff % (1000 * 60 * 60 * 24 * 365)) / (1000 * 60 * 60 * 24 * 30)
  );

  if (years > 0) {
    return `${years} an${years > 1 ? "s" : ""}`;
  }
  return `${months} mois`;
});

onMounted(async () => {
  await loadProfile();
});

const loadProfile = async () => {
  try {
    const userResponse = await api.get("/me");
    const userId = userResponse.data.id;

    const collabResponse = await api.get("/collaborators");
    const collabData = Array.isArray(collabResponse.data)
      ? collabResponse.data
      : collabResponse.data.data || [];
    const myCollab = collabData.find((c: any) => c.user_id === userId);

    if (myCollab) {
      profile.value = {
        id: myCollab.id,
        fullname: userResponse.data.fullname,
        email: userResponse.data.email,
        tel: userResponse.data.tel,
        matricule: myCollab.matricule,
        poste: myCollab.poste,
        date_embauche: myCollab.date_embauche,
        date_naissance: myCollab.date_naissance,
        genre: myCollab.genre,
        adresse: myCollab.adresse,
        statut: myCollab.statut,
        photo_url: myCollab.photo_url || "",
      };

      formData.value = { ...profile.value };

      // Charger le contrat
      await loadContract(myCollab.id);
      // Charger le parcours professionnel
      await loadParcoursData(myCollab.id);

      // Charger les formations
      await loadFormationsData(myCollab.id);

      // Charger les équipements
      await loadEquipementsData(myCollab.id);

      const congesResponse = await api.get(
        `/collaborators/${myCollab.id}/conges`
      );
      const congesData = Array.isArray(congesResponse.data)
        ? congesResponse.data
        : congesResponse.data.historique || [];
      const joursPris = congesData
        .filter((c: any) => c.statut === "approuvé")
        .reduce((sum: number, c: any) => sum + (c.nb_jours || 0), 0);
      soldeConges.value = 30 - joursPris;

      const paiesResponse = await api.get(
        `/collaborators/${myCollab.id}/paies`
      );
      const paiesData = Array.isArray(paiesResponse.data)
        ? paiesResponse.data
        : paiesResponse.data.historique || [];
      bulletinsCount.value = paiesData.length;
    }
  } catch (error) {
    console.error("Erreur chargement profil:", error);
  }
};

const loadContract = async (collaboratorId: number) => {
  loadingContract.value = true;
  try {
    const response = await api.get(
      `/collaborators/${collaboratorId}/documents`
    );
    const documents = Array.isArray(response.data) ? response.data : [];

    // Trouver le contrat
    const contractDoc = documents.find(
      (doc: any) => doc.type === "contract" && doc.statut === "actif"
    );

    if (contractDoc) {
      contract.value = contractDoc;
    }
  } catch (error) {
    console.error("Erreur chargement contrat:", error);
  } finally {
    loadingContract.value = false;
  }
};

const loadParcoursData = async (collaboratorId: number) => {
  parcoursLoading.value = true;
  try {
    const response = await api.get(`/collaborators/${collaboratorId}/parcours`);
    parcoursData.value = response.data.map((p: any) => ({
      id: p.id,
      type_mouvement: p.type_mouvement,
      date: new Date(p.date_debut).toLocaleDateString("fr-FR"),
      poste: p.poste,
      departement: p.departement,
    }));
  } catch (error) {
    console.error("Erreur chargement parcours:", error);
    parcoursData.value = [];
  } finally {
    parcoursLoading.value = false;
  }
};

const loadFormationsData = async (collaboratorId: number) => {
  formationsLoading.value = true;
  try {
    const response = await api.get(
      `/collaborators/${collaboratorId}/formations`
    );
    formationsData.value = response.data.map((f: any) => ({
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
  } catch (error) {
    console.error("Erreur chargement formations:", error);
    formationsData.value = [];
  } finally {
    formationsLoading.value = false;
  }
};

const loadEquipementsData = async (collaboratorId: number) => {
  equipementsLoading.value = true;
  try {
    const response = await api.get(
      `/collaborators/${collaboratorId}/equipements`
    );
    equipementsData.value = response.data.map((eq: any) => ({
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
    console.error("Erreur chargement équipements:", error);
    equipementsData.value = [];
  } finally {
    equipementsLoading.value = false;
  }
};

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
      `/collaborators/${profile.value.id}/parcours`,
      parcoursForm.value
    );
    successMessage.value = "Parcours ajouté avec succès !";
    showParcoursModal.value = false;
    await loadParcoursData(profile.value.id);

    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'ajout du parcours");
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
      `/collaborators/${profile.value.id}/formations`,
      formationForm.value
    );
    successMessage.value = "Formation ajoutée avec succès !";
    showFormationModal.value = false;
    await loadFormationsData(profile.value.id);

    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error) {
    console.error("Erreur:", error);
    alert("Erreur lors de l'ajout de la formation");
  }
};

const downloadContract = async () => {
  if (!contract.value) return;

  try {
    const response = await api.get(`/documents/${contract.value.id}/download`, {
      responseType: "blob",
    });

    // Créer un lien de téléchargement
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement("a");
    link.href = url;
    link.setAttribute("download", contract.value.nom_fichier);
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);

    successMessage.value = "Contrat téléchargé avec succès !";
    setTimeout(() => {
      successMessage.value = "";
    }, 3000);
  } catch (error: any) {
    console.error("Erreur téléchargement contrat:", error);
    alert(
      error.response?.data?.message ||
        "Erreur lors du téléchargement du contrat"
    );
  }
};

const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return "0 Bytes";
  const k = 1024;
  const sizes = ["Bytes", "KB", "MB", "GB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
};

const triggerPhotoUpload = () => {
  photoInput.value?.click();
};

const handlePhotoChange = async (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (!file) return;

  // Validation du fichier
  const validTypes = ["image/jpeg", "image/png", "image/jpg"];
  if (!validTypes.includes(file.type)) {
    alert("Format de fichier non supporté. Utilisez JPG, JPEG ou PNG.");
    return;
  }

  const maxSize = 5 * 1024 * 1024; // 5MB
  if (file.size > maxSize) {
    alert("La taille du fichier ne doit pas dépasser 5MB.");
    return;
  }

  try {
    const formData = new FormData();
    formData.append("photo", file);

    const response = await api.post("/profile/photo", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });

    profile.value.photo_url = response.data.photo_url;

    successMessage.value = "Photo de profil mise à jour avec succès !";
    setTimeout(() => {
      successMessage.value = "";
    }, 5000);

    await authStore.fetchUser();
  } catch (error: any) {
    console.error("Erreur upload photo:", error);
    const errorMsg =
      error.response?.data?.message || "Erreur lors de l'upload de la photo";
    alert(errorMsg);
  }
};

const removePhoto = async () => {
  if (!confirm("Voulez-vous vraiment supprimer votre photo de profil ?")) {
    return;
  }

  try {
    await api.delete("/profile/photo");

    profile.value.photo_url = "";

    successMessage.value = "Photo de profil supprimée avec succès !";
    setTimeout(() => {
      successMessage.value = "";
    }, 5000);

    await authStore.fetchUser();
  } catch (error: any) {
    console.error("Erreur suppression photo:", error);
    const errorMsg =
      error.response?.data?.message ||
      "Erreur lors de la suppression de la photo";
    alert(errorMsg);
  }
};

const cancelEdit = () => {
  editMode.value = false;
  formData.value = { ...profile.value };
};

const saveProfile = async () => {
  saving.value = true;
  successMessage.value = "";

  try {
    await api.put("/profile", {
      fullname: formData.value.fullname,
      telephone: formData.value.tel,
      date_naissance: formData.value.date_naissance,
      genre: formData.value.genre,
      adresse: formData.value.adresse,
    });

    await loadProfile();
    await authStore.fetchUser();

    successMessage.value = "Profil mis à jour avec succès !";
    editMode.value = false;

    setTimeout(() => {
      successMessage.value = "";
    }, 5000);
  } catch (error: any) {
    console.error("Erreur sauvegarde profil:", error);
    const errorMsg = error.response?.data?.message || error.message;
    alert("Erreur lors de la sauvegarde du profil: " + errorMsg);
  } finally {
    saving.value = false;
  }
};

const changePassword = async () => {
  if (passwordData.value.new !== passwordData.value.confirm) {
    alert("Les mots de passe ne correspondent pas");
    return;
  }

  if (passwordData.value.new.length < 8) {
    alert("Le mot de passe doit contenir au moins 8 caractères");
    return;
  }

  try {
    await api.post("/change-password", {
      current_password: passwordData.value.current,
      new_password: passwordData.value.new,
    });

    successMessage.value = "Mot de passe modifié avec succès !";
    passwordData.value = { current: "", new: "", confirm: "" };

    setTimeout(() => {
      successMessage.value = "";
    }, 5000);
  } catch (error) {
    console.error("Erreur changement mot de passe:", error);
    alert("Erreur lors du changement de mot de passe");
  }
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return "N/A";
  const date = new Date(dateStr);
  return date.toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
};
</script>
