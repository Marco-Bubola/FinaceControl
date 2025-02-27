<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'price_sale',
    ];

    // Relacionamento com a Venda
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // Relacionamento com o Produto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
