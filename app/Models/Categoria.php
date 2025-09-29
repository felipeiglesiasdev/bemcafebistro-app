<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $fillable = ['nome'];

    // Desabilitando os timestamps (created_at, updated_at)
    public $timestamps = false;

    /**
     * Define a relação de que uma Categoria pode ter muitos Produtos.
     */
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'categoria_id');
    }
}

