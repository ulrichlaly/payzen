<?php

namespace App\Events;

use App\Models\Expense;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewExpenseRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $expense;

    public function __construct(Expense $expense)
    {
        $this->expense = $expense->load('collaborator.user');
    }

    public function broadcastOn(): Channel
    {
        return new Channel('admin-notifications');
    }

    public function broadcastAs(): string
    {
        return 'expense.requested';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->expense->id,
            'type' => 'expense_request',
            'title' => 'Nouvelle note de frais',
            'message' => "{$this->expense->collaborator->user->fullname} a soumis une note de frais ({$this->expense->categorie})",
            'data' => [
                'expense_id' => $this->expense->id,
                'collaborator' => $this->expense->collaborator->user->fullname,
                'categorie' => $this->expense->categorie,
                'montant' => $this->expense->montant,
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
