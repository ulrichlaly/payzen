<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'photo_url',
        'date_naissance',
        'email',
        'telephone',
        'adresse',
        'genre',
        'date_embauche',
        'salaire_base',
        'poste',
        'departement',
        'statut',
        'situation_familiale',
        'nombre_enfants',
        'type_contrat',
        'duree_contrat',
        'date_fin_contrat',
        'heures_travail',
        'jours_conges',
        'iban',
        'notes_parcours',
    ];

    protected $casts = [
        'salaire_base' => 'decimal:2',
        'heures_travail' => 'decimal:2',
        'nombre_enfants' => 'integer',
        'jours_conges' => 'integer',
        'date_embauche' => 'date',
        'date_naissance' => 'date',
        'date_fin_contrat' => 'date',
    ];

    /**
     * Relation : un collaborateur appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paies()
    {
        return $this->hasMany(Paie::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
