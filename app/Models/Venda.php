<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    protected $table = 'vendas';
    protected $fillable = ['data_venda', 'total', 'forma_pagamento'];

    public $timestamps = false;

    /**
     * Define a relação de que uma Venda possui vários Itens de Venda.
     */
    public function itens(): HasMany
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }
}
