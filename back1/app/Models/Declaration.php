<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Declaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'paie_id',
        'type',
        'montant_total',
        'date_declaration',
        'reference',
        'details',
        'statut',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function paie()
    {
        return $this->belongsTo(Paie::class);
    }
}
