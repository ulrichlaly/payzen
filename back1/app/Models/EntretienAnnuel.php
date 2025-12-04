<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntretienAnnuel extends Model
{
    use HasFactory;

    protected $table = 'entretiens_annuels';

    protected $fillable = [
        'collaborator_id',
        'date_entretien',
        'evaluateur',
        'objectifs_atteints',
        'competences_techniques',
        'competences_comportementales',
        'points_forts',
        'axes_amelioration',
        'objectifs_futurs'
    ];

    protected $casts = [
        'date_entretien' => 'date',
        'objectifs_atteints' => 'integer',
        'competences_techniques' => 'integer',
        'competences_comportementales' => 'integer',
    ];

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
