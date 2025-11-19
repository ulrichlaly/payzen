<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold text-gray-900">
          Déclarations sociales et fiscales
        </h2>
        <p class="text-sm text-gray-500 mt-1">
          Gérez vos obligations CNSS, IR et fiscales
        </p>
      </div>
      <button
        @click="openCreateModal"
        class="btn-primary flex items-center gap-2"
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
        Nouvelle déclaration
      </button>
    </div>

    <!-- Alertes échéances -->
    <div
      v-if="upcomingDeadlines.length > 0"
      class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-lg"
    >
      <div class="flex items-start gap-3">
        <svg
          class="w-6 h-6 text-orange-600 flex-shrink-0 mt-0.5"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
          />
        </svg>
        <div class="flex-1">
          <p class="font-bold text-orange-900 mb-2">⚠️ Échéances à venir</p>
          <div class="space-y-1">
            <p
              v-for="deadline in upcomingDeadlines"
              :key="deadline.id"
              class="text-sm text-orange-800"
            >
              • {{ deadline.type }} - <strong>{{ deadline.date }}</strong> ({{
                deadline.daysLeft
              }}
              jours restants)
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">CNSS du mois</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
              {{ formatMontant(stats.cnssMois) }}
            </h3>
            <p class="text-xs text-blue-600 font-semibold">
              Cotisations sociales
            </p>
          </div>
          <div
            class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center"
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
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">IR du mois</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
              {{ formatMontant(stats.irMois) }}
            </h3>
            <p class="text-xs text-green-600 font-semibold">Impôt sur revenu</p>
          </div>
          <div
            class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center"
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
                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">En attente</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
              {{ stats.enAttente }}
            </h3>
            <p class="text-xs text-orange-600 font-semibold">À soumettre</p>
          </div>
          <div
            class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center"
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
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="stat-card">
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600 mb-1">Validées</p>
            <h3 class="text-3xl font-bold text-gray-900 mb-2">
              {{ stats.validees }}
            </h3>
            <p class="text-xs text-green-600 font-semibold">Ce mois</p>
          </div>
          <div
            class="w-12 h-12 bg-primary-500 rounded-xl flex items-center justify-center"
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
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres et boutons actions -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-bold text-gray-900">Déclarations</h3>
        <div class="flex items-center gap-3">
          <select
            v-model="filters.type"
            @change="loadDeclarations"
            class="input-field w-auto"
          >
            <option value="">Tous les types</option>
            <option value="sociale">Déclaration sociale (CNSS)</option>
            <option value="fiscale">Déclaration fiscale (IR)</option>
          </select>

          <select
            v-model="filters.mois"
            @change="loadDeclarations"
            class="input-field w-auto"
          >
            <option value="">Tous les mois</option>
            <option value="01">Janvier</option>
            <option value="02">Février</option>
            <option value="03">Mars</option>
            <option value="04">Avril</option>
            <option value="05">Mai</option>
            <option value="06">Juin</option>
            <option value="07">Juillet</option>
            <option value="08">Août</option>
            <option value="09">Septembre</option>
            <option value="10">Octobre</option>
            <option value="11">Novembre</option>
            <option value="12">Décembre</option>
          </select>

          <select
            v-model="filters.statut"
            @change="loadDeclarations"
            class="input-field w-auto"
          >
            <option value="">Tous les statuts</option>
            <option value="en_attente">En attente</option>
            <option value="soumise">Soumise</option>
            <option value="validee">Validée</option>
            <option value="rejetee">Rejetée</option>
          </select>

          <button
            @click="generateDeclarations"
            class="btn-secondary flex items-center gap-2"
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
            Générer auto
          </button>
        </div>
      </div>

      <!-- Loader -->
      <div v-if="loading" class="py-12 flex justify-center">
        <div class="text-center">
          <svg
            class="animate-spin h-12 w-12 text-primary-600 mx-auto mb-4"
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
          <p class="text-gray-600">Chargement des déclarations...</p>
        </div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Type
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Période
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Montant Total
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Date déclaration
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Référence
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Statut
              </th>
              <th
                class="text-left py-4 px-6 text-xs font-semibold text-gray-600 uppercase"
              >
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="declarations.length === 0">
              <td colspan="7" class="py-12 text-center text-gray-500">
                <svg
                  class="w-16 h-16 text-gray-300 mx-auto mb-4"
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
                <p class="text-lg font-semibold text-gray-700">
                  Aucune déclaration trouvée
                </p>
              </td>
            </tr>
            <tr v-for="decl in declarations" :key="decl.id" class="table-row">
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <div
                    :class="decl.typeIconBg"
                    class="w-8 h-8 rounded-lg flex items-center justify-center"
                  >
                    <svg
                      class="w-4 h-4 text-white"
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
                  <div>
                    <p class="text-sm font-semibold text-gray-900">
                      {{ decl.typeLabel }}
                    </p>
                    <p class="text-xs text-gray-500">{{ decl.organism }}</p>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6 text-sm font-semibold text-gray-900">
                {{ decl.periode }}
              </td>
              <td class="py-4 px-6 text-sm font-bold text-gray-900">
                {{ formatMontant(decl.montant_total) }}
              </td>
              <td class="py-4 px-6 text-sm text-gray-700">
                {{ formatDate(decl.date_declaration) }}
              </td>
              <td class="py-4 px-6">
                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">{{
                  decl.reference || "N/A"
                }}</span>
              </td>
              <td class="py-4 px-6">
                <span
                  :class="decl.statutClass"
                  class="px-3 py-1 rounded-full text-xs font-semibold"
                >
                  {{ decl.statutLabel }}
                </span>
              </td>
              <td class="py-4 px-6">
                <div class="flex items-center gap-2">
                  <button
                    @click="viewDetails(decl)"
                    class="p-2 hover:bg-blue-50 rounded-lg transition-colors group"
                    title="Voir détails"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 group-hover:text-blue-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                      />
                    </svg>
                  </button>
                  <button
                    @click="downloadDeclaration(decl)"
                    class="p-2 hover:bg-green-50 rounded-lg transition-colors group"
                    title="Télécharger"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 group-hover:text-green-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                      />
                    </svg>
                  </button>
                  <button
                    v-if="decl.statut === 'en_attente'"
                    @click="submitDeclaration(decl)"
                    class="p-2 hover:bg-primary-50 rounded-lg transition-colors group"
                    title="Soumettre"
                  >
                    <svg
                      class="w-5 h-5 text-gray-400 group-hover:text-primary-600"
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
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="p-6 border-t border-gray-100">
        <p class="text-sm text-gray-600">
          Affichage de {{ declarations.length }} déclaration(s)
        </p>
      </div>
    </div>

    <!-- Calendrier fiscal -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">
          Calendrier fiscal du Bénin
        </h3>
        <div class="space-y-3">
          <div
            v-for="event in fiscalCalendar"
            :key="event.id"
            class="flex items-start gap-4 p-4 rounded-lg border-2"
            :class="event.borderClass"
          >
            <div
              :class="event.iconBg"
              class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
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
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
              </svg>
            </div>
            <div class="flex-1">
              <p class="text-sm font-bold text-gray-900">{{ event.title }}</p>
              <p class="text-xs text-gray-600 mt-1">{{ event.description }}</p>
              <p class="text-xs font-semibold mt-2" :class="event.dateClass">
                Échéance : {{ event.deadline }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-6">
          Répartition des cotisations
        </h3>
        <div class="space-y-4">
          <div class="p-4 bg-blue-50 rounded-lg border-2 border-blue-200">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-gray-700"
                >CNSS (Part employeur)</span
              >
              <span class="text-lg font-bold text-blue-700">16.4%</span>
            </div>
            <div class="w-full bg-blue-100 rounded-full h-3">
              <div
                class="bg-blue-600 h-full rounded-full"
                style="width: 16.4%"
              ></div>
            </div>
            <p class="text-xs text-gray-600 mt-2">
              Cotisations sociales obligatoires
            </p>
          </div>

          <div class="p-4 bg-green-50 rounded-lg border-2 border-green-200">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-gray-700"
                >CNSS (Part salarié)</span
              >
              <span class="text-lg font-bold text-green-700">3.6%</span>
            </div>
            <div class="w-full bg-green-100 rounded-full h-3">
              <div
                class="bg-green-600 h-full rounded-full"
                style="width: 3.6%"
              ></div>
            </div>
            <p class="text-xs text-gray-600 mt-2">Retenue sur salaire</p>
          </div>

          <div class="p-4 bg-orange-50 rounded-lg border-2 border-orange-200">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-gray-700"
                >IR (Impôt progressif)</span
              >
              <span class="text-lg font-bold text-orange-700">0-35%</span>
            </div>
            <div class="w-full bg-orange-100 rounded-full h-3">
              <div
                class="bg-orange-600 h-full rounded-full"
                style="width: 25%"
              ></div>
            </div>
            <p class="text-xs text-gray-600 mt-2">
              Barème progressif selon revenus
            </p>
          </div>

          <div class="p-4 bg-purple-50 rounded-lg border-2 border-purple-200">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-semibold text-gray-700"
                >Contribution PPN</span
              >
              <span class="text-lg font-bold text-purple-700">2.5%</span>
            </div>
            <div class="w-full bg-purple-100 rounded-full h-3">
              <div
                class="bg-purple-600 h-full rounded-full"
                style="width: 2.5%"
              ></div>
            </div>
            <p class="text-xs text-gray-600 mt-2">
              Politique nationale de population
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals -->
    <DeclarationModal
      v-if="showModal"
      :show="showModal"
      @close="closeModal"
      @submit="handleSubmit"
    />

    <DeclarationDetailsModal
      v-if="showDetailsModal"
      :show="showDetailsModal"
      :declaration="selectedDeclaration"
      @close="showDetailsModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import DeclarationModal from "../components/DeclarationModal.vue";
import DeclarationDetailsModal from "../components/DeclarationDetailsModal.vue";
import api from "../services/api/axios";
import Swal from "sweetalert2";

interface Declaration {
  id: number;
  type: string;
  typeLabel: string;
  typeIconBg: string;
  organism: string;
  periode: string;
  montant_total: number;
  date_declaration: string;
  reference: string;
  statut: string;
  statutLabel: string;
  statutClass: string;
  details?: string;
}

const declarations = ref<Declaration[]>([]);
const loading = ref(false);
const showModal = ref(false);
const showDetailsModal = ref(false);
const selectedDeclaration = ref<Declaration | null>(null);
const successMessage = ref("");

const filters = ref({
  type: "",
  mois:
    new Date().getMonth() + 1 < 10
      ? "0" + (new Date().getMonth() + 1)
      : String(new Date().getMonth() + 1),
  statut: "",
});

const stats = computed(() => {
  const cnssMois = declarations.value
    .filter(
      (d) =>
        d.type === "sociale" &&
        d.periode.includes(getMoisLabel(filters.value.mois))
    )
    .reduce((sum, d) => sum + d.montant_total, 0);

  const irMois = declarations.value
    .filter(
      (d) =>
        d.type === "fiscale" &&
        d.periode.includes(getMoisLabel(filters.value.mois))
    )
    .reduce((sum, d) => sum + d.montant_total, 0);

  const enAttente = declarations.value.filter(
    (d) => d.statut === "en_attente"
  ).length;
  const validees = declarations.value.filter(
    (d) => d.statut === "validee"
  ).length;

  return { cnssMois, irMois, enAttente, validees };
});

const upcomingDeadlines = computed(() => {
  const today = new Date();
  const deadlines = [];

  // CNSS - 15 du mois suivant
  const cnssDeadline = new Date(today.getFullYear(), today.getMonth() + 1, 15);
  const cnssDaysLeft = Math.ceil(
    (cnssDeadline.getTime() - today.getTime()) / (1000 * 60 * 60 * 24)
  );

  if (cnssDaysLeft > 0 && cnssDaysLeft <= 10) {
    deadlines.push({
      id: 1,
      type: "Déclaration CNSS",
      date: formatDate(cnssDeadline.toISOString()),
      daysLeft: cnssDaysLeft,
    });
  }

  // IR - 15 du mois suivant
  const irDeadline = new Date(today.getFullYear(), today.getMonth() + 1, 15);
  const irDaysLeft = Math.ceil(
    (irDeadline.getTime() - today.getTime()) / (1000 * 60 * 60 * 24)
  );

  if (irDaysLeft > 0 && irDaysLeft <= 10) {
    deadlines.push({
      id: 2,
      type: "Déclaration IR",
      date: formatDate(irDeadline.toISOString()),
      daysLeft: irDaysLeft,
    });
  }

  return deadlines;
});

const fiscalCalendar = ref([
  {
    id: 1,
    title: "Déclaration CNSS mensuelle",
    description: "Cotisations sociales (16.4% employeur + 3.6% salarié)",
    deadline: "15 de chaque mois",
    iconBg: "bg-blue-500",
    borderClass: "border-blue-200",
    dateClass: "text-blue-700",
  },
  {
    id: 2,
    title: "Impôt sur le Revenu (IR)",
    description: "Retenue à la source sur salaires",
    deadline: "15 de chaque mois",
    iconBg: "bg-green-500",
    borderClass: "border-green-200",
    dateClass: "text-green-700",
  },
  {
    id: 3,
    title: "Contribution PPN",
    description: "Politique Nationale de Population (2.5%)",
    deadline: "15 de chaque mois",
    iconBg: "bg-purple-500",
    borderClass: "border-purple-200",
    dateClass: "text-purple-700",
  },
  {
    id: 4,
    title: "TVA mensuelle",
    description: "Taxe sur la Valeur Ajoutée",
    deadline: "20 de chaque mois",
    iconBg: "bg-orange-500",
    borderClass: "border-orange-200",
    dateClass: "text-orange-700",
  },
]);

onMounted(() => {
  loadDeclarations();
});

const loadDeclarations = async () => {
  loading.value = true;
  try {
    const response = await api.get("/declarations");
    const data = Array.isArray(response.data)
      ? response.data
      : response.data.data || [];

    declarations.value = data.map((d: any) => ({
      id: d.id,
      type: d.type,
      typeLabel: getTypeLabel(d.type),
      typeIconBg: getTypeIconBg(d.type),
      organism: getOrganism(d.type),
      periode:
        d.periode ||
        `${getMoisLabel(d.mois || "01")} ${
          d.annee || new Date().getFullYear()
        }`,
      montant_total: parseFloat(d.montant_total || 0),
      date_declaration: d.date_declaration || new Date().toISOString(),
      reference: d.reference || "",
      statut: d.statut || "en_attente",
      statutLabel: getStatutLabel(d.statut),
      statutClass: getStatutClass(d.statut),
      details: d.details,
    }));
  } catch (error) {
    console.error("Erreur chargement déclarations:", error);
    Swal.fire({
      icon: "error",
      title: "Erreur de chargement",
      text: "Impossible de charger les déclarations",
      confirmButtonColor: "#3b82f6",
    });
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const handleSubmit = async (data: any) => {
  try {
    await api.post("/declarations", data);

    await Swal.fire({
      icon: "success",
      title: "Déclaration créée !",
      text: "La déclaration a été créée avec succès",
      confirmButtonColor: "#3b82f6",
      timer: 2000,
      showConfirmButton: false,
    });

    await loadDeclarations();
    closeModal();
  } catch (error: any) {
    console.error("Erreur:", error);

    Swal.fire({
      icon: "error",
      title: "Erreur",
      text:
        error.response?.data?.message ||
        "Erreur lors de la création de la déclaration",
      confirmButtonColor: "#3b82f6",
    });
  }
};

const viewDetails = (declaration: Declaration) => {
  selectedDeclaration.value = declaration;
  showDetailsModal.value = true;
};

const downloadDeclaration = async (declaration: Declaration) => {
  await Swal.fire({
    icon: "info",
    title: "Téléchargement",
    html: `Téléchargement de la déclaration <strong>${
      declaration.typeLabel
    }</strong><br>Référence : ${declaration.reference || "N/A"}`,
    confirmButtonColor: "#3b82f6",
    timer: 2000,
  });
};

const submitDeclaration = async (declaration: Declaration) => {
  const result = await Swal.fire({
    title: "Soumettre cette déclaration ?",
    html: `
      <div class="text-left">
        <p class="mb-2"><strong>Type :</strong> ${declaration.typeLabel}</p>
        <p class="mb-2"><strong>Période :</strong> ${declaration.periode}</p>
        <p class="mb-2"><strong>Montant :</strong> ${formatMontant(
          declaration.montant_total
        )}</p>
        <p class="mb-2"><strong>Organisme :</strong> ${declaration.organism}</p>
      </div>
    `,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3b82f6",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Oui, soumettre",
    cancelButtonText: "Annuler",
  });

  if (result.isConfirmed) {
    try {
      await api.put(`/declarations/${declaration.id}`, { statut: "soumise" });

      await Swal.fire({
        icon: "success",
        title: "Déclaration soumise !",
        text: "La déclaration a été soumise avec succès",
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadDeclarations();
    } catch (error) {
      console.error("Erreur:", error);

      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Impossible de soumettre la déclaration",
        confirmButtonColor: "#3b82f6",
      });
    }
  }
};

const generateDeclarations = async () => {
  const result = await Swal.fire({
    title: "Génération automatique",
    html: `
      <p class="mb-3">Cette action va générer automatiquement les déclarations suivantes :</p>
      <ul class="text-left list-disc list-inside space-y-1 mb-3">
        <li>Déclaration CNSS (cotisations sociales)</li>
        <li>Déclaration IR (impôt sur le revenu)</li>
        <li>Basées sur les paies du mois en cours</li>
      </ul>
      <p class="text-sm text-gray-600">Voulez-vous continuer ?</p>
    `,
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: "#3b82f6",
    cancelButtonColor: "#6b7280",
    confirmButtonText: "Oui, générer",
    cancelButtonText: "Annuler",
  });

  if (result.isConfirmed) {
    try {
      loading.value = true;

      // Appel API pour générer automatiquement les déclarations basées sur les paies
      await api.post("/declarations/generate-auto");

      await Swal.fire({
        icon: "success",
        title: "Déclarations générées !",
        text: "Les déclarations ont été générées automatiquement",
        confirmButtonColor: "#3b82f6",
        timer: 2000,
        showConfirmButton: false,
      });

      await loadDeclarations();
    } catch (error: any) {
      console.error("Erreur:", error);

      Swal.fire({
        icon: "error",
        title: "Erreur",
        text:
          error.response?.data?.message ||
          "Erreur lors de la génération automatique",
        confirmButtonColor: "#3b82f6",
      });
    } finally {
      loading.value = false;
    }
  }
};

const formatMontant = (montant: number) => {
  return (
    new Intl.NumberFormat("fr-FR", {
      minimumFractionDigits: 0,
    }).format(montant) + " FCFA"
  );
};

const formatDate = (dateStr: string) => {
  if (!dateStr) return "N/A";
  const date = new Date(dateStr);
  return date.toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};

const getMoisLabel = (mois: string) => {
  const labels: Record<string, string> = {
    "01": "Janvier",
    "02": "Février",
    "03": "Mars",
    "04": "Avril",
    "05": "Mai",
    "06": "Juin",
    "07": "Juillet",
    "08": "Août",
    "09": "Septembre",
    "10": "Octobre",
    "11": "Novembre",
    "12": "Décembre",
  };
  return labels[mois] || mois;
};

const getTypeLabel = (type: string) => {
  const labels: Record<string, string> = {
    sociale: "Déclaration sociale (CNSS)",
    fiscale: "Déclaration fiscale (IR)",
  };
  return labels[type] || type;
};

const getTypeIconBg = (type: string) => {
  return type === "sociale" ? "bg-blue-500" : "bg-green-500";
};

const getOrganism = (type: string) => {
  return type === "sociale" ? "CNSS Bénin" : "Direction Générale des Impôts";
};

const getStatutLabel = (statut: string) => {
  const labels: Record<string, string> = {
    en_attente: "En attente",
    soumise: "Soumise",
    validee: "Validée",
    rejetee: "Rejetée",
  };
  return labels[statut] || statut;
};

const getStatutClass = (statut: string) => {
  const classes: Record<string, string> = {
    en_attente: "bg-orange-100 text-orange-700",
    soumise: "bg-blue-100 text-blue-700",
    validee: "bg-green-100 text-green-700",
    rejetee: "bg-red-100 text-red-700",
  };
  return classes[statut] || "bg-gray-100 text-gray-700";
};
</script>
