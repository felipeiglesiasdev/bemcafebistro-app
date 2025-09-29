<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';
    protected $fillable = ['nome', 'categoria', 'marca'];
    public $timestamps = false; // já que não tem created_at/updated_at
    // RELAÇÃO: Produto tem muitos registros no estoque
    public function estoque()
    {
        return $this->hasMany(Estoque::class, 'produto_id');
    }
    // RELAÇÃO: Produto aparece em vários itens de venda
    public function itensVenda()
    {
        return $this->hasMany(ItemVenda::class, 'produto_id');
    }
}
