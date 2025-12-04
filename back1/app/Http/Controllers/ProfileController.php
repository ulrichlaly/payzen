<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Collaborator;

class ProfileController extends Controller
{
    /**
     * Upload photo via /profile/photo (sans ID dans l'URL)
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $user = $request->user();
        $collaborator = $user->collaborator;

        if (!$collaborator) {
            return response()->json(['message' => 'Collaborateur non trouvé'], 404);
        }

        // Supprimer l'ancienne photo si elle existe
        if ($collaborator->photo_url) {
            $this->deleteOldPhoto($collaborator->photo_url);
        }

        // Upload la nouvelle photo
        $file = $request->file('photo');
        $filename = 'profile_' . $collaborator->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('photos', $filename, 'public');

        // Générer l'URL complète
        $fullUrl = url('/storage/' . $path);

        // Mettre à jour le collaborateur
        $collaborator->photo_url = $fullUrl;
        $collaborator->save();

        // Rafraîchir pour s'assurer que les données sont à jour
        $collaborator->refresh();

        // Mettre à jour également l'utilisateur si nécessaire
        $user->refresh();

        return response()->json([
            'message' => 'Photo uploadée avec succès',
            'photo_url' => $fullUrl,
            'collaborator' => $collaborator->load('user'),
            'user' => $user
        ]);
    }

    /**
     * Delete photo via /profile/photo (sans ID dans l'URL)
     */
    public function deletePhoto(Request $request)
    {
        $user = $request->user();
        $collaborator = $user->collaborator;

        if (!$collaborator) {
            return response()->json(['message' => 'Collaborateur non trouvé'], 404);
        }

        if ($collaborator->photo_url) {
            $this->deleteOldPhoto($collaborator->photo_url);

            $collaborator->photo_url = null;
            $collaborator->save();

            // Rafraîchir les données
            $collaborator->refresh();
        }

        return response()->json([
            'message' => 'Photo supprimée avec succès',
            'collaborator' => $collaborator->load('user')
        ]);
    }

    /**
     * Change password via /change-password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        $user = $request->user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Le mot de passe actuel est incorrect.'],
            ]);
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Mot de passe mis à jour avec succès'
        ]);
    }

    /**
     * Upload photo via /collaborators/{id}/photo
     */
    public function uploadPhotoById(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user = $request->user();
        $collaborator = Collaborator::findOrFail($id);

        // Sécurité : vérifier que c'est bien son propre profil
        if ($collaborator->user_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Supprimer l'ancienne photo
        if ($collaborator->photo_url) {
            $this->deleteOldPhoto($collaborator->photo_url);
        }

        // Upload la nouvelle photo
        $file = $request->file('photo');
        $filename = 'profile_' . $collaborator->id . '_' . time() . '.' . $file->extension();
        $path = $file->storeAs('photos', $filename, 'public');

        // Générer l'URL complète
        $fullUrl = url('/storage/' . $path);

        // Mettre à jour
        $collaborator->photo_url = $fullUrl;
        $collaborator->save();
        $collaborator->refresh();

        return response()->json([
            'message' => 'Photo uploadée avec succès',
            'photo_url' => $fullUrl,
            'collaborator' => $collaborator->load('user')
        ]);
    }

    /**
     * Delete photo via /collaborators/{id}/photo
     */
    public function deletePhotoById(Request $request, $id)
    {
        $user = $request->user();
        $collaborator = Collaborator::findOrFail($id);

        // Sécurité
        if ($collaborator->user_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        if ($collaborator->photo_url) {
            $this->deleteOldPhoto($collaborator->photo_url);
            $collaborator->photo_url = null;
            $collaborator->save();
            $collaborator->refresh();
        }

        return response()->json([
            'message' => 'Photo supprimée avec succès',
            'collaborator' => $collaborator->load('user')
        ]);
    }

    /**
     * Méthode helper pour supprimer une ancienne photo
     */
    private function deleteOldPhoto($photoUrl)
    {
        if (!$photoUrl) return;

        try {
            $path = str_replace(['/storage/', '\\storage\\'], '', parse_url($photoUrl, PHP_URL_PATH));

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            \Log::warning('Erreur lors de la suppression de la photo: ' . $e->getMessage());
        }
    }
}
