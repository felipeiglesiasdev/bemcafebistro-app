@extends('layouts.app')

@section('content')
    <!-- Header do Dashboard -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-stone-800">Dashboard</h1>
        <p class="mt-2 text-lg text-stone-600">Bem-vindo(a) de volta, <span class="font-semibold">{{ Auth::user()->name }}</span>! Aqui está um resumo do seu bistrô.</p>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <!-- Faturamento Total -->
        <div class="p-6 bg-white rounded-2xl shadow-lg flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Faturamento Total</p>
                <p class="text-3xl font-bold text-stone-800">R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <i class="bi bi-currency-dollar text-3xl text-green-600"></i>
            </div>
        </div>
        <!-- Total de Vendas -->
        <div class="p-6 bg-white rounded-2xl shadow-lg flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Total de Vendas</p>
                <p class="text-3xl font-bold text-stone-800">{{ $totalVendas }}</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="bi bi-receipt text-3xl text-blue-600"></i>
            </div>
        </div>
        <!-- Produtos Cadastrados -->
        <div class="p-6 bg-white rounded-2xl shadow-lg flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Produtos Cadastrados</p>
                <p class="text-3xl font-bold text-stone-800">{{ $totalProdutos }}</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="bi bi-box-seam text-3xl text-yellow-600"></i>
            </div>
        </div>
        <!-- Itens em Estoque -->
        <div class="p-6 bg-white rounded-2xl shadow-lg flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-stone-500">Itens em Estoque</p>
                <p class="text-3xl font-bold text-stone-800">{{ $totalEstoque }}</p>
            </div>
            <div class="p-3 bg-red-100 rounded-full">
                <i class="bi bi-clipboard-data text-3xl text-red-600"></i>
            </div>
        </div>
    </div>

    <!-- Seção de Gráfico e Tabelas -->
    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Gráfico de Vendas -->
        <div class="lg:col-span-2 p-6 bg-white rounded-2xl shadow-lg">
            <h3 class="text-xl font-semibold text-stone-800 mb-4">Vendas nos Últimos 7 Dias</h3>
            <canvas id="salesChart"></canvas>
        </div>

        <!-- Produtos com Baixo Estoque -->
        <div class="p-6 bg-white rounded-2xl shadow-lg">
            <h3 class="text-xl font-semibold text-stone-800 mb-4">Produtos com Baixo Estoque</h3>
            <div class="space-y-4">
                @forelse ($produtosBaixoEstoque as $produto)
                    <div class="flex items-center justify-between">
                        <span class="text-stone-700">{{ $produto->nome }}</span>
                        <span class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 rounded-full">{{ $produto->total_quantidade }} un.</span>
                    </div>
                @empty
                    <p class="text-center text-stone-500 py-4">Nenhum produto com baixo estoque!</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tabela de Últimas Vendas -->
    <div class="mt-8 p-6 bg-white rounded-2xl shadow-lg">
        <h3 class="text-xl font-semibold text-stone-800 mb-4">Últimas Vendas</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b-2 border-stone-200">
                        <th class="p-4 text-stone-600">ID</th>
                        <th class="p-4 text-stone-600">Data</th>
                        <th class="p-4 text-stone-600">Total</th>
                        <th class="p-4 text-stone-600">Pagamento</th>
                        <th class="p-4 text-stone-600 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimasVendas as $venda)
                        <tr class="border-b border-stone-100">
                            <td class="p-4">{{ $venda->id }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</td>
                            <td class="p-4">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                            <td class="p-4">{{ $venda->forma_pagamento }}</td>
                            <td class="p-4 text-center">
                                <a href="{{ route('vendas.show', $venda->id) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="bi bi-eye-fill"></i> Visualizar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-stone-500">Nenhuma venda registrada ainda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script para o Gráfico -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labelsGrafico),
                    datasets: [{
                        label: 'Faturamento (R$)',
                        data: @json($dadosGrafico),
                        backgroundColor: 'rgba(111, 78, 55, 0.7)',
                        borderColor: 'rgba(111, 78, 55, 1)',
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection

