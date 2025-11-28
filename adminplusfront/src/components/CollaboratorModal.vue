<template>
  <Transition name="modal">
    <div
      v-if="show"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
      <div
        class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
        @click="$emit('close')"
      ></div>

      <div
        class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto"
      >
        <div
          class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl z-10"
        >
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-gray-900">
                {{ isEdit ? "Modifier l'employé" : "Nouvel employé" }}
              </h3>
              <p class="text-sm text-gray-500 mt-1">
                {{
                  isEdit
                    ? "Modifiez les informations"
                    : "Remplissez toutes les informations (les champs avec * sont obligatoires)"
                }}
              </p>
            </div>
            <button
              @click="$emit('close')"
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
        </div>

        <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
          <!-- SECTION 1: Informations de Base -->
          <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
            <div class="flex items-center gap-2 mb-4">
              <svg
                class="w-6 h-6 text-blue-600"
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
              <h4 class="text-lg font-bold text-gray-900">
                Informations de Base
              </h4>
            </div>

            <div class="space-y-4">
              <!-- Nom complet -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Nom complet <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="formData.fullname"
                  type="text"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  placeholder="Ex: Kokou Mensah"
                />
              </div>

              <!-- Email et Téléphone -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formData.email"
                    type="email"
                    required
                    :disabled="isEdit"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-100 disabled:cursor-not-allowed"
                    placeholder="exemple@email.com"
                  />
                  <p v-if="isEdit" class="text-xs text-gray-500 mt-1">
                    L'email ne peut pas être modifié
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Téléphone
                  </label>
                  <input
                    v-model="formData.telephone"
                    type="tel"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="+229 XX XX XX XX"
                  />
                </div>
              </div>

              <!-- Poste et Département -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Poste <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formData.poste"
                    type="text"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Ex: Analyste RH"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Département
                  </label>
                  <input
                    v-model="formData.departement"
                    type="text"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Ex: Ressources Humaines"
                  />
                </div>
              </div>

              <!-- Date embauche et Statut -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Date d'embauche <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="formData.date_embauche"
                    type="date"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  />
                </div>

                <div v-if="isEdit">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Statut
                  </label>
                  <select
                    v-model="formData.statut"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  >
                    <option value="actif">Actif</option>
                    <option value="en_conge">En congé</option>
                    <option value="inactif">Inactif</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 2: Informations RH -->
          <div class="bg-green-50 border-2 border-green-200 rounded-xl p-6">
            <div class="flex items-center gap-2 mb-4">
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
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
              <h4 class="text-lg font-bold text-gray-900">Informations RH</h4>
            </div>

            <div class="space-y-4">
              <!-- Salaire et Date naissance -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Salaire de base (FCFA) <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model.number="formData.salaire_base"
                    type="number"
                    required
                    min="0"
                    step="1000"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="450000"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Date de naissance
                  </label>
                  <input
                    v-model="formData.date_naissance"
                    type="date"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  />
                </div>
              </div>

              <!-- Genre et Situation familiale -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Genre
                  </label>
                  <select
                    v-model="formData.genre"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  >
                    <option value="">Sélectionnez</option>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Situation familiale
                  </label>
                  <select
                    v-model="formData.situation_familiale"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  >
                    <option value="">Sélectionnez</option>
                    <option value="Célibataire">Célibataire</option>
                    <option value="Marié(e)">Marié(e)</option>
                    <option value="Divorcé(e)">Divorcé(e)</option>
                    <option value="Veuf(ve)">Veuf(ve)</option>
                  </select>
                </div>
              </div>

              <!-- Nombre d'enfants et Adresse -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre d'enfants
                  </label>
                  <input
                    v-model.number="formData.nombre_enfants"
                    type="number"
                    min="0"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="0"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Adresse
                  </label>
                  <input
                    v-model="formData.adresse"
                    type="text"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Ex: Cotonou, Akpakpa"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 3: Informations Contractuelles -->
          <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6">
            <div class="flex items-center gap-2 mb-4">
              <svg
                class="w-6 h-6 text-red-600"
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
              <h4 class="text-lg font-bold text-gray-900">
                Informations Contractuelles
              </h4>
            </div>

            <div class="space-y-4">
              <!-- Type de contrat et Durée -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Type de contrat
                  </label>
                  <select
                    v-model="formData.type_contrat"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  >
                    <option value="">Sélectionnez</option>
                    <option value="CDI">
                      CDI (Contrat à Durée Indéterminée)
                    </option>
                    <option value="CDD">
                      CDD (Contrat à Durée Déterminée)
                    </option>
                    <option value="Stage">Stage</option>
                    <option value="Apprentissage">Apprentissage</option>
                    <option value="Freelance">Freelance</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Durée du contrat
                  </label>
                  <input
                    v-model="formData.duree_contrat"
                    type="text"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="Ex: 12 mois, Indéterminée"
                  />
                </div>
              </div>

              <!-- Date de fin et Heures de travail -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Date de fin de contrat
                  </label>
                  <input
                    v-model="formData.date_fin_contrat"
                    type="date"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Heures hebdomadaires
                  </label>
                  <input
                    v-model.number="formData.heures_travail"
                    type="number"
                    min="0"
                    step="0.5"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="40"
                  />
                </div>
              </div>

              <!-- Jours de congés et IBAN -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jours de congés annuels
                  </label>
                  <input
                    v-model.number="formData.jours_conges"
                    type="number"
                    min="0"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="30"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    IBAN
                  </label>
                  <input
                    v-model="formData.iban"
                    type="text"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    placeholder="FR76 XXXX XXXX XXXX XXXX XXXX XXX"
                  />
                </div>
              </div>

              <!-- 📄 NOUVEAU : Upload du contrat -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Contrat de travail (PDF, DOC, DOCX)
                </label>
                <div class="flex items-center gap-3">
                  <label
                    class="flex-1 flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition-colors"
                  >
                    <svg
                      class="w-6 h-6 text-gray-400"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                      />
                    </svg>
                    <span class="text-sm text-gray-600">
                      {{ contratFileName || "Choisir un fichier" }}
                    </span>
                    <input
                      ref="contratInput"
                      type="file"
                      accept=".pdf,.doc,.docx"
                      @change="handleContratChange"
                      class="hidden"
                    />
                  </label>

                  <!-- Bouton pour retirer le fichier -->
                  <button
                    v-if="contratFile"
                    type="button"
                    @click="removeContrat"
                    class="px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors"
                    title="Retirer le fichier"
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
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                      />
                    </svg>
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                  Formats acceptés : PDF, DOC, DOCX (max 10MB)
                </p>
              </div>
            </div>
          </div>

          <!-- SECTION 4: Parcours Professionnel Initial (optionnel) -->
          <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-6">
            <div class="flex items-center gap-2 mb-4">
              <svg
                class="w-6 h-6 text-purple-600"
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
              <h4 class="text-lg font-bold text-gray-900">
                Notes sur le Parcours (optionnel)
              </h4>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Notes sur l'expérience professionnelle
              </label>
              <textarea
                v-model="formData.notes_parcours"
                rows="3"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                placeholder="Ex: 5 ans d'expérience en développement web, expert React..."
              ></textarea>
            </div>
          </div>

          <!-- Message d'information pour nouvel employé -->
          <div
            v-if="!isEdit"
            class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4"
          >
            <div class="flex items-start gap-3">
              <svg
                class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
              </svg>
              <div>
                <p class="font-semibold text-blue-900 mb-1">
                  📧 Email automatique
                </p>
                <p class="text-sm text-blue-700">
                  Un email sera automatiquement envoyé à
                  <strong>{{ formData.email || "l'employé" }}</strong> avec :
                </p>
                <ul
                  class="text-sm text-blue-700 list-disc list-inside mt-2 space-y-1"
                >
                  <li>Ses identifiants de connexion</li>
                  <li>
                    Son mot de passe par défaut :
                    <code class="bg-blue-100 px-2 py-0.5 rounded font-mono"
                      >Password123!</code
                    >
                  </li>
                  <li>Son matricule unique</li>
                </ul>
                <p class="text-xs text-blue-600 mt-2">
                  💡 Le contrat sera téléchargé après la création du
                  collaborateur
                </p>
              </div>
            </div>
          </div>

          <!-- Message d'erreur -->
          <div
            v-if="error"
            class="bg-red-50 border-2 border-red-200 rounded-lg p-3 flex items-start gap-2"
          >
            <svg
              class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <p class="text-red-700 text-sm font-semibold">{{ error }}</p>
          </div>

          <!-- Boutons d'action -->
          <div class="flex gap-3 pt-4 sticky bottom-0 bg-white pb-4">
            <button
              type="button"
              @click="$emit('close')"
              class="flex-1 px-6 py-3 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button
              type="submit"
              :disabled="loading"
              class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-blue-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <span
                v-if="loading"
                class="flex items-center justify-center gap-2"
              >
                <svg
                  class="animate-spin h-5 w-5"
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
                {{ isEdit ? "Mise à jour..." : "Création..." }}
              </span>
              <span v-else>{{
                isEdit ? "Mettre à jour" : "Créer l'employé"
              }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>
<script setup lang="ts">
import { ref, watch, computed } from "vue";
import api from "../services/api/axios";

interface Props {
  show: boolean;
  collaborator?: any;
}

const props = defineProps<Props>();
const emit = defineEmits(["close", "submit"]);

const loading = ref(false);
const error = ref("");

// 📄 Gestion du fichier contrat
const contratFile = ref<File | null>(null);
const contratFileName = ref("");
const contratInput = ref<HTMLInputElement | null>(null);

const isEdit = computed(() => !!props.collaborator);

const formData = ref({
  // Informations de base
  fullname: "",
  email: "",
  telephone: "",
  poste: "",
  departement: "",
  date_embauche: new Date().toISOString().split("T")[0],
  statut: "actif",

  // Informations RH
  salaire_base: 0,
  date_naissance: "",
  genre: "",
  situation_familiale: "",
  nombre_enfants: 0,
  adresse: "",

  // Informations contractuelles
  type_contrat: "",
  duree_contrat: "",
  date_fin_contrat: "",
  heures_travail: 40,
  jours_conges: 30,
  iban: "",

  // Notes parcours
  notes_parcours: "",
});

watch(
  () => props.show,
  (newVal) => {
    if (newVal) {
      error.value = "";
      contratFile.value = null;
      contratFileName.value = "";

      if (props.collaborator) {
        formData.value = {
          // Informations de base
          fullname:
            props.collaborator.fullname || props.collaborator.name || "",
          email: props.collaborator.email || "",
          telephone:
            props.collaborator.telephone || props.collaborator.phone || "",
          poste: props.collaborator.poste || props.collaborator.position || "",
          departement: props.collaborator.departement || "",
          date_embauche:
            props.collaborator.date_embauche ||
            new Date().toISOString().split("T")[0],
          statut: props.collaborator.statut || "actif",

          // Informations RH
          salaire_base:
            typeof props.collaborator.salaire_base === "number"
              ? props.collaborator.salaire_base
              : parseFloat(
                  String(props.collaborator.salaire_base || "0").replace(
                    /[^\d]/g,
                    ""
                  )
                ) || 0,
          date_naissance: props.collaborator.date_naissance || "",
          genre: props.collaborator.genre || "",
          situation_familiale: props.collaborator.situation_familiale || "",
          nombre_enfants: props.collaborator.nombre_enfants || 0,
          adresse: props.collaborator.adresse || "",

          // Informations contractuelles
          type_contrat: props.collaborator.type_contrat || "",
          duree_contrat: props.collaborator.duree_contrat || "",
          date_fin_contrat: props.collaborator.date_fin_contrat || "",
          heures_travail: props.collaborator.heures_travail || 40,
          jours_conges: props.collaborator.jours_conges || 30,
          iban: props.collaborator.iban || "",

          // Notes
          notes_parcours: "",
        };
      } else {
        // Réinitialiser le formulaire pour un nouveau collaborateur
        formData.value = {
          fullname: "",
          email: "",
          telephone: "",
          poste: "",
          departement: "",
          date_embauche: new Date().toISOString().split("T")[0],
          statut: "actif",
          salaire_base: 0,
          date_naissance: "",
          genre: "",
          situation_familiale: "",
          nombre_enfants: 0,
          adresse: "",
          type_contrat: "",
          duree_contrat: "",
          date_fin_contrat: "",
          heures_travail: 40,
          jours_conges: 30,
          iban: "",
          notes_parcours: "",
        };
      }
    }
  }
);

// 📄 Gérer la sélection du fichier contrat
const handleContratChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];

  if (!file) return;

  // Vérifier le type de fichier
  const allowedTypes = [
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
  ];

  if (!allowedTypes.includes(file.type)) {
    error.value = "Format de fichier non accepté. Utilisez PDF, DOC ou DOCX.";
    if (contratInput.value) contratInput.value.value = "";
    return;
  }

  // Vérifier la taille (10MB max)
  const maxSize = 10 * 1024 * 1024; // 10MB
  if (file.size > maxSize) {
    error.value = "Le fichier est trop volumineux (max 10MB).";
    if (contratInput.value) contratInput.value.value = "";
    return;
  }

  contratFile.value = file;
  contratFileName.value = file.name;
  error.value = "";
};

