<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Events\NotificationSent;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationMail;

class NotificationService
{
    /**
     * Créer et envoyer une notification
     */
    public function envoyerNotification(
        User $user,
        string $type,
        string $titre,
        string $message,
        array $data = [],
        bool $envoyerEmail = true
    ): Notification {
        // Créer la notification en base
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'titre' => $titre,
            'message' => $message,
            'data' => $data,
        ]);

        // Diffuser via Pusher
        broadcast(new NotificationSent($notification));

        // Envoyer l'email si demandé
        if ($envoyerEmail && $user->email) {
            try {
                Mail::to($user->email)->send(new NotificationMail($notification));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email notification: ' . $e->getMessage());
            }
        }

        return $notification;
    }

    /**
     * Récupérer le nom complet du collaborateur
     */
    private function getNomComplet($collaborator): string
    {
        // Charger la relation user si pas déjà chargée
        if (!$collaborator->relationLoaded('user')) {
            $collaborator->load('user');
        }

        // Retourner le fullname du user, ou construire à partir de nom/prenom
        return $collaborator->user->fullname ??
            trim(($collaborator->prenom ?? '') . ' ' . ($collaborator->nom ?? '')) ?:
            'Collaborateur';
    }

    /**
     * Notification pour nouvelle demande de congé (Admin)
     */
    public function nouvelleDemandeConge($conge, $collaborator)
    {
        $admins = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin Général', 'Administrateur', 'Admin']);
        })->get();

        $nomComplet = $this->getNomComplet($collaborator);

        foreach ($admins as $admin) {
            $this->envoyerNotification(
                $admin,
                'conge',
                'Nouvelle demande de congé',
                "{$nomComplet} a fait une demande de congé de {$conge->nb_jours} jour(s)",
                [
                    'conge_id' => $conge->id,
                    'collaborator_id' => $collaborator->id,
                    'collaborator_name' => $nomComplet,
                    'type_conge' => $conge->type,
                    'date_debut' => $conge->date_debut,
                    'date_fin' => $conge->date_fin,
                    'nb_jours' => $conge->nb_jours,
                    'action_url' => '/dashboard/leaves'
                ]
            );
        }
    }

    /**
     * Notification congé approuvé (Collaborateur)
     */
    public function congeApprouve($conge, $collaborator)
    {
        if (!$collaborator->user) return;

        $this->envoyerNotification(
            $collaborator->user,
            'conge',
            'Congé approuvé ✅',
            "Votre demande de congé du {$conge->date_debut} au {$conge->date_fin} a été approuvée",
            [
                'conge_id' => $conge->id,
                'type_conge' => $conge->type,
                'date_debut' => $conge->date_debut,
                'date_fin' => $conge->date_fin,
                'nb_jours' => $conge->nb_jours,
                'statut' => 'approuvé'
            ]
        );
    }

    /**
     * Notification congé rejeté (Collaborateur)
     */
    public function congeRejete($conge, $collaborator, $motifRejet = null)
    {
        if (!$collaborator->user) return;

        $message = "Votre demande de congé du {$conge->date_debut} au {$conge->date_fin} a été refusée";
        if ($motifRejet) {
            $message .= ". Motif: {$motifRejet}";
        }

        $this->envoyerNotification(
            $collaborator->user,
            'conge',
            'Congé refusé ❌',
            $message,
            [
                'conge_id' => $conge->id,
                'type_conge' => $conge->type,
                'date_debut' => $conge->date_debut,
                'date_fin' => $conge->date_fin,
                'motif_rejet' => $motifRejet,
                'statut' => 'refusé'
            ]
        );
    }

    /**
     * Notification nouvelle demande de prêt (Admin/Comptable)
     */
    public function nouvelDemandePret($loan, $collaborator)
    {
        $destinataires = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin Général', 'Administrateur', 'Admin', 'Comptable']);
        })->get();

        $nomComplet = $this->getNomComplet($collaborator);

        foreach ($destinataires as $user) {
            $this->envoyerNotification(
                $user,
                'loan',
                'Nouvelle demande de prêt/avance',
                "{$nomComplet} demande un {$loan->type} de " . number_format($loan->montant, 0, ',', ' ') . " FCFA",
                [
                    'loan_id' => $loan->id,
                    'collaborator_id' => $collaborator->id,
                    'collaborator_name' => $nomComplet,
                    'type' => $loan->type,
                    'montant' => $loan->montant,
                    'duree' => $loan->duree,
                    'action_url' => '/dashboard/loans'
                ]
            );
        }
    }

    /**
     * Notification prêt approuvé (Collaborateur)
     */
    public function pretApprouve($loan, $collaborator)
    {
        if (!$collaborator->user) return;

        $this->envoyerNotification(
            $collaborator->user,
            'loan',
            'Prêt/Avance approuvé ✅',
            "Votre demande de {$loan->type} de " . number_format($loan->montant, 0, ',', ' ') . " FCFA a été approuvée",
            [
                'loan_id' => $loan->id,
                'type' => $loan->type,
                'montant' => $loan->montant,
                'duree' => $loan->duree,
                'statut' => 'Approuvé'
            ]
        );
    }

    /**
     * Notification prêt rejeté (Collaborateur)
     */
    public function pretRejete($loan, $collaborator, $motifRejet = null)
    {
        if (!$collaborator->user) return;

        $message = "Votre demande de {$loan->type} de " . number_format($loan->montant, 0, ',', ' ') . " FCFA a été refusée";
        if ($motifRejet) {
            $message .= ". Motif: {$motifRejet}";
        }

        $this->envoyerNotification(
            $collaborator->user,
            'loan',
            'Prêt/Avance refusé ❌',
            $message,
            [
                'loan_id' => $loan->id,
                'type' => $loan->type,
                'montant' => $loan->montant,
                'motif_rejet' => $motifRejet,
                'statut' => 'Rejeté'
            ]
        );
    }

    /**
     * Marquer toutes les notifications d'un user comme lues
     */
    public function marquerToutCommeLu(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->where('lu', false)
            ->update([
                'lu' => true,
                'lu_at' => now()
            ]);
    }

    /**
     * Notification paie effectuée (Collaborateur)
     */
    public function paieEffectuee($paie, $collaborator)
    {
        if (!$collaborator->user) return;

        $this->envoyerNotification(
            $collaborator->user,
            'paie',
            'Paie effectuée 💰',
            "Votre paie du mois de {$paie->mois} {$paie->annee} a été traitée. Montant net: " . number_format($paie->net_a_payer, 0, ',', ' ') . " FCFA",
            [
                'paie_id' => $paie->id,
                'mois' => $paie->mois,
                'annee' => $paie->annee,
                'salaire_base' => $paie->salaire_base,
                'prime' => $paie->prime,
                'indemnite' => $paie->indemnite,
                'retenue' => $paie->retenue,
                'net_a_payer' => $paie->net_a_payer,
                'action_url' => '/dashboard/payroll'
            ]
        );
    }

    /**
     * Notification nouvelle note de frais (Admin/Comptable)
     */
    public function nouvelleNoteDeFrais($expense, $collaborator)
    {
        $destinataires = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Admin Général', 'Administrateur', 'Admin', 'Comptable']);
        })->get();

        $nomComplet = $this->getNomComplet($collaborator);

        foreach ($destinataires as $user) {
            $this->envoyerNotification(
                $user,
                'expense',
                'Nouvelle note de frais',
                "{$nomComplet} a soumis une note de frais ({$expense->categorie}) de " . number_format($expense->montant, 0, ',', ' ') . " FCFA",
                [
                    'expense_id' => $expense->id,
                    'collaborator_id' => $collaborator->id,
                    'collaborator_name' => $nomComplet,
                    'categorie' => $expense->categorie,
                    'montant' => $expense->montant,
                    'date' => $expense->date,
                    'description' => $expense->description,
                    'action_url' => '/dashboard/expenses'
                ]
            );
        }
    }

    /**
     * Notification note de frais approuvée (Collaborateur)
     */
    public function noteDeFraisApprouvee($expense, $collaborator)
    {
        if (!$collaborator->user) return;

        $this->envoyerNotification(
            $collaborator->user,
            'expense',
            'Note de frais approuvée ✅',
            "Votre note de frais ({$expense->categorie}) de " . number_format($expense->montant, 0, ',', ' ') . " FCFA a été approuvée",
            [
                'expense_id' => $expense->id,
                'categorie' => $expense->categorie,
                'montant' => $expense->montant,
                'date' => $expense->date,
                'statut' => 'Approuvée'
            ]
        );
    }

    /**
     * Notification note de frais rejetée (Collaborateur)
     */
    public function noteDeFraisRejetee($expense, $collaborator, $motifRejet = null)
    {
        if (!$collaborator->user) return;

        $message = "Votre note de frais ({$expense->categorie}) de " . number_format($expense->montant, 0, ',', ' ') . " FCFA a été refusée";
        if ($motifRejet) {
            $message .= ". Motif: {$motifRejet}";
        }

        $this->envoyerNotification(
            $collaborator->user,
            'expense',
            'Note de frais refusée ❌',
            $message,
            [
                'expense_id' => $expense->id,
                'categorie' => $expense->categorie,
                'montant' => $expense->montant,
                'motif_rejet' => $motifRejet,
                'statut' => 'Rejetée'
            ]
        );
    }

    /**
     * Notification note de frais remboursée (Collaborateur)
     */
    public function noteDeFraisRemboursee($expense, $collaborator)
    {
        if (!$collaborator->user) return;

        $this->envoyerNotification(
            $collaborator->user,
            'expense',
            'Note de frais remboursée 💳',
            "Votre note de frais ({$expense->categorie}) de " . number_format($expense->montant, 0, ',', ' ') . " FCFA a été remboursée",
            [
                'expense_id' => $expense->id,
                'categorie' => $expense->categorie,
                'montant' => $expense->montant,
                'statut' => 'Remboursée'
            ]
        );
    }
}
