<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'vendas';
    protected $fillable = ['data_venda', 'total', 'forma_pagamento'];

    public $timestamps = false;

    // RELAÇÃO: Venda possui vários itens
    public function itens()
    {
        return $this->hasMany(ItemVenda::class, 'venda_id');
    }
}
