@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Detalhes da Venda #{{ $venda->id }}</h1>
        <a href="{{ route('vendas.index') }}" class="text-gray-600 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center">
             <i class="bi bi-arrow-left mr-2"></i>
            Voltar para a Lista
        </a>
    </div>

    <div class="bg-white shadow-2xl rounded-2xl p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-b pb-6 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Data da Venda</h3>
                <p class="text-lg text-gray-800">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Forma de Pagamento</h3>
                <p class="text-lg text-gray-800">{{ $venda->forma_pagamento }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Total da Venda</h3>
                <p class="text-2xl font-bold text-amber-800">R$ {{ number_format($venda->total, 2, ',', '.') }}</p>
            </div>
        </div>

        <h2 class="text-xl font-bold text-stone-800 mb-4">Itens Inclusos</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Produto</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase">Quantidade</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Preço Unitário</th>
                        <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($venda->itens as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $item->produto->nome ?? 'Produto não encontrado' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-600">{{ $item->quantidade }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection