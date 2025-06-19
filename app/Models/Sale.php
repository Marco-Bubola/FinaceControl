<?php

namespace App\Models;

use App\Models\SalePayment as ModelsSalePayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SalePayment;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'total_price',
        'status', // pendente, orcamento, confirmada, concluida, cancelada
        'payment_method',
        'tipo_pagamento', // a_vista, parcelado
        'parcelas', // int
    ];
    /**
     * status: pendente, orcamento, confirmada, concluida, cancelada
     * tipo_pagamento: a_vista, parcelado
     * parcelas: int
     */

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

    // Relacionamento com os Pagamentos da Venda
    public function payments()
    {
        return $this->hasMany(ModelsSalePayment::class);
    }

    // Relacionamento com as parcelas da venda
    public function parcelasVenda()
    {
        return $this->hasMany(VendaParcela::class, 'sale_id');
    }

    // Método para calcular o total pago
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount_paid'); // Soma todos os pagamentos dessa venda
    }

    // Método para calcular o saldo restante
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->total_price - $this->total_paid);
    }
}
