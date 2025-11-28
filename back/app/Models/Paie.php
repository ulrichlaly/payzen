<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paie extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'mois',
        'annee',
        'salaire_base',
        'prime',
        'indemnite',
        'retenue',
        'deduction_pret',
        'net_a_payer',
        'commentaire'
    ];


    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }
}
