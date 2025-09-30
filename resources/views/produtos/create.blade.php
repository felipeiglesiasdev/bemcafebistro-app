@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Adicionar Novo Produto</h1>
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
        <form action="{{ route('produtos.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="nome" class="block text-stone-800 text-sm font-bold mb-2">Nome do Produto:</label>
                    <input type="text" name="nome" id="nome" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('nome') }}">
                </div>

                <div class="mb-4">
                    <label for="categoria_id" class="block text-stone-800 text-sm font-bold mb-2">Categoria:</label>
                    <select name="categoria_id" id="categoria_id" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200">
                        <option value="">Selecione uma categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4 md:col-span-2">
                    <label for="marca" class="block text-stone-800 text-sm font-bold mb-2">Marca:</label>
                    <input type="text" name="marca" id="marca" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ old('marca') }}">
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 space-x-4">
                <a href="{{ route('produtos.index') }}" class="text-gray-600 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="cursor-pointer bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900 transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Salvar Produto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection