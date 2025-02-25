<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'total_price',
        'status',
    ];

    // Relacionamento com o Cliente
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relacionamento com os Itens da Venda (Produtos)
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}

