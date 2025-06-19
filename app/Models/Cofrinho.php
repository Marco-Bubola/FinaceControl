<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cofrinho extends Model
{
    use HasFactory;

    protected $table = 'cofrinhos';

    protected $fillable = [
        'user_id',
        'nome',
        'meta_valor',
        'status',
    ];

    // Relacionamento com usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com lançamentos do cashbook
    public function cashbooks()
    {
        return $this->hasMany(Cashbook::class, 'cofrinho_id');
    }
} 