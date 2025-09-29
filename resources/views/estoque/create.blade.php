@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Adicionar Registro de Estoque</h1>
        <a href="{{ route('estoque.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <strong>Ops!</strong> Havia alguns problemas com sua entrada.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('estoque.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="produto_id" class="block text-gray-700 text-sm font-bold mb-2">Produto:</label>
                    <select name="produto_id" id="produto_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Digite para buscar um produto...">
                        <option value="">Selecione um produto</option>
                        @foreach ($produtos as $produto)
                            <option value="{{ $produto->id }}" {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                                {{ $produto->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantidade" class="block text-gray-700 text-sm font-bold mb-2">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidade" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('quantidade') }}">
                </div>

                <div class="mb-4">
                    <label for="preco_compra" class="block text-gray-700 text-sm font-bold mb-2">Preço de Compra:</label>
                    <input type="text" name="preco_compra" id="preco_compra" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('preco_compra') }}">
                </div>

                <div class="mb-4">
                    <label for="preco_venda" class="block text-gray-700 text-sm font-bold mb-2">Preço de Venda:</label>
                    <input type="text" name="preco_venda" id="preco_venda" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('preco_venda') }}">
                </div>

                <div class="mb-4">
                    <label for="data_compra" class="block text-gray-700 text-sm font-bold mb-2">Data da Compra:</label>
                    <input type="date" name="data_compra" id="data_compra" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('data_compra') }}">
                </div>

                <div class="mb-4">
                    <label for="data_validade" class="block text-gray-700 text-sm font-bold mb-2">Data de Validade (Opcional):</label>
                    <input type="date" name="data_validade" id="data_validade" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('data_validade') }}">
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Salvar Registro
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tom Select JS -->
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
        let value = input.value;
        value = value.replace(/\D/g, "");
        value = (value / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        input.value = value;
    };

    precoCompraInput.addEventListener('input', () => applyCurrencyMask(precoCompraInput));
    precoVendaInput.addEventListener('input', () => applyCurrencyMask(precoVendaInput));
});
</script>
@endsection

