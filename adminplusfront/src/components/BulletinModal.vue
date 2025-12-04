<template>
  <Transition name="modal">
    <div
      v-if="show && paie"
      class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
      <div
        class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"
        @click="$emit('close')"
      ></div>

      <div
        class="relative bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto"
      >
        <!-- Header Actions -->
        <div
          class="sticky top-0 bg-white border-b-2 border-gray-200 px-6 py-4 rounded-t-2xl z-10 flex items-center justify-between"
        >
          <h3 class="text-xl font-bold text-gray-900">Bulletin de Paie</h3>
          <div class="flex items-center gap-2">
            <button
              @click="downloadPDF"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2"
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
                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                />
              </svg>
              Télécharger PDF
            </button>
            <button
              @click="$emit('close')"
              class="p-2 hover:bg-gray-100 rounded-lg"
            >
              <svg
                class="w-6 h-6"
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

        <!-- Bulletin Content -->
        <div id="bulletin-content" class="p-6 bg-white">
          <!-- En-tête République -->
          <div class="text-center mb-6 border-b-2 border-green-600 pb-4">
            <img
              src="/public/raynis.png"
              alt="Logo"
              class="mx-auto w-20 h-auto mb-2"
            />
            <h2 class="text-lg font-bold text-green-700">BULLETIN DE PAIE</h2>
            <p class="text-xs text-gray-600 mt-1">
              Période : {{ paie.periode }}
            </p>
          </div>

          <!-- SECTION 1: Informations Employeur et Employé -->
          <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Employeur -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
              <h3
                class="font-bold text-gray-900 mb-2 text-xs uppercase border-b border-blue-300 pb-1"
              >
                Employeur
              </h3>
              <div class="space-y-1 text-xs">
                <div>
                  <span class="text-gray-600">Raison sociale :</span>
                  <p class="font-semibold text-gray-900">RAYNIS Bénin</p>
                </div>
                <div>
                  <span class="text-gray-600">Adresse :</span>
                  <p class="font-semibold text-gray-900">Cotonou, Bénin</p>
                </div>
                <div>
                  <span class="text-gray-600">IFU :</span>
                  <p class="font-semibold text-gray-900">IFU123456789</p>
                </div>
                <div>
                  <span class="text-gray-600">N° CNSS :</span>
                  <p class="font-semibold text-gray-900">123456789</p>
                </div>
              </div>
            </div>

            <!-- Employé -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
              <h3
                class="font-bold text-gray-900 mb-2 text-xs uppercase border-b border-green-300 pb-1"
              >
                Employé
              </h3>
              <div class="space-y-1 text-xs">
                <div>
                  <span class="text-gray-600">Nom complet :</span>
                  <p class="font-semibold text-gray-900">
                    {{ paie.employeeName }}
                  </p>
                </div>
                <div>
                  <span class="text-gray-600">Matricule :</span>
                  <p class="font-semibold text-gray-900">
                    {{ paie.employeeMatricule }}
                  </p>
                </div>
                <div>
                  <span class="text-gray-600">Poste :</span>
                  <p class="font-semibold text-gray-900">
                    {{ paie.poste || "Non spécifié" }}
                  </p>
                </div>
                <div>
                  <span class="text-gray-600">N° CNSS :</span>
                  <p class="font-semibold text-gray-900">
                    {{ paie.employeeCNSS || "Non renseigné" }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 2: Détails du Salaire -->
          <div class="mb-4">
            <h3
              class="font-bold text-gray-900 mb-3 text-sm border-b border-gray-300 pb-1"
            >
              Détail de la rémunération
            </h3>

            <!-- A - Salaire de base -->
            <div class="mb-4">
              <h4
                class="font-semibold text-blue-700 mb-2 text-xs bg-blue-50 p-2 rounded"
              >
                A. SALAIRE DE BASE
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <tbody>
                  <tr class="border-b border-gray-200">
                    <td class="py-1 px-2">Salaire mensuel</td>
                    <td class="py-1 px-2 text-right font-semibold">
                      {{ formatMontant(paie.salaireBase) }}
                    </td>
                  </tr>
                  <tr class="border-b border-gray-200">
                    <td class="py-1 px-2">Nombre d'heures mensuelles</td>
                    <td class="py-1 px-2 text-right">
                      {{ calculerHeuresMensuelles() }} h
                    </td>
                  </tr>
                  <tr class="bg-blue-50">
                    <td class="py-1 px-2 font-semibold">Taux horaire</td>
                    <td class="py-1 px-2 text-right font-bold">
                      {{ formatMontant(calculerTauxHoraire()) }} FCFA/h
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- B - Primes -->
            <div class="mb-4">
              <h4
                class="font-semibold text-green-700 mb-2 text-xs bg-green-50 p-2 rounded"
              >
                B. PRIMES ET INDEMNITÉS
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <thead class="bg-gray-100">
                  <tr>
                    <th
                      class="text-left py-1 px-2 border-b border-gray-200 font-semibold"
                    >
                      Libellé
                    </th>
                    <th
                      class="text-right py-1 px-2 border-b border-gray-200 font-semibold"
                    >
                      Montant (FCFA)
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="calculerPrimeTransport() > 0">
                    <td class="py-1 px-2">Prime de transport</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(calculerPrimeTransport()) }}
                    </td>
                  </tr>
                  <tr v-if="calculerPrimeLogement() > 0">
                    <td class="py-1 px-2">Prime de logement</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(calculerPrimeLogement()) }}
                    </td>
                  </tr>
                  <tr v-if="paie.primes > 0">
                    <td class="py-1 px-2">Autres primes et indemnités</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(paie.primes) }}
                    </td>
                  </tr>
                  <tr class="bg-green-50 font-bold">
                    <td class="py-1 px-2">TOTAL DES PRIMES</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(calculerTotalPrimes()) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- C - Total Brut -->
            <div class="mb-4">
              <h4
                class="font-semibold text-gray-900 mb-2 text-xs bg-gray-100 p-2 rounded"
              >
                C. TOTAL BRUT
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <tbody>
                  <tr class="border-b border-gray-200">
                    <td class="py-2 px-2">Salaire de base</td>
                    <td class="py-2 px-2 text-right">
                      {{ formatMontant(paie.salaireBase) }}
                    </td>
                  </tr>
                  <tr class="border-b border-gray-200">
                    <td class="py-2 px-2">Total primes et indemnités</td>
                    <td class="py-2 px-2 text-right">
                      {{ formatMontant(calculerTotalPrimes()) }}
                    </td>
                  </tr>
                  <tr class="bg-gray-100 font-bold text-base">
                    <td class="py-2 px-2">TOTAL BRUT</td>
                    <td class="py-2 px-2 text-right">
                      {{ formatMontant(calculerTotalBrut()) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- SECTION 3: Cotisations -->
          <div class="mb-4">
            <h3
              class="font-bold text-gray-900 mb-3 text-sm border-b border-gray-300 pb-1"
            >
              Cotisations sociales
            </h3>

            <!-- Cotisations salariales -->
            <div class="mb-4">
              <h4
                class="font-semibold text-red-700 mb-2 text-xs bg-red-50 p-2 rounded"
              >
                COTISATIONS SALARIALES (retenues sur salaire)
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="text-left py-1 px-2 border-b border-gray-200">
                      Libellé
                    </th>
                    <th class="text-right py-1 px-2 border-b border-gray-200">
                      Base
                    </th>
                    <th class="text-right py-1 px-2 border-b border-gray-200">
                      Taux
                    </th>
                    <th class="text-right py-1 px-2 border-b border-gray-200">
                      Montant
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="calculerCNSSSalarie() > 0">
                    <td class="py-1 px-2">CNSS (3.6%)</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(paie.salaireBase) }}
                    </td>
                    <td class="py-1 px-2 text-right">3.6%</td>
                    <td class="py-1 px-2 text-right font-semibold text-red-600">
                      {{ formatMontant(calculerCNSSSalarie()) }}
                    </td>
                  </tr>
                  <tr v-if="paie.retenues > 0">
                    <td class="py-1 px-2">Autres retenues</td>
                    <td class="py-1 px-2 text-right">-</td>
                    <td class="py-1 px-2 text-right">-</td>
                    <td class="py-1 px-2 text-right font-semibold text-red-600">
                      {{ formatMontant(paie.retenues) }}
                    </td>
                  </tr>
                  <tr class="bg-red-50 font-bold">
                    <td class="py-1 px-2" colspan="3">
                      TOTAL RETENUES SALARIALES
                    </td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(calculerTotalRetenues()) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Cotisations patronales -->
            <div class="mb-4">
              <h4
                class="font-semibold text-purple-700 mb-2 text-xs bg-purple-50 p-2 rounded"
              >
                COTISATIONS PATRONALES
              </h4>
              <table class="w-full border border-gray-200 text-xs">
                <thead class="bg-gray-100">
                  <tr>
                    <th class="text-left py-1 px-2 border-b border-gray-200">
                      Libellé
                    </th>
                    <th class="text-right py-1 px-2 border-b border-gray-200">
                      Base
                    </th>
                    <th class="text-right py-1 px-2 border-b border-gray-200">
                      Taux
                    </th>
                    <th class="text-right py-1 px-2 border-b border-gray-200">
                      Montant
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="py-1 px-2">CNSS Patronale (16.4%)</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(paie.salaireBase) }}
                    </td>
                    <td class="py-1 px-2 text-right">16.4%</td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(calculerCNSSPatronale()) }}
                    </td>
                  </tr>
                  <tr class="bg-purple-50 font-bold">
                    <td class="py-1 px-2" colspan="3">
                      TOTAL COTISATIONS PATRONALES
                    </td>
                    <td class="py-1 px-2 text-right">
                      {{ formatMontant(calculerCNSSPatronale()) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- SECTION 4: Synthèse -->
          <div class="mb-4">
            <h3
              class="font-bold text-gray-900 mb-3 text-sm border-b border-gray-300 pb-1"
            >
              Synthèse
            </h3>
            <table class="w-full border border-gray-200 text-xs mb-4">
              <tbody>
                <tr class="border-b border-gray-200">
                  <td class="py-2 px-2">Total brut</td>
                  <td class="py-2 px-2 text-right font-semibold">
                    {{ formatMontant(calculerTotalBrut()) }}
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-2 px-2">Total retenues salariales</td>
                  <td class="py-2 px-2 text-right text-red-600">
                    - {{ formatMontant(calculerTotalRetenues()) }}
                  </td>
                </tr>
                <tr class="border-b border-gray-200 bg-gray-50">
                  <td class="py-2 px-2 font-semibold">Net imposable</td>
                  <td class="py-2 px-2 text-right font-bold">
                    {{ formatMontant(calculerNetImposable()) }}
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-2 px-2">Net après CNSS</td>
                  <td class="py-2 px-2 text-right">
                    {{ formatMontant(calculerNetApresCNSS()) }}
                  </td>
                </tr>
                <tr class="border-b border-gray-200">
                  <td class="py-2 px-2">Net avant impôt</td>
                  <td class="py-2 px-2 text-right">
                    {{ formatMontant(calculerNetAvantImpots()) }}
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- Net à payer -->
            <div
              class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-4"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs opacity-90 mb-1">NET À PAYER</p>
                  <p class="text-2xl font-bold">
                    {{ formatMontant(paie.netAPayer) }}
                  </p>
                  <p class="text-xs mt-1 opacity-90">
                    Arrêté la présente somme à :
                    {{ formatMontantLettres(paie.netAPayer) }}
                  </p>
                </div>
                <div class="text-3xl font-bold opacity-50">FCFA</div>
              </div>
              <div class="mt-2 text-xs opacity-80">
                Mode de paiement :
                <span class="font-semibold">{{ getModePaiement() }}</span>
              </div>
            </div>
          </div>

          <!-- SECTION 5: Congés -->
          <div class="mb-4">
            <h3
              class="font-bold text-gray-900 mb-3 text-sm border-b border-gray-300 pb-1"
            >
              Situation des congés
            </h3>
            <table class="w-full border border-gray-200 text-xs">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-left py-1 px-2 border-b border-gray-200"></th>
                  <th class="text-center py-1 px-2 border-b border-gray-200">
                    Jours acquis
                  </th>
                  <th class="text-center py-1 px-2 border-b border-gray-200">
                    Jours pris
                  </th>
                  <th class="text-center py-1 px-2 border-b border-gray-200">
                    Solde restant
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="py-1 px-2">Congés annuels</td>
                  <td class="py-1 px-2 text-center">{{ calculerCongesAcquis() }}</td>
                  <td class="py-1 px-2 text-center">{{ calculerCongesPris() }}</td>
                  <td class="py-1 px-2 text-center font-semibold">
                    {{ calculerSoldeConges() }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pied de page avec mentions légales -->
          <div class="mt-6 pt-4 border-t border-gray-300">
            <!-- Signatures -->
            <div class="grid grid-cols-2 gap-6 mb-4">
              <div class="text-center">
                <p class="text-xs text-gray-600 mb-6">
                  Signature de l'employeur
                </p>
                <div class="border-t border-gray-400 pt-2 mt-8">
                  <p class="text-xs text-gray-500">Cachet et signature</p>
                </div>
              </div>
              <div class="text-center">
                <p class="text-xs text-gray-600 mb-6">Signature de l'employé</p>
                <div class="border-t border-gray-400 pt-2 mt-8">
                  <p class="text-xs text-gray-500">Pour réception</p>
                </div>
              </div>
            </div>

            <!-- Mentions légales -->
            <div class="text-center text-xs text-gray-500 space-y-1">
              <p class="italic">
                Bulletin conforme à la législation du travail en République du
                Bénin.
              </p>
              <p class="italic">À conserver par le salarié pendant 5 ans.</p>
              <p class="mt-2">
                Document généré le {{ new Date().toLocaleDateString("fr-FR") }}
                - PAYZEN Système de gestion RH
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
interface Paie {
  id: number;
  employeeName: string;
  employeeMatricule: string;
  periode: string;
  salaireBase: number;
  primes: number;
  retenues: number;
  netAPayer: number;
}

// Ajout des propriétés optionnelles pour la compatibilité
interface PaieEtendue extends Paie {
  poste?: string;
  employeeCNSS?: string;
}

const props = defineProps<{
  show: boolean;
  paie: PaieEtendue | null;
}>();

const emit = defineEmits(["close"]);

// Fonctions de calcul (compatibles avec ton interface actuelle)
const calculerHeuresMensuelles = () => {
  return 173.33; // Standard Bénin
};

const calculerTauxHoraire = () => {
  if (!props.paie?.salaireBase) return 0;
  return props.paie.salaireBase / calculerHeuresMensuelles();
};

const calculerPrimeTransport = () => {
  // 10% du salaire de base comme prime de transport standard
  if (!props.paie?.salaireBase) return 0;
  return Math.round(props.paie.salaireBase * 0.1);
};

const calculerPrimeLogement = () => {
  // 15% du salaire de base comme prime de logement standard
  if (!props.paie?.salaireBase) return 0;
  return Math.round(props.paie.salaireBase * 0.15);
};

const calculerTotalPrimes = () => {
  if (!props.paie) return 0;
  const primesDeBase = props.paie.primes || 0;
  return (
    primesDeBase + calculerPrimeTransport() + calculerPrimeLogement()
  );
};

const calculerTotalBrut = () => {
  if (!props.paie) return 0;
  return props.paie.salaireBase + calculerTotalPrimes();
};

const calculerCNSSSalarie = () => {
  if (!props.paie?.salaireBase) return 0;
  return Math.round(props.paie.salaireBase * 0.036); // 3.6%
};

const calculerCNSSPatronale = () => {
  if (!props.paie?.salaireBase) return 0;
  return Math.round(props.paie.salaireBase * 0.164); // 16.4%
};

const calculerTotalRetenues = () => {
  if (!props.paie) return 0;
  const retenuesDeBase = props.paie.retenues || 0;
  return retenuesDeBase + calculerCNSSSalarie();
};

const calculerNetImposable = () => {
  return calculerTotalBrut() - calculerCNSSSalarie();
};

const calculerNetApresCNSS = () => {
  return calculerTotalBrut() - calculerCNSSSalarie();
};

const calculerNetAvantImpots = () => {
  if (!props.paie) return 0;
  const retenues = props.paie.retenues || 0;
  return calculerNetApresCNSS() - retenues;
};

const calculerCongesAcquis = () => {
  return 2.5; // Standard : 2.5 jours par mois
};

const calculerCongesPris = () => {
  return 1; // Exemple : 1 jour pris
};

const calculerSoldeConges = () => {
  return calculerCongesAcquis() - calculerCongesPris();
};

const getModePaiement = () => {
  return "Virement bancaire"; // Par défaut
};

const formatMontant = (montant: number) => {
  if (!montant || isNaN(montant)) {
    return "0";
  }
  return new Intl.NumberFormat("fr-FR", {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(montant);
};

const formatMontantLettres = (montant: number) => {
  // Version simplifiée pour l'affichage
  if (montant === 0) return "zéro franc CFA";
  
  const millions = Math.floor(montant / 1000000);
  const resteMillions = montant % 1000000;
  const milliers = Math.floor(resteMillions / 1000);
  const reste = resteMillions % 1000;
  
  let texte = "";
  
  if (millions > 0) {
    texte += `${millions} million${millions > 1 ? 's' : ''} `;
  }
  
  if (milliers > 0) {
    texte += `${formatMontant(milliers)} mille `;
  }
  
  if (reste > 0 || (millions === 0 && milliers === 0)) {
    texte += `${formatMontant(reste)} `;
  }
  
  return texte.trim() + " francs CFA";
};

const downloadPDF = async () => {
  const content = document.getElementById("bulletin-content");
  if (!content) return;

  const script = document.createElement("script");
  script.src =
    "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js";

  script.onload = () => {
    // @ts-ignore
    const opt = {
      margin: [10, 10, 10, 10],
      filename: `bulletin-paie-${Date.now()}.pdf`,
      image: { type: "jpeg", quality: 0.98 },
      html2canvas: {
        scale: 2,
        useCORS: true,
        letterRendering: true,
      },
      jsPDF: {
        unit: "mm",
        format: "a4",
        orientation: "portrait",
      },
      pagebreak: { mode: "avoid-all" },
    };

    // @ts-ignore
    html2pdf().set(opt).from(content).save();
  };

  document.head.appendChild(script);
};
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
</style>