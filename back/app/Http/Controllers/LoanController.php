<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Collaborator;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    // Liste tous les prêts (Admin/Comptable)
    public function index(Request $request)
    {
        $query = Loan::with(['collaborator.user']);

        // Filtres
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        // ✅ Transformer les données pour ajouter le nom complet
        $loans = $loans->map(function ($loan) {
            $collaborator = $loan->collaborator;
            $user = $collaborator ? $collaborator->user : null;

            return [
                'id' => $loan->id,
                'collaborator_id' => $loan->collaborator_id,
                'type' => $loan->type,
                'montant' => $loan->montant,
                'duree' => $loan->duree,
                'montant_restant' => $loan->montant_restant,
                'mensualite' => $loan->mensualite,
                'motif' => $loan->motif,
                'statut' => $loan->statut,
                'motif_rejet' => $loan->motif_rejet,
                'date_debut' => $loan->date_debut,
                'created_at' => $loan->created_at,
                'updated_at' => $loan->updated_at,
                'collaborator' => [
                    'id' => $collaborator->id ?? null,
                    'matricule' => $collaborator->matricule ?? null,
                    'nom_complet' => $user ? $user->fullname : 'N/A',
                    'email' => $collaborator->email ?? null,
                    'poste' => $collaborator->poste ?? null,
                ]
            ];
        });

        return response()->json($loans, 200);
    }

    // Prêts du collaborateur connecté
    public function myLoans(Request $request)
    {
        $user = $request->user();

        $collaborator = Collaborator::where('user_id', $user->id)->first();

        if (!$collaborator) {
            return response()->json([
                'message' => 'Vous devez être enregistré comme collaborateur.',
                'error' => 'Collaborateur introuvable'
            ], 403);
        }

        $loans = Loan::where('collaborator_id', $collaborator->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($loans, 200);
    }

    // Créer une demande de prêt/avance
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:Prêt,Avance',
            'montant' => 'required|numeric|min:10000',
            'duree' => 'required|integer|min:1|max:24',
            'motif' => 'required|string|min:20',
        ]);

        $user = $request->user();

        $collaborator = Collaborator::where('user_id', $user->id)->first();

        if (!$collaborator) {
            return response()->json([
                'message' => 'Vous devez être enregistré comme collaborateur.',
                'error' => 'Collaborateur introuvable'
            ], 403);
        }

        $validated['collaborator_id'] = $collaborator->id;

        $loan = Loan::create($validated);

        return response()->json([
            'message' => 'Demande créée avec succès.',
            'data' => $loan
        ], 201);
    }

    // Approuver un prêt
    public function approve($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Prêt introuvable.'], 404);
        }

        $loan->update([
            'statut' => 'En cours',
            'date_debut' => Carbon::now()
        ]);

        return response()->json([
            'message' => 'Prêt approuvé.',
            'data' => $loan
        ], 200);
    }

    // Rejeter un prêt
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'motif_rejet' => 'required|string|min:10',
        ]);

        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Prêt introuvable.'], 404);
        }

        $loan->update([
            'statut' => 'Rejeté',
            'motif_rejet' => $validated['motif_rejet']
        ]);

        return response()->json([
            'message' => 'Prêt rejeté.',
            'data' => $loan
        ], 200);
    }

    // Mettre à jour le statut (PATCH)
    public function update(Request $request, $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Prêt introuvable.'], 404);
        }

        $validated = $request->validate([
            'statut' => 'required|in:En attente,Approuvé,Rejeté,En cours,Remboursé',
            'motif_rejet' => 'nullable|string',
        ]);

        $loan->update($validated);

        return response()->json([
            'message' => 'Prêt mis à jour.',
            'data' => $loan
        ], 200);
    }

    // Supprimer un prêt
    public function destroy($id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Prêt introuvable.'], 404);
        }

        $loan->delete();

        return response()->json(['message' => 'Prêt supprimé.'], 200);
    }
}
