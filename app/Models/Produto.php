<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    protected $table = 'produtos';
    // Ajustado para refletir a chave estrangeira no banco de dados
    protected $fillable = ['nome', 'categoria_id', 'marca'];
    
    public $timestamps = false;

    /**
     * Define a relação de que um Produto pertence a uma Categoria.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Define a relação de que um Produto tem muitos registros no estoque.
     */
    public function estoque(): HasMany
    {
        return $this->hasMany(Estoque::class, 'produto_id');
    }

    /**
     * Define a relação de que um Produto aparece em vários itens de venda.
     */
    public function itensVenda(): HasMany
    {
        return $this->hasMany(ItemVenda::class, 'produto_id');
    }
}

