@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Histórico de Vendas</h1>
        <a href="{{ route('vendas.create') }}" class="bg-amber-800 hover:bg-amber-900 text-white font-bold py-2 px-4 rounded-lg flex items-center transition-transform duration-300 ease-in-out transform hover:scale-105">
            <i class="bi bi-plus-circle-fill mr-2"></i>
            Registrar Nova Venda
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

    <div class="bg-white shadow-2xl rounded-2xl overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID Venda</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data e Hora</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Forma de Pagamento</th>
                    <th class="px-6 py-4 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($vendas as $venda)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">#{{ $venda->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-semibold">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $venda->forma_pagamento }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('vendas.show', $venda->id) }}" class="bg-sky-500 hover:bg-sky-600 text-white font-bold p-2 rounded-lg inline-flex items-center transition-transform duration-300 ease-in-out transform hover:scale-110">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <button type="button" class="cursor-pointer bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-lg inline-flex items-center delete-btn transition-transform duration-300 ease-in-out transform hover:scale-110" data-url="{{ route('vendas.destroy', $venda->id) }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">Nenhuma venda registrada ainda.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $vendas->links() }}
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 md:w-1/3 transform transition-transform duration-300 scale-95">
        <h3 class="text-xl font-bold text-stone-800 mb-4">Confirmar Cancelamento</h3>
        <p class="text-gray-600">Você tem certeza que deseja cancelar esta venda? Esta ação não poderá ser desfeita e os itens retornarão ao estoque.</p>
        <div class="mt-8 flex justify-end space-x-4">
            <button id="cancelBtn" type="button" class="cursor-pointer bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                Manter Venda
            </button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="cursor-pointer bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    Sim, Cancelar Venda
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