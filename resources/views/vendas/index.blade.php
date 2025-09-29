@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-700">Histórico de Vendas</h1>
        <a href="{{ route('vendas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Nova Venda
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
     @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

     <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Nº de Itens</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pagamento</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vendas as $venda)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">#{{ $venda->id }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ \Carbon\Carbon::parse($venda->data_venda)->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        <span class="bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs">{{ $venda->itens->count() }}</span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm font-semibold">R$ {{ number_format($venda->total, 2, ',', '.') }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $venda->forma_pagamento }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <div class="flex items-center space-x-2">
                             <a href="{{ route('vendas.show', $venda->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold p-2 rounded inline-flex items-center" title="Visualizar Venda">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                            <button type="button" class="bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded inline-flex items-center delete-btn" data-url="{{ route('vendas.destroy', $venda->id) }}" title="Cancelar Venda">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">Nenhuma venda registrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $vendas->links() }}
    </div>

</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-opacity duration-300">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-1/3 transform transition-transform duration-300 scale-95">
        <h3 class="text-lg font-bold mb-4">Confirmar Cancelamento</h3>
        <p>Você tem certeza que deseja cancelar esta venda? Os itens serão retornados ao estoque.</p>
        <div class="mt-6 flex justify-end space-x-4">
            <button id="cancelBtn" type="button" class="cursor-pointer bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                Não, voltar
            </button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="cursor-pointer bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Sim, cancelar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
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
        }
    });
</script>
@endsection

