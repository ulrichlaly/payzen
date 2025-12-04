<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'type',
        'montant',
        'duree',
        'montant_restant',
        'mensualite',
        'motif',
        'statut',
        'motif_rejet',
        'date_debut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'mensualite' => 'decimal:2',
        'date_debut' => 'date',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }

    // Calculer la mensualité automatiquement
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            $loan->mensualite = $loan->montant / $loan->duree;
            $loan->montant_restant = $loan->montant;
        });
    }
}
