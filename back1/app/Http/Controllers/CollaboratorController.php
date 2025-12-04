<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use App\Models\User;
use App\Models\ParcoursProfessionnel;
use App\Models\EntretienAnnuel;
use App\Models\Formation;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NouvelEmployeCredentials;

class CollaboratorController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();

            $user->load('roles');
            $userRoles = $user->roles->pluck('name')->toArray();

            $isAdmin = in_array('Admin', $userRoles) ||
                in_array('Administrateur', $userRoles) ||
                in_array('Admin Général', $userRoles);

            if ($isAdmin) {
                $collaborators = Collaborator::with('user')->get()->map(function ($collaborator) {
                    return [
                        'id' => $collaborator->id,
                        'user_id' => $collaborator->user_id,
                        'nom_complet' => $collaborator->user->fullname ?? 'N/A',
                        'email' => $collaborator->email,
                        'poste' => $collaborator->poste,
                        'telephone' => $collaborator->telephone ?? $collaborator->user->tel ?? 'N/A',
                        'salaire_base' => number_format($collaborator->salaire_base, 0, ',', ' ') . ' FCFA',
                        'statut' => $collaborator->statut,
                        'photo_url' => $collaborator->photo_url,
                        'matricule' => $collaborator->matricule,
                        'date_embauche' => $collaborator->date_embauche,
                        'date_naissance' => $collaborator->date_naissance,
                        'departement' => $collaborator->departement,
                        'genre' => $collaborator->genre,
                        'adresse' => $collaborator->adresse,
                        'situation_familiale' => $collaborator->situation_familiale,
                        'nombre_enfants' => $collaborator->nombre_enfants,
                        'type_contrat' => $collaborator->type_contrat,
                        'duree_contrat' => $collaborator->duree_contrat,
                        'date_fin_contrat' => $collaborator->date_fin_contrat,
                        'heures_travail' => $collaborator->heures_travail,
                        'jours_conges' => $collaborator->jours_conges,
                        'iban' => $collaborator->iban,
                    ];
                });

                return response()->json($collaborators);
            }

            $collaborator = Collaborator::with('user')->where('user_id', $user->id)->first();

            if (!$collaborator) {
                return response()->json([
                    'message' => 'Profil collaborateur non trouvé',
                    'user_id' => $user->id,
                    'user_roles' => $userRoles
                ], 404);
            }

            return response()->json([
                [
                    'id' => $collaborator->id,
                    'user_id' => $collaborator->user_id,
                    'nom_complet' => $collaborator->user->fullname ?? 'N/A',
                    'email' => $collaborator->email,
                    'poste' => $collaborator->poste,
                    'telephone' => $collaborator->telephone ?? $collaborator->user->tel ?? 'N/A',
                    'salaire_base' => number_format($collaborator->salaire_base, 0, ',', ' ') . ' FCFA',
                    'statut' => $collaborator->statut,
                    'matricule' => $collaborator->matricule,
                    'date_embauche' => $collaborator->date_embauche,
                    'date_naissance' => $collaborator->date_naissance,
                    'departement' => $collaborator->departement,
                    'genre' => $collaborator->genre,
                    'adresse' => $collaborator->adresse,
                    'situation_familiale' => $collaborator->situation_familiale,
                    'nombre_enfants' => $collaborator->nombre_enfants,
                    'type_contrat' => $collaborator->type_contrat,
                    'duree_contrat' => $collaborator->duree_contrat,
                    'date_fin_contrat' => $collaborator->date_fin_contrat,
                    'heures_travail' => $collaborator->heures_travail,
                    'jours_conges' => $collaborator->jours_conges,
                    'iban' => $collaborator->iban,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur CollaboratorController@index: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Erreur lors de la récupération des collaborateurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'poste' => 'required|string|max:255',
            'salaire_base' => 'required|numeric|min:0',
            'telephone' => 'nullable|string|max:20',
            'date_embauche' => 'required|date',
            'date_naissance' => 'nullable|date',
            'genre' => 'nullable|in:M,F',
            'adresse' => 'nullable|string',
            'departement' => 'nullable|string|max:255',
            'situation_familiale' => 'nullable|string|max:50',
            'nombre_enfants' => 'nullable|integer|min:0',
            'type_contrat' => 'nullable|string|max:50',
            'duree_contrat' => 'nullable|string|max:100',
            'date_fin_contrat' => 'nullable|date',
            'heures_travail' => 'nullable|numeric|min:0',
            'jours_conges' => 'nullable|integer|min:0',
            'iban' => 'nullable|string|max:34',
            'notes_parcours' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $defaultPassword = 'Password123!';
            $collaboratorRole = \App\Models\Role::where('name', 'Collaborateur')
                ->orWhere('name', 'Employé')
                ->orWhere('name', 'Employee')
                ->first();

            if (!$collaboratorRole) {
                $roleId = 4;
            } else {
                $roleId = $collaboratorRole->id;
            }

            $user = User::create([
                'fullname' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($defaultPassword),
                'tel' => $request->telephone,
                'role_id' => $roleId,
            ]);

            $user->roles()->attach($roleId);

            $lastCollaborator = Collaborator::orderByRaw('CAST(SUBSTRING(matricule, 5) AS UNSIGNED) DESC')
                ->first();

            if ($lastCollaborator && $lastCollaborator->matricule) {
                $lastNumber = (int) substr($lastCollaborator->matricule, 4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $matricule = 'COL-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $attempts = 0;
            while (Collaborator::where('matricule', $matricule)->exists() && $attempts < 100) {
                $nextNumber++;
                $matricule = 'COL-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $attempts++;
            }

            if ($attempts >= 100) {
                throw new \Exception('Impossible de générer un matricule unique');
            }

            $nameParts = explode(' ', $request->fullname);
            $prenom = $nameParts[0];
            $nom = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : $nameParts[0];

            $collaborator = Collaborator::create([
                'user_id' => $user->id,
                'matricule' => $matricule,
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'date_naissance' => $request->date_naissance,
                'genre' => $request->genre,
                'adresse' => $request->adresse,
                'poste' => $request->poste,
                'departement' => $request->departement ?? 'Administration',
                'salaire_base' => $request->salaire_base,
                'date_embauche' => $request->date_embauche,
                'statut' => 'actif',
                'situation_familiale' => $request->situation_familiale,
                'nombre_enfants' => $request->nombre_enfants ?? 0,
                'type_contrat' => $request->type_contrat ?? 'CDI',
                'duree_contrat' => $request->duree_contrat,
                'date_fin_contrat' => $request->date_fin_contrat,
                'heures_travail' => $request->heures_travail ?? 40,
                'jours_conges' => $request->jours_conges ?? 30,
                'iban' => $request->iban,
                'notes_parcours' => $request->notes_parcours,
            ]);

            try {
                Mail::to($user->email)->send(new NouvelEmployeCredentials(
                    $user,
                    $collaborator,
                    $defaultPassword
                ));
            } catch (\Exception $e) {
                Log::error('Erreur envoi email: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'message' => 'Employé créé avec succès. Un email a été envoyé avec les identifiants de connexion.',
                'id' => $collaborator->id,
                'collaborator' => $collaborator->load('user'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création collaborateur: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la création de l\'employé',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = auth()->user();
            $collaborator = Collaborator::with('user')->findOrFail($id);

            $user->load('roles');
            $userRoles = $user->roles->pluck('name')->toArray();
            $isAdmin = in_array('Admin', $userRoles) ||
                in_array('Administrateur', $userRoles) ||
                in_array('Admin Général', $userRoles);

            if (!$isAdmin && $collaborator->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Vous ne pouvez consulter que vos propres informations'
                ], 403);
            }

            return response()->json($collaborator);
        } catch (\Exception $e) {
            Log::error('Erreur CollaboratorController@show: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la récupération du collaborateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $collaborator = Collaborator::findOrFail($id);

        $request->validate([
            'fullname' => 'sometimes|required|string|max:255',
            'poste' => 'sometimes|required|string|max:255',
            'salaire_base' => 'sometimes|required|numeric|min:0',
            'telephone' => 'nullable|string|max:20',
            'statut' => 'sometimes|in:actif,en_conge,inactif',
            'date_naissance' => 'nullable|date',
            'genre' => 'nullable|in:M,F',
            'adresse' => 'nullable|string',
            'departement' => 'nullable|string|max:255',
            'situation_familiale' => 'nullable|string|max:50',
            'nombre_enfants' => 'nullable|integer|min:0',
            'type_contrat' => 'nullable|string|max:50',
            'duree_contrat' => 'nullable|string|max:100',
            'date_fin_contrat' => 'nullable|date',
            'heures_travail' => 'nullable|numeric|min:0',
            'jours_conges' => 'nullable|integer|min:0',
            'iban' => 'nullable|string|max:34',
            'notes_parcours' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $collaborator->update([
                'poste' => $request->poste ?? $collaborator->poste,
                'salaire_base' => $request->salaire_base ?? $collaborator->salaire_base,
                'statut' => $request->statut ?? $collaborator->statut,
                'date_naissance' => $request->date_naissance ?? $collaborator->date_naissance,
                'genre' => $request->genre ?? $collaborator->genre,
                'adresse' => $request->adresse ?? $collaborator->adresse,
                'telephone' => $request->telephone ?? $collaborator->telephone,
                'departement' => $request->departement ?? $collaborator->departement,
                'situation_familiale' => $request->situation_familiale ?? $collaborator->situation_familiale,
                'nombre_enfants' => $request->nombre_enfants ?? $collaborator->nombre_enfants,
                'type_contrat' => $request->type_contrat ?? $collaborator->type_contrat,
                'duree_contrat' => $request->duree_contrat ?? $collaborator->duree_contrat,
                'date_fin_contrat' => $request->date_fin_contrat ?? $collaborator->date_fin_contrat,
                'heures_travail' => $request->heures_travail ?? $collaborator->heures_travail,
                'jours_conges' => $request->jours_conges ?? $collaborator->jours_conges,
                'iban' => $request->iban ?? $collaborator->iban,
                'notes_parcours' => $request->notes_parcours ?? $collaborator->notes_parcours,
            ]);

            if ($request->has('fullname') || $request->has('telephone')) {
                $collaborator->user->update([
                    'fullname' => $request->fullname ?? $collaborator->user->fullname,
                    'tel' => $request->telephone ?? $collaborator->user->tel,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Employé mis à jour avec succès',
                'collaborator' => $collaborator->load('user'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $collaborator = Collaborator::findOrFail($id);
        $user = $collaborator->user;

        DB::beginTransaction();

        try {
            $collaborator->delete();
            $user->delete();

            DB::commit();

            return response()->json([
                'message' => 'Employé supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Erreur lors de la suppression',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();
            $collaborator = Collaborator::where('user_id', $user->id)->firstOrFail();

            $request->validate([
                'fullname' => 'sometimes|string|max:255',
                'telephone' => 'sometimes|string|max:20',
                'date_naissance' => 'sometimes|date',
                'genre' => 'sometimes|in:M,F',
                'adresse' => 'sometimes|string|max:255',
            ]);

            DB::beginTransaction();

            if ($request->has('fullname') || $request->has('telephone')) {
                $user->update([
                    'fullname' => $request->fullname ?? $user->fullname,
                    'tel' => $request->telephone ?? $user->tel,
                ]);
            }

            $collaborator->update([
                'date_naissance' => $request->date_naissance ?? $collaborator->date_naissance,
                'genre' => $request->genre ?? $collaborator->genre,
                'adresse' => $request->adresse ?? $collaborator->adresse,
                'telephone' => $request->telephone ?? $collaborator->telephone,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Profil mis à jour avec succès',
                'collaborator' => $collaborator->load('user')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur updateProfile: ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la mise à jour du profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getParcours($id)
    {
        try {
            $parcours = ParcoursProfessionnel::where('collaborator_id', $id)
                ->orderBy('date_debut', 'desc')
                ->get();

            return response()->json($parcours);
        } catch (\Exception $e) {
            Log::error('Erreur getParcours: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addParcours(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'type_mouvement' => 'required|string',
                'date_debut' => 'required|date',
                'poste' => 'required|string',
                'departement' => 'required|string',
                'date_fin' => 'nullable|date',
                'notes' => 'nullable|string',
            ]);

            $validated['collaborator_id'] = $id;

            $parcours = ParcoursProfessionnel::create($validated);

            return response()->json($parcours, 201);
        } catch (\Exception $e) {
            Log::error('Erreur addParcours: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEntretiens($id)
    {
        try {
            $entretiens = EntretienAnnuel::where('collaborator_id', $id)
                ->orderBy('date_entretien', 'desc')
                ->get();

            return response()->json($entretiens);
        } catch (\Exception $e) {
            Log::error('Erreur getEntretiens: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addEntretien(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'date_entretien' => 'required|date',
                'evaluateur' => 'required|string',
                'objectifs_atteints' => 'required|integer|min:0|max:100',
                'competences_techniques' => 'required|integer|min:0|max:100',
                'competences_comportementales' => 'required|integer|min:0|max:100',
                'points_forts' => 'required|string',
                'axes_amelioration' => 'required|string',
                'objectifs_futurs' => 'required|string',
            ]);

            $validated['collaborator_id'] = $id;

            $entretien = EntretienAnnuel::create($validated);

            return response()->json($entretien, 201);
        } catch (\Exception $e) {
            Log::error('Erreur addEntretien: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getFormations($id)
    {
        try {
            $formations = Formation::where('collaborator_id', $id)
                ->orderBy('date_debut', 'desc')
                ->get();

            return response()->json($formations);
        } catch (\Exception $e) {
            Log::error('Erreur getFormations: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addFormation(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string',
                'organisme' => 'required|string',
                'date_debut' => 'required|date',
                'date_fin' => 'required|date',
                'duree_heures' => 'required|integer',
                'cout' => 'nullable|numeric',
                'certification_obtenue' => 'boolean',
                'notes' => 'nullable|string',
            ]);

            $validated['collaborator_id'] = $id;

            $formation = Formation::create($validated);

            return response()->json($formation, 201);
        } catch (\Exception $e) {
            Log::error('Erreur addFormation: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEquipements($id)
    {
        try {
            $equipements = Equipement::where('collaborator_id', $id)
                ->orderBy('date_attribution', 'desc')
                ->get();

            return response()->json($equipements);
        } catch (\Exception $e) {
            Log::error('Erreur getEquipements: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addEquipement(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'type_equipement' => 'required|string',
                'marque' => 'required|string',
                'modele' => 'required|string',
                'numero_serie' => 'required|string',
                'date_attribution' => 'required|date',
                'etat' => 'required|string',
                'date_retour' => 'nullable|date',
                'valeur' => 'nullable|numeric',
                'notes' => 'nullable|string',
            ]);

            $validated['collaborator_id'] = $id;

            $equipement = Equipement::create($validated);

            return response()->json($equipement, 201);
        } catch (\Exception $e) {
            Log::error('Erreur addEquipement: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
