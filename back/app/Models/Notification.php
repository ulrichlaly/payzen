<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'titre',
        'message',
        'data',
        'lu',
        'lu_at'
    ];

    protected $casts = [
        'data' => 'array',
        'lu' => 'boolean',
        'lu_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['temps_ecoule'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function marquerCommeLu(): void
    {
        $this->update([
            'lu' => true,
            'lu_at' => now()
        ]);
    }

    public function getTempsEcouleAttribute(): string
    {
        $diff = $this->created_at->diffInMinutes(now());

        if ($diff < 1) return "À l'instant";
        if ($diff < 60) return "{$diff} min";
        if ($diff < 1440) return $this->created_at->diffInHours(now()) . "h";
        if ($diff < 10080) return $this->created_at->diffInDays(now()) . "j";

        return $this->created_at->format('d/m/Y');
    }
}
