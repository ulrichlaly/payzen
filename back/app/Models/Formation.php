<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    use HasFactory;

    protected $table = 'formations';

    protected $fillable = [
        'collaborator_id',
        'titre',
        'organisme',
        'date_debut',
        'date_fin',
        'duree_heures',
        'cout',
        'certification_obtenue',
        'notes'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'duree_heures' => 'integer',
        'cout' => 'decimal:2',
        'certification_obtenue' => 'boolean',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
