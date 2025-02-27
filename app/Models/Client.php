<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    // Definindo a tabela associada ao model
    protected $table = 'clients';

    // Campos que podem ser preenchidos
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'user_id',
        'created_at',
        'updated_at',
    ];
    // No modelo Client
    public function sales()
    {
        return $this->hasMany(Sale::class);  // Um cliente pode ter várias vendas
    }

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
