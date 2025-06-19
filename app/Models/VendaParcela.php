<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaParcela extends Model
{
    use HasFactory;

    protected $table = 'venda_parcelas';

    protected $fillable = [
        'sale_id',
        'numero_parcela',
        'valor',
        'data_vencimento',
        'status',
        'pago_em',
    ];

    // Relacionamento com a venda
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
} 