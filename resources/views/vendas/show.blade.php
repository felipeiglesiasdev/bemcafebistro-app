@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Detalhes da Venda #{{ $venda->id }}</h1>
        <a href="{{ route('vendas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Voltar para o Histórico
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b">
            <div>
                <h3 class="text-sm text-gray-500 uppercase font-semibold">Data da Venda</h3>
                <p class="text-lg text-gray-800">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y \à\s H:i') }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 uppercase font-semibold">Forma de Pagamento</h3>
                <p class="text-lg text-gray-800">{{ $venda->forma_pagamento }}</p>
            </div>
            <div>
                <h3 class="text-sm text-gray-500 uppercase font-semibold">Valor Total</h3>
                <p class="text-2xl font-bold text-green-600">R$ {{ number_format($venda->total, 2, ',', '.') }}</p>
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-700 mb-4">Itens Inclusos na Venda</h2>
        <div class="overflow-x-auto">
             <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produto</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantidade</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Preço Unitário</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($venda->itens as $item)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{ $item->produto->nome ?? 'Produto não encontrado' }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                {{ $item->quantidade }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right font-semibold">
                                R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
             </table>
        </div>
    </div>
</div>
@endsection
