<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Collaborator;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // Liste toutes les notes de frais (Admin/Comptable)
    public function index(Request $request)
    {
        $query = Expense::with('collaborator.user');

        // Filtres
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('categorie') && $request->categorie) {
            $query->where('categorie', $request->categorie);
        }

        $expenses = $query->orderBy('created_at', 'desc')->get();

        // ✅ Transformer les données pour ajouter le nom complet
        $expenses = $expenses->map(function ($expense) {
            $collaborator = $expense->collaborator;
            $user = $collaborator ? $collaborator->user : null;

            return [
                'id' => $expense->id,
                'collaborator_id' => $expense->collaborator_id,
                'categorie' => $expense->categorie,
                'montant' => $expense->montant,
                'date' => $expense->date,
                'description' => $expense->description,
                'justificatif' => $expense->justificatif,
                'statut' => $expense->statut,
                'motif_rejet' => $expense->motif_rejet,
                'created_at' => $expense->created_at,
                'updated_at' => $expense->updated_at,
                'collaborator' => [
                    'id' => $collaborator->id ?? null,
                    'matricule' => $collaborator->matricule ?? null,
                    'nom_complet' => $user ? $user->fullname : 'N/A',
                    'email' => $collaborator->email ?? null,
                    'poste' => $collaborator->poste ?? null,
                ]
            ];
        });

        return response()->json($expenses, 200);
    }

    public function myExpenses(Request $request)
    {
        $user = $request->user();

        $collaborator = Collaborator::where('user_id', $user->id)->first();

        if (!$collaborator) {
            return response()->json([
                'message' => 'Vous devez être enregistré comme collaborateur.',
                'error' => 'Collaborateur introuvable'
            ], 403);
        }

        $expenses = Expense::where('collaborator_id', $collaborator->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($expenses, 200);
    }

    // Créer une note de frais
    public function store(Request $request)
    {
        // ✅ DEBUG : Voir ce qui arrive
        \Log::info('=== DEBUT DEBUG EXPENSE ===');
        \Log::info('Request all:', $request->all());
        \Log::info('Has file justificatif?', ['has' => $request->hasFile('justificatif')]);

        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');
            \Log::info('File info:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);
        }

        $validated = $request->validate([
            'categorie' => 'required|in:Transport,Hébergement,Restauration,Fournitures,Autre',
            'montant' => 'required|numeric|min:100',
            'date' => 'required|date',
            'description' => 'required|string|min:10',
            'justificatif' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120',
        ]);

        $user = $request->user();

        // ✅ CHARGER LA RELATION user
        $collaborator = Collaborator::with('user')->where('user_id', $user->id)->first();

        if (!$collaborator) {
            return response()->json([
                'message' => 'Vous devez être enregistré comme collaborateur.',
                'error' => 'Collaborateur introuvable'
            ], 403);
        }

        $validated['collaborator_id'] = $collaborator->id;

        // ✅ Upload du fichier
        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('justificatifs', $filename, 'public');

            \Log::info('File uploaded successfully:', ['path' => $path]);
            $validated['justificatif'] = $path;
        }

        $expense = Expense::create($validated);

        \Log::info('Expense created:', ['expense' => $expense->toArray()]);

        // ✅ Envoyer notification aux admins/comptables
        $this->notificationService->nouvelleNoteDeFrais($expense, $collaborator);

        // Retourner l'URL complète
        if ($expense->justificatif) {
            $expense->justificatif = url('storage/' . $expense->justificatif);
        }

        \Log::info('=== FIN DEBUG EXPENSE ===');

        return response()->json([
            'message' => 'Note de frais créée avec succès.',
            'data' => $expense
        ], 201);
    }

    // Approuver une note de frais
    public function approve($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Note de frais introuvable.'], 404);
        }

        $expense->update(['statut' => 'Approuvée']);

        // ✅ CHARGER LA RELATION user AVANT D'ENVOYER LA NOTIFICATION
        $collaborator = Collaborator::with('user')->find($expense->collaborator_id);

        if (!$collaborator) {
            return response()->json(['message' => 'Collaborateur introuvable.'], 404);
        }

        // ✅ Notifier le collaborateur
        $this->notificationService->noteDeFraisApprouvee($expense, $collaborator);

        return response()->json([
            'message' => 'Note de frais approuvée.',
            'data' => $expense
        ], 200);
    }

    // Rejeter une note de frais
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'motif_rejet' => 'required|string|min:10',
        ]);

        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Note de frais introuvable.'], 404);
        }

        $expense->update([
            'statut' => 'Rejetée',
            'motif_rejet' => $validated['motif_rejet']
        ]);

        // ✅ CHARGER LA RELATION user AVANT D'ENVOYER LA NOTIFICATION
        $collaborator = Collaborator::with('user')->find($expense->collaborator_id);

        if (!$collaborator) {
            return response()->json(['message' => 'Collaborateur introuvable.'], 404);
        }

        // ✅ Notifier le collaborateur du rejet
        $this->notificationService->noteDeFraisRejetee(
            $expense,
            $collaborator,
            $validated['motif_rejet']
        );

        return response()->json([
            'message' => 'Note de frais rejetée.',
            'data' => $expense
        ], 200);
    }

    // Mettre à jour le statut (PATCH)
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Note de frais introuvable.'], 404);
        }

        $validated = $request->validate([
            'statut' => 'required|in:En attente,Approuvée,Rejetée,Remboursée',
            'motif_rejet' => 'nullable|string',
        ]);

        $oldStatut = $expense->statut;
        $expense->update($validated);

        // ✅ CHARGER LA RELATION user
        $collaborator = Collaborator::with('user')->find($expense->collaborator_id);

        // ✅ Envoyer notification selon le changement de statut
        if ($oldStatut !== $validated['statut'] && $collaborator) {
            switch ($validated['statut']) {
                case 'Approuvée':
                    $this->notificationService->noteDeFraisApprouvee($expense, $collaborator);
                    break;
                case 'Rejetée':
                    $this->notificationService->noteDeFraisRejetee(
                        $expense,
                        $collaborator,
                        $validated['motif_rejet'] ?? null
                    );
                    break;
                case 'Remboursée':
                    $this->notificationService->noteDeFraisRemboursee($expense, $collaborator);
                    break;
            }
        }

        return response()->json([
            'message' => 'Note de frais mise à jour.',
            'data' => $expense
        ], 200);
    }

    // Supprimer une note de frais
    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json(['message' => 'Note de frais introuvable.'], 404);
        }

        // Supprimer le fichier justificatif
        if ($expense->justificatif) {
            Storage::disk('public')->delete($expense->justificatif);
        }

        $expense->delete();

        return response()->json(['message' => 'Note de frais supprimée.'], 200);
    }
}
