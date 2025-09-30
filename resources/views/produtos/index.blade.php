@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Produtos</h1>
        <a href="{{ route('produtos.create') }}" class="bg-amber-800 hover:bg-amber-900 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-transform duration-300 ease-in-out transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Adicionar Produto
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Categoria</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Marca</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($produtos as $produto)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $produto->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $produto->nome }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $produto->categoria->nome ?? 'Sem categoria' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $produto->marca }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('produtos.edit', $produto->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold p-2 rounded-lg inline-flex items-center transition-transform duration-300 ease-in-out transform hover:scale-110">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L14.732 3.732z"></path></svg>
                            </a>
                            <button type="button" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg inline-flex items-center delete-btn transition-transform duration-300 ease-in-out transform hover:scale-110" data-url="{{ route('produtos.destroy', $produto->id) }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">Nenhum produto encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $produtos->links() }}
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 md:w-1/3 transform transition-transform duration-300 scale-95">
        <h3 class="text-xl font-bold text-stone-800 mb-4">Confirmar Exclusão</h3>
        <p class="text-gray-600">Você tem certeza que deseja excluir este produto?</p>
        <div class="mt-8 flex justify-end space-x-4">
            <button id="cancelBtn" type="button" class="cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                Cancelar
            </button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="cursor-pointer bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    Confirmar Exclusão
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        const modalContent = deleteModal.querySelector('div');
        const deleteForm = document.getElementById('deleteForm');
        const cancelBtn = document.getElementById('cancelBtn');
        const deleteBtns = document.querySelectorAll('.delete-btn');

        deleteBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const url = btn.getAttribute('data-url');
                deleteForm.action = url;
                deleteModal.classList.remove('hidden');
                setTimeout(() => {
                    deleteModal.classList.remove('opacity-0');
                    modalContent.classList.remove('scale-95');
                }, 10);
            });
        });

        function closeModal() {
            modalContent.classList.add('scale-95');
            deleteModal.classList.add('opacity-0');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
            }, 300);
        }

        cancelBtn.addEventListener('click', closeModal);

        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                closeModal();
            }
        });
    });
</script>
@endsection