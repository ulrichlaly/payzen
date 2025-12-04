<?php

namespace App\Events;

use App\Models\Loan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLoanRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $loan;

    public function __construct(Loan $loan)
    {
        $this->loan = $loan->load('collaborator.user');
    }

    /**
     * Canal de diffusion
     */
    public function broadcastOn(): Channel
    {
        return new Channel('admin-notifications');
    }

    /**
     * Nom de l'événement
     */
    public function broadcastAs(): string
    {
        return 'loan.requested';
    }

    /**
     * Données à envoyer
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->loan->id,
            'type' => 'loan_request',
            'title' => 'Nouvelle demande de prêt',
            'message' => "{$this->loan->collaborator->user->fullname} a soumis une demande de {$this->loan->type}",
            'data' => [
                'loan_id' => $this->loan->id,
                'collaborator' => $this->loan->collaborator->user->fullname,
                'montant' => $this->loan->montant,
                'type' => $this->loan->type,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
