<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filho extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'filhos';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'confirmacao_id',
        'nome',
        'idade',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the confirmacao that owns the filho.
     */
    public function confirmacao(): BelongsTo
    {
        return $this->belongsTo(Confirmacao::class);
    }
}
