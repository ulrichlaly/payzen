<?php

namespace App\Http\Controllers;

use App\Models\Conge;
use App\Models\User;
use Illuminate\Http\Request;

use function Illuminate\Log\log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CongeNotification;

class CongeController extends Controller
{
    public function index()
    {
        $conges = Conge::with('collaborator.user')->get();
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

        try {
            $admins = User::whereHas('role', fn($q) => $q->where('name', 'Admin'))->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new CongeNotification($conge));
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création
            log('Erreur notification congé: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Demande de congé enregistrée avec succès.',
            'data' => $conge
        ], 201);
    }

    public function show($id)
    {
        $conge = Conge::with('collaborator')->find($id);
        if (!$conge) return response()->json(['message' => 'Congé introuvable.'], 404);

        return response()->json($conge, 200);
    }

    public function update(Request $request, $id)
    {
        $conge = Conge::find($id);
        if (!$conge) return response()->json(['message' => 'Congé introuvable.'], 404);

        $validated = $request->validate([
            'statut' => 'required|in:en_attente,approuvé,refusé'
        ]);

        $conge->update($validated);

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
