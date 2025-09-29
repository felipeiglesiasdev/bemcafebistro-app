<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{
    protected $table = 'estoque';
    protected $fillable = [
        'produto_id', 'quantidade', 'preco_compra',
        'preco_venda', 'data_compra', 'data_validade'
    ];

    public $timestamps = false;

    /**
     * Define a relação de que um registro de Estoque pertence a um Produto.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
