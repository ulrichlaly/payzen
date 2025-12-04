<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'type',
        'nom_fichier',
        'chemin',
        'extension',
        'taille',
        'description',
        'date_expiration',
        'statut',
    ];

    protected $casts = [
        'date_expiration' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec Collaborator
     */
    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
