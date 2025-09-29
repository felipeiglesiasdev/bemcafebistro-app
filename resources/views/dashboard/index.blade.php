@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-amber-900 mb-6">Dashboard</h1>
    
    <p class="mb-8 text-amber-800">Bem-vindo(a) ao painel administrativo do Bem Café Bistrô.</p>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-amber-900">Vendas do Dia</h3>
            <p class="text-2xl font-bold text-amber-800 mt-2">R$ 1.250,00</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-amber-900">Produtos em Estoque</h3>
            <p class="text-2xl font-bold text-amber-800 mt-2">152 itens</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-amber-900">Novos Clientes</h3>
            <p class="text-2xl font-bold text-amber-800 mt-2">8</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-amber-900">Pedidos Pendentes</h3>
            <p class="text-2xl font-bold text-amber-800 mt-2">3</p>
        </div>
    </div>
</div>
@endsection