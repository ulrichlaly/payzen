<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\Collaborator;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class CongeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Liste tous les congés (Admin)
     */
    public function index()
    {
        $conges = Conge::with('collaborator.user')->get();
        return response()->json($conges, 200);
    }

    /**
     * Créer une demande de congé
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collaborator_id' => 'required|exists:collaborators,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type' => 'required|in:annuel,maladie,maternite,autre',
            'nb_jours' => 'required|integer|min:1',
            'motif' => 'nullable|string',
        ]);

        $validated['statut'] = 'en_attente';

        $conge = Conge::create($validated);

        // Récupérer le collaborateur avec sa relation user
        $collaborator = Collaborator::with('user')->find($validated['collaborator_id']);

        // 🔔 ENVOYER NOTIFICATION AUX ADMINS
        $this->notificationService->nouvelleDemandeConge($conge, $collaborator);

        return response()->json([
            'message' => 'Demande de congé enregistrée avec succès.',
            'data' => $conge
        ], 201);
    }

    /**
     * Afficher un congé
     */
    public function show($id)
    {
        $conge = Conge::with('collaborator.user')->find($id);

        if (!$conge) {
            return response()->json(['message' => 'Congé introuvable.'], 404);
        }

        return response()->json($conge, 200);
    }

    /**
     * Mettre à jour un congé
     */
    public function update(Request $request, $id)
    {
        $conge = Conge::find($id);

        if (!$conge) {
            return response()->json(['message' => 'Congé introuvable.'], 404);
        }

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,approuvé,refusé'
        ]);

        $conge->update($validated);

        return response()->json([
            'message' => 'Statut mis à jour.',
            'data' => $conge
        ], 200);
    }

    /**
     * Approuver un congé
     */
    public function approve($id)
    {
        $conge = Conge::find($id);

        if (!$conge) {
            return response()->json(['message' => 'Congé introuvable.'], 404);
        }

        $conge->update(['statut' => 'approuvé']);

        $collaborator = Collaborator::with('user')->find($conge->collaborator_id);

        // 🔔 NOTIFIER LE COLLABORATEUR
        $this->notificationService->congeApprouve($conge, $collaborator);

        return response()->json([
            'message' => 'Congé approuvé',
            'conge' => $conge
        ], 200);
    }

    /**
     * Rejeter un congé
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'motif_rejet' => 'nullable|string'
        ]);

        $conge = Conge::find($id);

        if (!$conge) {
            return response()->json(['message' => 'Congé introuvable.'], 404);
        }

        $conge->update([
            'statut' => 'refusé',
            'motif_rejet' => $request->motif_rejet
        ]);

        $collaborator = Collaborator::with('user')->find($conge->collaborator_id);

        // 🔔 NOTIFIER LE COLLABORATEUR
        $this->notificationService->congeRejete($conge, $collaborator, $request->motif_rejet);

        return response()->json([
            'message' => 'Congé refusé',
            'conge' => $conge
        ], 200);
    }

    /**
     * Supprimer un congé
     */
    public function destroy($id)
    {
        $conge = Conge::find($id);

        if (!$conge) {
            return response()->json(['message' => 'Congé introuvable.'], 404);
        }

        $conge->delete();

        return response()->json(['message' => 'Congé supprimé.'], 200);
    }

    /**
     * Historique des congés d'un collaborateur
     */
    public function historique($collaborator_id)
    {
        $conges = Conge::where('collaborator_id', $collaborator_id)->get();

        $jours_pris = $conges->where('statut', 'approuvé')->sum('nb_jours');
        $jours_restant = 30 - $jours_pris;

        return response()->json([
            'historique' => $conges,
            'jours_pris' => $jours_pris,
            'jours_restant' => $jours_restant
        ], 200);
    }
}
