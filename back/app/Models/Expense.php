<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'categorie',
        'montant',
        'date',
        'description',
        'justificatif',
        'statut',
        'motif_rejet',
    ];

    protected $casts = [
        'date' => 'date',
        'montant' => 'decimal:2',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
