<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Collaborator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    /**
     * Uploader un document
     */
    public function store(Request $request, $collaboratorId)
    {
        Log::info('📄 Début upload document', [
            'collaboratorId' => $collaboratorId,
            'has_file' => $request->hasFile('document'),
            'request_all' => $request->all()
        ]);

        try {
            $request->validate([
                'document' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
                'type' => 'nullable|string',
                'description' => 'nullable|string',
                'date_expiration' => 'nullable|date',
            ]);

            // Vérifier que le collaborateur existe
            $collaborator = Collaborator::findOrFail($collaboratorId);

            Log::info('✅ Collaborateur trouvé', ['collaborator' => $collaborator->nom_complet]);

            $type = $request->input('type', 'contract');

            // Supprimer l'ancien contrat du même type s'il existe
            $deleted = Document::where('collaborator_id', $collaboratorId)
                ->where('type', $type)
                ->delete();

            Log::info('🗑️ Anciens documents supprimés', ['count' => $deleted]);

            // Générer un nom unique pour le fichier
            $file = $request->file('document');
            $extension = $file->getClientOriginalExtension();
            $fileName = Str::slug($collaborator->nom_complet) . '_' . $type . '_' . time() . '.' . $extension;

            Log::info('📝 Nom du fichier généré', ['fileName' => $fileName]);

            // Créer le dossier s'il n'existe pas
            $uploadPath = 'documents';
            if (!Storage::disk('public')->exists($uploadPath)) {
                Storage::disk('public')->makeDirectory($uploadPath);
                Log::info(' Dossier créé', ['path' => $uploadPath]);
            }

            // Sauvegarder le fichier
            $path = $file->storeAs($uploadPath, $fileName, 'public');

            Log::info('Fichier sauvegardé', ['path' => $path]);

            // Créer l'enregistrement en base de données
            $document = Document::create([
                'collaborator_id' => $collaboratorId,
                'type' => $type,
                'nom_fichier' => $file->getClientOriginalName(),
                'chemin' => $path,
                'extension' => $extension,
                'taille' => $file->getSize(),
                'description' => $request->input('description', 'Contrat de travail'),
                'date_expiration' => $request->date_expiration,
                'statut' => 'actif',
            ]);

            Log::info('Document créé en base', ['document_id' => $document->id]);

            return response()->json([
                'message' => 'Document uploadé avec succès',
                'data' => $document
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Erreur de validation', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('❌ Erreur upload document', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erreur lors de l\'upload du document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les documents d'un collaborateur
     */
    public function getByCollaborator($collaboratorId)
    {
        try {
            Log::info('📄 Récupération documents', ['collaboratorId' => $collaboratorId]);

            $documents = Document::where('collaborator_id', $collaboratorId)
                ->where('statut', 'actif')
                ->get();

            Log::info('✅ Documents trouvés', ['count' => $documents->count()]);

            return response()->json($documents, 200);
        } catch (\Exception $e) {
            Log::error('❌ Erreur récupération documents', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Erreur lors de la récupération des documents',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharger un document
     */
    public function download($id)
    {
        try {
            $document = Document::findOrFail($id);

            // Vérifier les permissions
            $user = auth()->user();
            $collaborator = Collaborator::where('user_id', $user->id)->first();

            if ($collaborator && $collaborator->id !== $document->collaborator_id && $user->role !== 'admin') {
                return response()->json([
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            if (!Storage::disk('public')->exists($document->chemin)) {
                Log::error('❌ Fichier non trouvé', ['chemin' => $document->chemin]);
                return response()->json([
                    'message' => 'Le fichier n\'existe pas'
                ], 404);
            }

            return Storage::disk('public')->download(
                $document->chemin,
                $document->nom_fichier
            );
        } catch (\Exception $e) {
            Log::error('❌ Erreur téléchargement', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Erreur lors du téléchargement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Les autres méthodes restent inchangées...
    public function show($id)
    {
        try {
            $document = Document::findOrFail($id);

            // Vérifier que l'utilisateur a accès à ce document
            $user = auth()->user();
            $collaborator = Collaborator::where('user_id', $user->id)->first();

            if ($collaborator && $collaborator->id !== $document->collaborator_id && $user->role !== 'admin') {
                return response()->json([
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            return response()->json($document, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Document non trouvé',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $document = Document::findOrFail($id);

            // Vérifier les permissions (admin uniquement)
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Supprimer le fichier physique
            if (Storage::disk('public')->exists($document->chemin)) {
                Storage::disk('public')->delete($document->chemin);
            }

            // Supprimer la base de données
            $document->delete();

            return response()->json([
                'message' => 'Document supprimé avec succès'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkExpiration()
    {
        try {
            $expiredDocuments = Document::where('date_expiration', '<', now())
                ->where('statut', 'actif')
                ->get();

            foreach ($expiredDocuments as $doc) {
                $doc->update(['statut' => 'expiré']);
            }

            return response()->json([
                'message' => 'Vérification effectuée',
                'expired_count' => $expiredDocuments->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la vérification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