// 📄 Retirer le fichier sélectionné
const removeContrat = () => {
  contratFile.value = null;
  contratFileName.value = "";
  if (contratInput.value) {
    contratInput.value.value = "";
  }
};

// 📄 Upload du contrat
const uploadContrat = async (collaboratorId: number) => {
  if (!contratFile.value) {
    console.log("❌ Pas de fichier à uploader");
    return null;
  }

  console.log("🚀 Début upload contrat", {
    collaboratorId,
    fileName: contratFile.value.name,
    fileSize: contratFile.value.size,
    fileType: contratFile.value.type,
  });

  try {
    const formDataUpload = new FormData();
    formDataUpload.append("document", contratFile.value);
    formDataUpload.append("type", "contract");
    formDataUpload.append("description", "Contrat de travail");

    console.log("📤 Envoi vers:", `/collaborators/${collaboratorId}/documents`);

    // Log du contenu du FormData
    for (let pair of formDataUpload.entries()) {
      console.log("FormData:", pair[0], pair[1]);
    }

    const response = await api.post(
      `/collaborators/${collaboratorId}/documents`,
      formDataUpload,
      {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      }
    );

    console.log("✅ Upload réussi:", response.data);
    return response.data;
  } catch (err: any) {
    console.error("❌ Erreur upload contrat:", {
      message: err.message,
      response: err.response?.data,
      status: err.response?.status,
      url: err.config?.url,
    });
    throw new Error(
      err.response?.data?.message || "Erreur lors de l'upload du contrat"
    );
  }
};

