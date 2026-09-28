<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'tenant_id',
        'tipo',
        'nome',
        'email',
        'cpf_cnpj',
        'telefone',
        'data_nascimento',
        'sexo',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'data_nascimento' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function precos()
    {
        return $this->hasMany(ClientePreco::class);
    }
}
