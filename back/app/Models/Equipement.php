<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $table = 'equipements';

    protected $fillable = [
        'collaborator_id',
        'type_equipement',
        'marque',
        'modele',
        'numero_serie',
        'date_attribution',
        'date_retour',
        'etat',
        'valeur',
        'notes'
    ];

    protected $casts = [
        'date_attribution' => 'date',
        'date_retour' => 'date',
        'valeur' => 'decimal:2',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
