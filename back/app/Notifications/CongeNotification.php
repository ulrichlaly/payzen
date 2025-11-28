<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CongeNotification extends Notification
{
    use Queueable;

    private $conge;

    public function __construct($conge)
    {
        $this->conge = $conge;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Nouvelle demande de congé de l’employé #{$this->conge->collaborator_id}.",
            'conge_id' => $this->conge->id,
            'type' => $this->conge->type,
            'nb_jours' => $this->conge->nb_jours,
        ];
    }
}
