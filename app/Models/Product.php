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
        'stock_quantity',
        'category_id',
        'user_id',
        'product_code',
        'image',
        'status',
    ];

    // Relação com a categoria
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');  // A chave estrangeira é 'category_id' e a chave primária da categoria é 'id_category'
    }
    // Definir o relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
