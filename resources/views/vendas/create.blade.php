@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4" x-data="vendaForm()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-stone-800">Registrar Nova Venda</h1>
    </div>

    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
            <strong class="font-bold">Erro!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('vendas.store') }}" method="POST" @submit.prevent="submitForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white shadow-2xl rounded-2xl p-8">
                <div class="flex items-end gap-4 mb-6">
                    <div class="flex-grow">
                        <label for="produto_select" class="block text-stone-800 text-sm font-bold mb-2">Adicionar Produto:</label>
                        <select id="produto_select" placeholder="Digite para buscar um produto..."></select>
                    </div>
                    <button @click="adicionarItem" type="button" class="cursor-pointer bg-amber-800 text-white font-bold py-3 px-6 rounded-lg hover:bg-amber-900 transition-all duration-300 ease-in-out transform hover:scale-105">
                        Adicionar
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Produto</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase">Qtd.</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Preço Unit.</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                                <th class="px-4 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <template x-if="items.length === 0">
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-gray-500">Nenhum item adicionado à venda.</td>
                                </tr>
                            </template>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800" x-text="item.nome"></td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <input type="number" x-model.number="item.quantidade" @input="updateSubtotal(index)" class="w-20 text-center border rounded-md py-1">
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600" x-text="formatCurrency(item.preco_venda)"></td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-800" x-text="formatCurrency(item.subtotal)"></td>
                                    <td class="px-4 py-3 text-sm text-center">
                                        <button @click="removerItem(index)" type="button" class="text-red-500 hover:text-red-700">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white shadow-2xl rounded-2xl p-8 h-fit">
                <h2 class="text-xl font-bold text-stone-800 mb-6">Fechamento</h2>
                <div class="mb-6">
                    <label for="forma_pagamento" class="block text-stone-800 text-sm font-bold mb-2">Forma de Pagamento:</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="shadow-sm appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-shadow duration-200">
                        <option value="Dinheiro">Dinheiro</option>
                        <option value="Cartão de Crédito">Cartão de Crédito</option>
                        <option value="Cartão de Débito">Cartão de Débito</option>
                        <option value="PIX">PIX</option>
                    </select>
                </div>
                
                <div class="border-t pt-6">
                    <div class="flex justify-between items-center text-2xl font-bold text-stone-800">
                        <span>Total:</span>
                        <span x-text="formatCurrency(total)"></span>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" class="cursor-pointer w-full bg-green-600 text-white font-bold py-4 px-6 rounded-lg hover:bg-green-700 transition-all duration-300 ease-in-out transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center text-lg">
                        <i class="bi bi-check-circle-fill mr-2"></i>
                        Finalizar Venda
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<script src="//unpkg.com/alpinejs" defer></script>

<script>
    function vendaForm() {
        return {
            tomSelect: null,
            produtosDisponiveis: @json($produtosEmEstoque),
            items: [],
            total: 0,
            
            init() {
                this.tomSelect = new TomSelect('#produto_select', {
                    options: this.produtosDisponiveis.map(p => ({
                        value: p.id,
                        text: `${p.nome} (Estoque: ${p.quantidade_total})`,
                        ...p
                    })),
                    render: {
                        option: function(data, escape) {
                            return `<div>
                                        <span class="font-semibold">${escape(data.nome)}</span>
                                        <span class="text-sm text-gray-500 ml-2">(Estoque: ${data.quantidade_total})</span>
                                        <span class="float-right font-medium">${parseFloat(data.preco_venda).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}</span>
                                    </div>`;
                        },
                        item: function(item, escape) {
                            return `<div>${escape(item.nome)}</div>`;
                        }
                    }
                });
            },

            adicionarItem() {
                const produtoId = this.tomSelect.getValue();
                if (!produtoId) {
                    alert('Selecione um produto.');
                    return;
                }

                const produto = this.produtosDisponiveis.find(p => p.id == produtoId);
                const itemExistente = this.items.find(item => item.produto_id == produtoId);

                if (itemExistente) {
                    itemExistente.quantidade++;
                    this.updateSubtotal(this.items.indexOf(itemExistente));
                } else {
                    this.items.push({
                        produto_id: produto.id,
                        nome: produto.nome,
                        quantidade: 1,
                        preco_venda: produto.preco_venda,
                        subtotal: produto.preco_venda,
                    });
                }
                
                this.calcularTotal();
                this.tomSelect.clear();
            },

            removerItem(index) {
                this.items.splice(index, 1);
                this.calcularTotal();
            },

            updateSubtotal(index) {
                let item = this.items[index];
                const produto = this.produtosDisponiveis.find(p => p.id == item.produto_id);

                if (item.quantidade > produto.quantidade_total) {
                    item.quantidade = produto.quantidade_total;
                    alert('Quantidade máxima em estoque atingida.');
                }
                if (item.quantidade < 1) {
                    item.quantidade = 1;
                }
                item.subtotal = item.quantidade * item.preco_venda;
                this.calcularTotal();
            },

            calcularTotal() {
                this.total = this.items.reduce((acc, item) => acc + item.subtotal, 0);
            },
            
            formatCurrency(value) {
                return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            },

            submitForm(event) {
                if (this.items.length === 0) {
                    alert('Adicione pelo menos um item para registrar a venda.');
                    return;
                }
                
                // Adiciona os itens como campos hidden no formulário antes de submeter
                this.items.forEach((item, index) => {
                    const form = event.target;
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][produto_id]" value="${item.produto_id}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="items[${index}][quantidade]" value="${item.quantidade}">`);
                });

                event.target.submit();
            }
        }
    }
</script>
@endpush