const handleSubmit = async () => {
  error.value = "";

  // Validation des champs obligatoires
  if (
    !formData.value.fullname ||
    !formData.value.email ||
    !formData.value.poste
  ) {
    error.value = "Veuillez remplir tous les champs obligatoires (*)";
    return;
  }

  if (!formData.value.salaire_base || formData.value.salaire_base <= 0) {
    error.value = "Le salaire doit être supérieur à 0";
    return;
  }

  // Validation de l'email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(formData.value.email)) {
    error.value = "Veuillez entrer une adresse email valide";
    return;
  }

  loading.value = true;

  try {
    const submitData = {
      ...formData.value,
      isEdit: isEdit.value,
      hasContrat: !!contratFile.value,
    };

    // Émettre l'événement de soumission avec callback pour upload
    emit("submit", submitData, async (collaboratorId: number) => {
      // Upload du contrat si présent
      if (contratFile.value && collaboratorId) {
        try {
          await uploadContrat(collaboratorId);
        } catch (uploadError: any) {
          throw uploadError;
        }
      }
    });
  } catch (err: any) {
    error.value = err.response?.data?.message || "Erreur lors de la soumission";
    loading.value = false;
  }
};

// Exposer les fonctions
defineExpose({
  uploadContrat,
  contratFile,
});
</script>
<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.2s;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Amélioration du scroll */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>
