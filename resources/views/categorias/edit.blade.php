@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-amber-900 mb-6">Editar Categoria</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
            <strong>Opa!</strong> Algo deu errado com os dados inseridos.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('categorias.update', $categoria->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="nome" class="block text-amber-800 text-sm font-bold mb-2">Nome da Categoria:</label>
                <input type="text" name="nome" id="nome" value="{{ old('nome', $categoria->nome) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-end mt-6">
                 <a href="{{ route('categorias.index') }}" class="text-gray-600 font-bold py-2 px-4 mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-amber-800 text-white font-bold py-2 px-4 rounded-lg hover:bg-amber-900 transition-colors duration-200">
                    Atualizar Categoria
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
