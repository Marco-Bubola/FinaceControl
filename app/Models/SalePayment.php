<?php
// app/Models/SalePayment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    // Definir os atributos da tabela, se necessário
    protected $fillable = ['sale_id', 'amount_paid', 'payment_method', 'payment_date', 'created_at'];

    // Relacionamento com a tabela sales (venda)
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
