<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Definindo a tabela associada ao model
    protected $table = 'invoice';
    // Se o nome da chave primária for diferente de 'id', defina a chave primária manualmente
    protected $primaryKey = 'id_invoice';
    public $incrementing = true;
    protected $keyType = 'int';

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'id_bank',
        'description',
        'installments',
        'value',
        'user_id',
        'category_id',
        'invoice_date',
        'client_id',
        'dividida',
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
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    // Relacionamento com a categoria
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }
}
