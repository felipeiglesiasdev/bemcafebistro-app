@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Editar Categoria</h1>
        <a href="{{ route('categorias.index') }}" class="text-gray-600 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            Voltar
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <strong class="font-bold">Opa!</strong>
            <span class="block sm:inline">Algo deu errado com os dados inseridos.</span>
            <ul class="list-disc ml-5 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl p-8">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="nome" class="block text-stone-800 text-sm font-bold mb-2">Nome da Categoria:</label>
                <input type="text" name="nome" id="nome" value="{{ old('nome', $categoria->nome) }}" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" required>
            </div>

            <div class="flex items-center justify-end mt-8 space-x-4">
                 <a href="{{ route('categorias.index') }}" class="text-gray-600 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" class="cursor-pointer bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900 transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Atualizar Categoria
                </button>
            </div>
        </form>
    </div>
</div>
@endsection