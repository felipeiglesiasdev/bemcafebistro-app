<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Venda;
use App\Models\Estoque;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal com estatísticas e gráficos.
     */
    public function index(): View
    {
        // 1. Indicadores Principais
        $faturamentoTotal = Venda::sum('total');
        $totalVendas = Venda::count();
        $totalProdutos = Produto::count();
        $totalEstoque = Estoque::sum('quantidade');

        // 2. Dados para o Gráfico de Vendas (Últimos 7 dias)
        $vendasUltimosSeteDias = Venda::select(
                DB::raw('DATE(data_venda) as dia'),
                DB::raw('SUM(total) as faturamento_diario')
            )
            ->where('data_venda', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('dia')
            ->orderBy('dia', 'asc')
            ->get();

        // Formata os dados para o Chart.js
        $labelsGrafico = $vendasUltimosSeteDias->pluck('dia')->map(function ($date) {
            return Carbon::parse($date)->format('d/m');
        })->toArray();
        
        $dadosGrafico = $vendasUltimosSeteDias->pluck('faturamento_diario')->toArray();

        // 3. Produtos com Baixo Estoque (Top 5)
        $produtosBaixoEstoque = Produto::select('produtos.id', 'produtos.nome', DB::raw('SUM(estoque.quantidade) as total_quantidade'))
            ->join('estoque', 'produtos.id', '=', 'estoque.produto_id')
            ->groupBy('produtos.id', 'produtos.nome')
            ->orderBy('total_quantidade', 'asc')
            ->having('total_quantidade', '>', 0) // Apenas produtos com alguma quantidade
            ->limit(8)
            ->get();

        // 4. Últimas Vendas (Top 5)
        $ultimasVendas = Venda::orderBy('data_venda', 'desc')->limit(5)->get();
        
        return view('dashboard.index', compact(
            'faturamentoTotal',
            'totalVendas',
            'totalProdutos',
            'totalEstoque',
            'labelsGrafico',
            'dadosGrafico',
            'produtosBaixoEstoque',
            'ultimasVendas'
        ));
    }
}

