@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Gerenciamento de Estoque</h1>
        <a href="{{ route('estoque.create') }}" class="bg-amber-800 hover:bg-amber-900 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-transform duration-300 ease-in-out transform hover:scale-105">
            <i class="bi bi-plus-circle-fill mr-2"></i>
            Adicionar ao Estoque
        </a>
    </div>

    <div class="mb-6">
        <form action="{{ route('estoque.index') }}" method="GET">
            <div class="relative w-full md:w-1/3">
                <input type="text" name="search" placeholder="Buscar por nome do produto..." class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200" value="{{ $search ?? '' }}">
                <button type="submit" class="absolute inset-y-0 right-0 px-4 flex items-center text-stone-600 hover:text-amber-800 transition-colors duration-200">
                    <i class="bi bi-search font-bold"></i>
                </button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-2xl rounded-2xl overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produto</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantidade</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preço Compra</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Preço Venda</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data Compra</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data Validade</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($estoques as $item)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $item->produto->nome ?? 'Produto não encontrado' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs font-semibold">{{ $item->quantidade }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">R$ {{ number_format($item->preco_compra, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">R$ {{ number_format($item->preco_venda, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($item->data_compra)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->data_validade ? \Carbon\Carbon::parse($item->data_validade)->format('d/m/Y') : 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('estoque.edit', $item->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold p-2 rounded-lg inline-flex items-center transition-transform duration-300 ease-in-out transform hover:scale-110">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <button type="button" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg inline-flex items-center delete-btn transition-transform duration-300 ease-in-out transform hover:scale-110" data-url="{{ route('estoque.destroy', $item->id) }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">Nenhum registro de estoque encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $estoques->links() }}
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 md:w-1/3 transform transition-transform duration-300 scale-95">
        <h3 class="text-xl font-bold text-stone-800 mb-4">Confirmar Exclusão</h3>
        <p class="text-gray-600">Você tem certeza que deseja excluir este registro do estoque?</p>
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