<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Definindo a tabela associada ao model
    protected $table = 'invoice';

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'id_bank',
        'description',
        'installments',
        'value',
        'user_id',
        'category_id',
    ];

    // Relacionamento com o banco
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank');
    }

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relacionamento com a categoria
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
