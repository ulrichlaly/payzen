<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcoursProfessionnel extends Model
{
    use HasFactory;

    protected $table = 'parcours_professionnels';

    protected $fillable = [
        'collaborator_id',
        'type_mouvement',
        'date_debut',
        'date_fin',
        'poste',
        'departement',
        'notes'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
