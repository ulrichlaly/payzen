<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Events\NewLoanRequestEvent;
use App\Events\LoanStatusChangedEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    /**
     * Liste tous les prêts (Admin/Comptable)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Loan::with(['collaborator.user'])
                ->when($request->statut, fn($q) => $q->where('statut', $request->statut))
                ->when($request->type, fn($q) => $q->where('type', $request->type))
                ->orderBy('created_at', 'desc');

            $loans = $query->get();

            return response()->json($loans);
        } catch (\Exception $e) {
            Log::error('Erreur chargement prêts', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur lors du chargement'], 500);
        }
    }

    /**
     * Mes prêts (Collaborateur)
     */
    public function myLoans(Request $request): JsonResponse
    {
        try {
            $collaborator = $request->user()->collaborator;

            if (!$collaborator) {
                return response()->json(['data' => []]);
            }

            $loans = Loan::where('collaborator_id', $collaborator->id)
                ->with('collaborator.user')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($loans);
        } catch (\Exception $e) {
            Log::error('Erreur myLoans', ['error' => $e->getMessage()]);
            return response()->json(['data' => []], 500);
        }
    }

    /**
     * Créer une nouvelle demande de prêt
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:Prêt,Avance',
                'montant' => 'required|numeric|min:1',
                'duree' => 'required|integer|min:1|max:24',
                'motif' => 'nullable|string|max:500'
            ]);

            $collaborator = $request->user()->collaborator;

            if (!$collaborator) {
                return response()->json([
                    'message' => 'Vous devez être un collaborateur'
                ], 403);
            }

            $loan = Loan::create([
                'collaborator_id' => $collaborator->id,
                'type' => $validated['type'],
                'montant' => $validated['montant'],
                'duree' => $validated['duree'],
                'motif' => $validated['motif'] ?? null,
                'statut' => 'En attente',
                'montant_restant' => $validated['montant'],
            ]);

            $loan->load('collaborator.user');

            // 🔥 Diffuser l'événement Pusher
            broadcast(new NewLoanRequestEvent($loan))->toOthers();

            Log::info('Nouvelle demande de prêt créée', ['loan_id' => $loan->id]);

            return response()->json([
                'message' => 'Demande soumise avec succès',
                'loan' => $loan
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur création prêt', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur lors de la création'], 500);
        }
    }

    /**
     * Approuver une demande de prêt
     */
    public function approve(Request $request, int $id): JsonResponse
    {
        try {
            $loan = Loan::with('collaborator.user')->findOrFail($id);

            if ($loan->statut !== 'En attente') {
                return response()->json([
                    'message' => 'Ce prêt ne peut plus être approuvé'
                ], 400);
            }

            $loan->update([
                'statut' => 'En cours',
                'date_approbation' => now(),
                'date_debut' => now()
            ]);

            // 🔥 Notifier le collaborateur
            broadcast(new LoanStatusChangedEvent($loan, 'En cours'))->toOthers();

            return response()->json([
                'message' => 'Prêt approuvé avec succès',
                'loan' => $loan->fresh(['collaborator.user'])
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur approbation prêt', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur'], 500);
        }
    }

    /**
     * Rejeter une demande de prêt
     */
    public function reject(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'motif_rejet' => 'required|string|max:500'
            ]);

            $loan = Loan::with('collaborator.user')->findOrFail($id);

            if ($loan->statut !== 'En attente') {
                return response()->json([
                    'message' => 'Ce prêt ne peut plus être rejeté'
                ], 400);
            }

            $loan->update([
                'statut' => 'Rejeté',
                'motif_rejet' => $validated['motif_rejet']
            ]);

            // 🔥 Notifier le collaborateur
            broadcast(new LoanStatusChangedEvent($loan, 'Rejeté'))->toOthers();

            return response()->json([
                'message' => 'Prêt rejeté',
                'loan' => $loan->fresh(['collaborator.user'])
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur rejet prêt', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur'], 500);
        }
    }

    /**
     * Supprimer une demande (Collaborateur - seulement si en attente)
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $collaborator = $request->user()->collaborator;

            if (!$collaborator) {
                return response()->json(['message' => 'Non autorisé'], 403);
            }

            $loan = Loan::where('collaborator_id', $collaborator->id)
                ->where('id', $id)
                ->firstOrFail();

            if ($loan->statut !== 'En attente') {
                return response()->json([
                    'message' => 'Seules les demandes en attente peuvent être supprimées'
                ], 400);
            }

            $loan->delete();

            return response()->json(['message' => 'Demande annulée avec succès']);
        } catch (\Exception $e) {
            Log::error('Erreur suppression prêt', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Erreur'], 500);
        }
    }
}
