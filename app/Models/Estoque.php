<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $table = 'estoque';
    protected $fillable = [
        'produto_id', 'quantidade', 'preco_compra',
        'preco_venda', 'data_compra', 'data_validade'
    ];

    public $timestamps = false;

    // RELAÇÃO: Estoque pertence a um produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
