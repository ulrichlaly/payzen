<?php

namespace App\Events;

use App\Models\Conge;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CongeStatusChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conge;
    public $status;

    public function __construct(Conge $conge, string $status)
    {
        $this->conge = $conge->load('collaborator.user');
        $this->status = $status;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user.' . $this->conge->collaborator->user_id);
    }

    public function broadcastAs(): string
    {
        return 'conge.status.changed';
    }

    public function broadcastWith(): array
    {
        $messages = [
            'approuvé' => 'Votre demande de congé a été approuvée',
            'refusé' => 'Votre demande de congé a été refusée',
        ];

        return [
            'id' => $this->conge->id,
            'type' => 'conge_status',
            'title' => 'Statut de congé mis à jour',
            'message' => $messages[$this->status] ?? 'Le statut de votre congé a changé',
            'data' => [
                'conge_id' => $this->conge->id,
                'status' => $this->status,
                'type' => $this->conge->type,
                'date_debut' => $this->conge->date_debut,
                'date_fin' => $this->conge->date_fin,
                'nb_jours' => $this->conge->nb_jours,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
