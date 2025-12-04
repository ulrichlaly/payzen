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
        'date_approbation',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_restant' => 'decimal:2',
        'mensualite' => 'decimal:2',
        'date_debut' => 'date',
        'date_approbation' => 'date',
    ];

    /**
     * Relation avec le collaborateur
     */
    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }

    /**
     * Calculer automatiquement la mensualité
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            if ($loan->duree > 0) {
                $loan->mensualite = round($loan->montant / $loan->duree, 2);
            }

            if (!$loan->montant_restant) {
                $loan->montant_restant = $loan->montant;
            }
        });
    }

    /**
     * Accesseur pour obtenir le nom du collaborateur
     */
    public function getCollaboratorNameAttribute()
    {
        return $this->collaborator?->nom_complet ?? 'Collaborateur inconnu';
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('statut', $status);
        }
        return $query;
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeByType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }
}
