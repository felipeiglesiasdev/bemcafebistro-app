@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Editar Registro de Estoque</h1>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <strong class="font-bold">Ops!</strong>
            <span class="block sm:inline">Havia alguns problemas com sua entrada.</span>
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl p-8">
        <form action="{{ route('estoque.update', $estoque->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="produto_id" class="block text-stone-800 text-sm font-bold mb-2">Produto:</label>
                    <select name="produto_id" id="produto_id" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" placeholder="Digite para buscar um produto...">
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $produto)
                            <option value="{{ $produto->id }}" {{ old('produto_id', $estoque->produto_id) == $produto->id ? 'selected' : '' }}>
                                {{ $produto->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantidade" class="block text-stone-800 text-sm font-bold mb-2">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidade" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('quantidade', $estoque->quantidade) }}">
                </div>

                <div class="mb-4">
                    <label for="preco_compra" class="block text-stone-800 text-sm font-bold mb-2">Preço de Compra:</label>
                    <input type="text" name="preco_compra" id="preco_compra" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('preco_compra', number_format($estoque->preco_compra, 2, ',', '.')) }}">
                </div>

                <div class="mb-4">
                    <label for="preco_venda" class="block text-stone-800 text-sm font-bold mb-2">Preço de Venda:</label>
                    <input type="text" name="preco_venda" id="preco_venda" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('preco_venda', number_format($estoque->preco_venda, 2, ',', '.')) }}">
                </div>

                <div class="mb-4">
                    <label for="data_compra" class="block text-stone-800 text-sm font-bold mb-2">Data da Compra:</label>
                    <input type="date" name="data_compra" id="data_compra" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('data_compra', $estoque->data_compra) }}">
                </div>

                <div class="mb-4">
                    <label for="data_validade" class="block text-stone-800 text-sm font-bold mb-2">Data de Validade (Opcional):</label>
                    <input type="date" name="data_validade" id="data_validade" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('data_validade', $estoque->data_validade) }}">
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 space-x-4">
                <a href="{{ route('estoque.index') }}" class="text-gray-600 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="cursor-pointer bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900 transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Atualizar Registro
                </button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inicializa o Tom Select para busca de produtos
    new TomSelect('#produto_id',{
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

    // Funcionalidade de Máscara de Moeda
    const precoCompraInput = document.getElementById('preco_compra');
    const precoVendaInput = document.getElementById('preco_venda');

    const applyCurrencyMask = (input) => {
        if (!input.value) return;
        let value = input.value;
        value = value.replace(/\D/g, "");
        value = (value / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        input.value = value;
    };
    
    // Aplica a máscara nos campos ao carregar a página e a cada nova entrada
    applyCurrencyMask(precoCompraInput);
    applyCurrencyMask(precoVendaInput);

    precoCompraInput.addEventListener('input', () => applyCurrencyMask(precoCompraInput));
    precoVendaInput.addEventListener('input', () => applyCurrencyMask(precoVendaInput));
});
</script>
@endsection