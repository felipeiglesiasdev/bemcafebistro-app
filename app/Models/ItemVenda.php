<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemVenda extends Model
{
    protected $table = 'itens_venda';
    protected $fillable = ['venda_id', 'produto_id', 'quantidade', 'preco_unitario', 'subtotal'];

    public $timestamps = false;

    /**
     * Define a relação de que um ItemVenda pertence a uma Venda.
     */
    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    /**
     * Define a relação de que um ItemVenda pertence a um Produto.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
