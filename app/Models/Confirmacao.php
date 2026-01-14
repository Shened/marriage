<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Confirmacao extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'confirmacoes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nome',
        'apelido',
        'idade',
        'email',
        'telefone',
        'presenca',
        'tem_parceiro',
        'tem_filhos',
        'restricoes',
        'ip_address',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tem_parceiro' => 'boolean',
        'tem_filhos' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parceiro associated with the confirmacao.
     */
    public function parceiro(): HasOne
    {
        return $this->hasOne(Parceiro::class);
    }

    /**
     * Get the filhos associated with the confirmacao.
     */
    public function filhos(): HasMany
    {
        return $this->hasMany(Filho::class);
    }

    /**
     * Scope a query to only include confirmed attendees.
     */
    public function scopeConfirmados($query)
    {
        return $query->where('presenca', 'sim');
    }

    /**
     * Scope a query to only include declined attendees.
     */
    public function scopeNaoConfirmados($query)
    {
        return $query->where('presenca', 'nao');
    }

    /**
     * Get total number of guests (including partners and children).
     */
    public function getTotalConvidadosAttribute(): int
    {
        $total = 1; // The person who confirmed
        
        if ($this->tem_parceiro && $this->parceiro) {
            $total++;
        }
        
        if ($this->tem_filhos) {
            $total += $this->filhos->count();
        }
        
        return $total;
    }
}
