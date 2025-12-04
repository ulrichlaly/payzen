<?php

namespace App\Events;

use App\Models\Loan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoanStatusChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $loan;
    public $status;

    public function __construct(Loan $loan, string $status)
    {
        $this->loan = $loan->load('collaborator.user');
        $this->status = $status;
    }

    public function broadcastOn(): Channel
    {
        // Canal privé pour le collaborateur spécifique
        return new Channel('user.' . $this->loan->collaborator->user_id);
    }

    public function broadcastAs(): string
    {
        return 'loan.status.changed';
    }

    public function broadcastWith(): array
    {
        $messages = [
            'En cours' => 'Votre demande de prêt a été approuvée',
            'Rejeté' => 'Votre demande de prêt a été rejetée',
            'Remboursé' => 'Votre prêt a été totalement remboursé',
        ];

        return [
            'id' => $this->loan->id,
            'type' => 'loan_status',
            'title' => 'Statut de prêt mis à jour',
            'message' => $messages[$this->status] ?? 'Le statut de votre prêt a changé',
            'data' => [
                'loan_id' => $this->loan->id,
                'status' => $this->status,
                'type' => $this->loan->type,
                'montant' => $this->loan->montant,
                'motif_rejet' => $this->loan->motif_rejet,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
