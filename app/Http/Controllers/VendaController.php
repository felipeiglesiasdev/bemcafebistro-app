<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class VendaController extends Controller
{
    /**
     * Exibe a lista de todas as vendas.
     */
    public function index(): View
    {
        $vendas = Venda::orderBy('data_venda', 'desc')->paginate(10);
        return view('vendas.index', compact('vendas'));
    }

    /**
     * Exibe o formulário para criar uma nova venda.
     */
    public function create(): View
    {
        // Pega apenas os produtos que têm estoque, agrupando para somar as quantidades
        $produtosEmEstoque = Produto::select('produtos.id', 'produtos.nome', 'estoque.preco_venda', DB::raw('SUM(estoque.quantidade) as quantidade_total'))
            ->join('estoque', 'produtos.id', '=', 'estoque.produto_id')
            ->where('estoque.quantidade', '>', 0)
            ->groupBy('produtos.id', 'produtos.nome', 'estoque.preco_venda')
            ->orderBy('produtos.nome', 'asc')
            ->get();
            
        return view('vendas.create', compact('produtosEmEstoque'));
    }

    /**
     * Armazena uma nova venda no banco de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'forma_pagamento' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.produto_id' => 'required|integer|exists:produtos,id',
            'items.*.quantidade' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Criar a Venda
            $venda = Venda::create([
                'data_venda' => now(),
                'total' => 0, // Total será calculado e atualizado
                'forma_pagamento' => $request->forma_pagamento,
            ]);

            $totalVenda = 0;

            foreach ($request->items as $itemData) {
                $estoqueItem = Estoque::where('produto_id', $itemData['produto_id'])
                                        ->where('quantidade', '>=', $itemData['quantidade'])
                                        ->orderBy('data_validade', 'asc') // Usa o lote mais antigo primeiro
                                        ->first();

                if (!$estoqueItem) {
                    throw new \Exception('Produto ' . $itemData['produto_id'] . ' sem estoque suficiente.');
                }

                $subtotal = $itemData['quantidade'] * $estoqueItem->preco_venda;
                $totalVenda += $subtotal;

                // 2. Criar o ItemVenda
                ItemVenda::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $itemData['produto_id'],
                    'quantidade' => $itemData['quantidade'],
                    'preco_unitario' => $estoqueItem->preco_venda,
                    'subtotal' => $subtotal,
                ]);

                // 3. Atualizar o Estoque
                $estoqueItem->decrement('quantidade', $itemData['quantidade']);
            }

            // 4. Atualizar o total da venda
            $venda->update(['total' => $totalVenda]);

            DB::commit();

            return redirect()->route('vendas.index')->with('success', 'Venda registrada com sucesso!');
        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Falha ao registrar a venda: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Exibe os detalhes de uma venda específica.
     */
    public function show(Venda $venda): View
    {
        // Carrega os itens e os produtos associados para exibição
        $venda->load('itens.produto');
        return view('vendas.show', compact('venda'));
    }

    /**
     * Remove uma venda e reverte o estoque.
     */
    public function destroy(Venda $venda): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Para cada item da venda, retorna a quantidade ao estoque
            foreach ($venda->itens as $item) {
                // Encontra o primeiro registro de estoque para o produto (ou cria um novo se não existir)
                $estoque = Estoque::where('produto_id', $item->produto_id)->orderBy('data_compra', 'desc')->first();

                if ($estoque) {
                    $estoque->increment('quantidade', $item->quantidade);
                } else {
                    // Se por algum motivo não houver mais registro de estoque, crie um novo.
                    // Preços podem ser zerados ou recuperados de outra lógica se necessário
                    Estoque::create([
                        'produto_id' => $item->produto_id,
                        'quantidade' => $item->quantidade,
                        'preco_compra' => $item->produto->estoque->first()->preco_compra ?? 0,
                        'preco_venda' => $item->preco_unitario,
                        'data_compra' => now(),
                    ]);
                }
            }

            // Deleta os itens da venda e depois a venda
            $venda->itens()->delete();
            $venda->delete();

            DB::commit();

            return redirect()->route('vendas.index')->with('success', 'Venda cancelada e estoque revertido com sucesso!');

        } catch (Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Falha ao cancelar a venda: ' . $e->getMessage());
        }
    }
}