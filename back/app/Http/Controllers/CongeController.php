<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Events\NewCongeRequestEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CongeController extends Controller
{
    public function index()
    {
        $conges = Conge::with('collaborator.user')->orderBy('created_at', 'desc')->get();
        return response()->json($conges, 200);
    }

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
        $conge->load('collaborator.user');

        // 🔥 Diffuser l'événement Pusher vers les admins
        broadcast(new NewCongeRequestEvent($conge))->toOthers();

        Log::info('✅ Demande de congé créée avec notification Pusher', ['conge_id' => $conge->id]);

        return response()->json([
            'message' => 'Demande de congé enregistrée avec succès.',
            'data' => $conge
        ], 201);
    }

    public function show($id)
    {
        $conge = Conge::with('collaborator.user')->find($id);
        if (!$conge) return response()->json(['message' => 'Congé introuvable.'], 404);
        return response()->json($conge, 200);
    }

    public function update(Request $request, $id)
    {
        $conge = Conge::with('collaborator.user')->find($id);
        if (!$conge) return response()->json(['message' => 'Congé introuvable.'], 404);

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,approuvé,refusé'
        ]);

        $ancienStatut = $conge->statut;
        $conge->update($validated);
        $conge->load('collaborator.user');

        // 🔥 Notifier le collaborateur via son canal privé
        if ($validated['statut'] !== $ancienStatut) {
            $userId = $conge->collaborator->user_id;

            broadcast(new \App\Events\CongeStatusChangedEvent($conge, $validated['statut']))->toOthers();

            Log::info('✅ Notification statut congé envoyée', [
                'conge_id' => $conge->id,
                'user_id' => $userId,
                'statut' => $validated['statut']
            ]);
        }

        return response()->json(['message' => 'Statut mis à jour.', 'data' => $conge], 200);
    }

    public function destroy($id)
    {
        $conge = Conge::find($id);
        if (!$conge) return response()->json(['message' => 'Congé introuvable.'], 404);
        $conge->delete();
        return response()->json(['message' => 'Congé supprimé.'], 200);
    }

    public function historique($collaborator_id)
    {
        $conges = Conge::where('collaborator_id', $collaborator_id)->get();
        $jours_pris = $conges->where('statut', 'approuvé')->sum('nb_jours');
        $jours_restant = 30 - $jours_pris;

        return response()->json([
            'historique' => $conges,
            'jours_pris' => $jours_pris,
            'jours_restant' => $jours_restant
        ]);
    }
}
