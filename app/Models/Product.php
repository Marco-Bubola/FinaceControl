<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'price_sale',
        'stock_quantity',
        'category_id',
        'user_id',
        'product_code',
        'image',
        'status', // ativo, inativo, descontinuado
        'tipo', // simples, kit
        'custos_adicionais', // decimal(10,2)
    ];
    /**
     * status: ativo, inativo, descontinuado
     * tipo: simples, kit
     * custos_adicionais: decimal(10,2)
     */
    // Relacionamento com os itens de venda
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    } // Relação com a categoria
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');  // A chave estrangeira é 'category_id' e a chave primária da categoria é 'id_category'
    }
    // Definir o relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Se for kit, retorna os componentes
    public function componentes()
    {
        return $this->hasMany(\App\Models\ProdutoComponente::class, 'kit_produto_id');
    }
}
