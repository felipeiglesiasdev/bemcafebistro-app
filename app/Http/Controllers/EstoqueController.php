<?php

namespace App\Http\Controllers;

use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EstoqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $estoques = Estoque::with('produto')
            ->when($search, function ($query, $search) {
                // Filtra o estoque buscando pelo nome do produto relacionado
                return $query->whereHas('produto', function ($q) use ($search) {
                    $q->where('nome', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString(); // Mantém o parâmetro de busca na paginação

        return view('estoque.index', compact('estoques', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $produtos = Produto::orderBy('nome')->get();
        return view('estoque.create', compact('produtos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Limpa os valores monetários antes da validação
        $request->merge([
            'preco_compra' => $this->cleanCurrency($request->preco_compra),
            'preco_venda' => $this->cleanCurrency($request->preco_venda),
        ]);

        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:0',
            'preco_compra' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'data_compra' => 'required|date',
            'data_validade' => 'nullable|date|after_or_equal:data_compra',
        ]);

        Estoque::create($request->all());

        return redirect()->route('estoque.index')
            ->with('success', 'Registro de estoque criado com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estoque $estoque): View
    {
        $produtos = Produto::orderBy('nome')->get();
        return view('estoque.edit', compact('estoque', 'produtos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estoque $estoque): RedirectResponse
    {
        // Limpa os valores monetários antes da validação
        $request->merge([
            'preco_compra' => $this->cleanCurrency($request->preco_compra),
            'preco_venda' => $this->cleanCurrency($request->preco_venda),
        ]);

        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:0',
            'preco_compra' => 'required|numeric|min:0',
            'preco_venda' => 'required|numeric|min:0',
            'data_compra' => 'required|date',
            'data_validade' => 'nullable|date|after_or_equal:data_compra',
        ]);

        $estoque->update($request->all());

        return redirect()->route('estoque.index')
            ->with('success', 'Registro de estoque atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estoque $estoque): RedirectResponse
    {
        $estoque->delete();

        return redirect()->route('estoque.index')
            ->with('success', 'Registro de estoque excluído com sucesso.');
    }

    /**
     * Helper to convert currency string to float.
     */
    private function cleanCurrency(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }
        // Remove "R$ ", remove dots, and replace comma with dot
        $cleanedValue = str_replace(['.', ','], ['', '.'], preg_replace('/[^\d,.]/', '', $value));
        return (float) $cleanedValue;
    }
}

