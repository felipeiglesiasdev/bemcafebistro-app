<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VendaController extends Controller
{
    /**
     * Exibe o histórico de vendas.
     */
    public function index(): View
    {
        $vendas = Venda::orderBy('data_venda', 'desc')->paginate(10);
        return view('vendas.index', compact('vendas'));
    }

    /**
     * Mostra o formulário para criar uma nova venda.
     */
    public function create(): View
    {
        // Carrega todos os produtos, com a soma total de sua quantidade em estoque.
        // Isso garante que a lista de produtos sempre apareça na tela de vendas.
        $produtosDisponiveis = Produto::with(['estoque' => function ($query) {
                // Carrega o primeiro lote de estoque com quantidade para pegar o preço de venda (FIFO)
                $query->where('quantidade', '>', 0)->orderBy('data_validade', 'asc');
            }])
            ->withSum('estoque', 'quantidade') // Gera a propriedade 'estoque_sum_quantidade'
            ->orderBy('nome')
            ->get();

        return view('vendas.create', compact('produtosDisponiveis'));
    }

    /**
     * Salva uma nova venda no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'itens' => 'required|array|min:1',
            'itens.*.id' => 'required|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'forma_pagamento' => 'required|string',
            'total' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Cria a venda
            $venda = Venda::create([
                'data_venda' => now(),
                'total' => $request->total,
                'forma_pagamento' => $request->forma_pagamento,
            ]);

            // 2. Itera sobre os itens do carrinho para criar os ItensVenda e dar baixa no estoque
            foreach ($request->itens as $item) {
                $produto = Produto::find($item['id']);
                $quantidadeNecessaria = $item['quantidade'];

                // Pega os lotes de estoque ordenados por validade (FIFO)
                $lotesEstoque = $produto->estoque()->where('quantidade', '>', 0)->orderBy('data_validade', 'asc')->get();

                // Verifica se o estoque total é suficiente
                if ($lotesEstoque->sum('quantidade') < $quantidadeNecessaria) {
                    throw new \Exception("Estoque insuficiente para o produto: {$produto->nome}");
                }
                
                // Cria o item da venda com o preço do primeiro lote
                ItemVenda::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidadeNecessaria,
                    'preco_unitario' => $lotesEstoque->first()->preco_venda,
                    'subtotal' => $quantidadeNecessaria * $lotesEstoque->first()->preco_venda,
                ]);

                // Dá baixa no estoque, lote a lote
                foreach ($lotesEstoque as $lote) {
                    if ($quantidadeNecessaria <= 0) break;

                    $removerDoLote = min($quantidadeNecessaria, $lote->quantidade);
                    $lote->decrement('quantidade', $removerDoLote);
                    $quantidadeNecessaria -= $removerDoLote;
                }
            }
        });

        return redirect()->route('vendas.index')->with('success', 'Venda realizada com sucesso!');
    }

    /**
     * Mostra os detalhes de uma venda específica.
     */
    public function show(Venda $venda): View
    {
        $venda->load('itens.produto');
        return view('vendas.show', compact('venda'));
    }

    /**
     * Remove uma venda e restaura o estoque.
     */
    public function destroy(Venda $venda)
    {
        DB::transaction(function () use ($venda) {
            foreach ($venda->itens as $item) {
                $estoque = Estoque::where('produto_id', $item->produto_id)->first();
                if ($estoque) {
                    $estoque->increment('quantidade', $item->quantidade);
                }
            }
            
            $venda->itens()->delete();
            $venda->delete();
        });

        return redirect()->route('vendas.index')->with('success', 'Venda cancelada e estoque restaurado com sucesso!');
    }
}

