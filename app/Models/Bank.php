<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    // Definindo a tabela associada ao model
    protected $table = 'banks';

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'registration_date',
        'user_id',
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Outros relacionamentos podem ser definidos aqui, se necessário.
}
