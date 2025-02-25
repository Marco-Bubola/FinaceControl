<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Defina explicitamente o nome da tabela
    protected $table = 'category';

    // Definindo os campos que podem ser preenchidos
    protected $fillable = [
        'parent_id',
        'name',
        'desc_category',
        'hexcolor_category',
        'icone',
        'descricao_detalhada',
        'tipo',
        'limite_orcamento',
        'compartilhavel',
        'tags',
        'regras_auto_categorizacao',
        'id_bank',
        'id_clients',
        'id_produtos_clientes',
        'historico_alteracoes',
        'is_active',
        'description',
        'user_id',
        'type',
    ];

    // Definindo os relacionamentos (Exemplo de banco e cliente)
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_clients');
    }

    // Se necessário, você pode adicionar outros métodos relacionados.
}
