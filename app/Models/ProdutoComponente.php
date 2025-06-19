<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoComponente extends Model
{
    use HasFactory;

    protected $table = 'produto_componentes';

    protected $fillable = [
        'kit_produto_id',
        'componente_produto_id',
        'quantidade',
    ];

    // Produto kit
    public function kit()
    {
        return $this->belongsTo(Product::class, 'kit_produto_id');
    }

    // Produto componente
    public function componente()
    {
        return $this->belongsTo(Product::class, 'componente_produto_id');
    }
} 