<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoRecorrente extends Model
{
    use HasFactory;

    protected $table = 'lancamentos_recorrentes';

    protected $fillable = [
        'user_id',
        'descricao',
        'valor',
        'type_id',
        'category_id',
        'frequencia',
        'data_inicio',
        'proximo_vencimento',
        'data_fim',
        'ativo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id_type');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }
} 