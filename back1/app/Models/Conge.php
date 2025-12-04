<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conge extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'type',
        'statut',
        'motif',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($conge) {
            if ($conge->date_debut && $conge->date_fin) {
                $conge->nb_jours = Carbon::parse($conge->date_debut)
                    ->diffInDays(Carbon::parse($conge->date_fin)) + 1;
            }
        });
    }

    public function collaborator()
    {
        return $this->belongsTo(Collaborator::class);
    }
}
