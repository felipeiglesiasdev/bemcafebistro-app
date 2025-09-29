@extends('layouts.app')

@section('content')
<div x-data="salesForm({{ Js::from($produtosDisponiveis) }})">
    <h1 class="text-3xl font-bold text-stone-800 mb-6">Nova Venda</h1>

    <form @submit.prevent="submitSale">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna de Produtos -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-semibold mb-4 text-stone-700">Adicionar Produtos</h2>
                
                <div class="mb-4 relative">
                    <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input 
                        x-model="search"
                        type="text" 
                        placeholder="Buscar por nome ou ID do produto..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6F4E37]">
                </div>

                <div class="max-h-[500px] overflow-y-auto border rounded-lg">
                    <template x-if="filteredProducts.length === 0">
                        <p class="p-4 text-center text-gray-500" x-text="search ? 'Nenhum produto encontrado.' : 'Carregando produtos...'"></p>
                    </template>
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="flex items-center justify-between p-3 border-b hover:bg-gray-50 last:border-b-0 transition-colors duration-150">
                            <div>
                                <p class="font-semibold text-stone-800" x-text="`#${product.id} - ${product.nome}`"></p>
                                <p class="text-sm text-stone-500">
                                    Em estoque: <span x-text="product.estoque_sum_quantidade || 0"></span>
                                    <template x-if="product.estoque_sum_quantidade > 0">
                                        <span> | R$ <span x-text="getPrice(product)"></span></span>
                                    </template>
                                </p>
                            </div>
                            
                            <template x-if="product.estoque_sum_quantidade > 0">
                                <button @click="addToCart(product)" type="button"
                                        class="px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-transform transform hover:scale-105">
                                    Adicionar
                                </button>
                            </template>
                            <template x-else>
                                <span class="px-4 py-2 text-sm font-semibold text-red-700 bg-red-100 rounded-lg">
                                    Sem Estoque
                                </span>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Coluna do Carrinho e Pagamento -->
            <div class="bg-white p-6 rounded-2xl shadow-lg">
                <h2 class="text-xl font-semibold mb-4 text-stone-700">Resumo da Venda</h2>
                
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-stone-800" x-text="item.nome"></p>
                                <p class="text-sm text-stone-500" x-text="'R$ ' + item.preco_unitario"></p>
                            </div>
                            <div class="flex items-center">
                                <button @click="decreaseQuantity(item.id)" type="button" class="px-2 py-1 bg-gray-200 rounded-l">-</button>
                                <input type="text" x-model.number="item.quantidade" @change="validateQuantity(item)" class="w-12 text-center border-t border-b">
                                <button @click="increaseQuantity(item.id)" type="button" class="px-2 py-1 bg-gray-200 rounded-r">+</button>
                                <button @click="removeFromCart(item.id)" type="button" class="ml-3 text-red-500 hover:text-red-700">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="cart.length === 0" class="text-center text-stone-500 py-8">
                        Carrinho vazio
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t">
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span>Total:</span>
                        <span x-text="'R$ ' + total.toFixed(2).replace('.', ',')"></span>
                    </div>

                    <div class="mt-6">
                        <label for="forma_pagamento" class="block text-sm font-medium text-stone-700 mb-2">Forma de Pagamento</label>
                        <select name="forma_pagamento" x-model="paymentMethod" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6F4E37]">
                            <option value="Dinheiro">Dinheiro</option>
                            <option value="Cartão de Crédito">Cartão de Crédito</option>
                            <option value="Cartão de Débito">Cartão de Débito</option>
                            <option value="Pix">Pix</option>
                        </select>
                    </div>

                    <div class="mt-6">
                        <button type="submit" :disabled="cart.length === 0" class="w-full py-3 text-lg font-bold text-white bg-[#A0522D] rounded-lg hover:bg-[#8B4513] transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                            Finalizar Venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function salesForm(initialProducts) {
        return {
            products: initialProducts,
            cart: [],
            search: '',
            paymentMethod: 'Dinheiro',
            
            get filteredProducts() {
                if (this.search === '') {
                    return this.products;
                }
                const searchTerm = this.search.toLowerCase();
                return this.products.filter(p => 
                    p.nome.toLowerCase().includes(searchTerm) || 
                    p.id.toString().includes(searchTerm)
                );
            },
            
            get total() {
                return this.cart.reduce((sum, item) => sum + (item.quantidade * parseFloat(item.preco_unitario)), 0);
            },

            getPrice(product) {
                if (product.estoque && product.estoque.length > 0) {
                    return parseFloat(product.estoque[0].preco_venda).toFixed(2);
                }
                return '0.00';
            },

            addToCart(product) {
                const existingItem = this.cart.find(item => item.id === product.id);
                if (existingItem) {
                    if (existingItem.quantidade < existingItem.estoque_max) {
                        existingItem.quantidade++;
                    }
                } else {
                    this.cart.push({
                        id: product.id,
                        nome: product.nome,
                        quantidade: 1,
                        preco_unitario: this.getPrice(product),
                        estoque_max: product.estoque_sum_quantidade
                    });
                }
            },

            removeFromCart(productId) {
                this.cart = this.cart.filter(item => item.id !== productId);
            },

            increaseQuantity(productId) {
                const item = this.cart.find(item => item.id === productId);
                if (item && item.quantidade < item.estoque_max) {
                    item.quantidade++;
                }
            },

            decreaseQuantity(productId) {
                const item = this.cart.find(item => item.id === productId);
                if (item && item.quantidade > 1) {
                    item.quantidade--;
                } else if (item) {
                    this.removeFromCart(productId);
                }
            },

            validateQuantity(item) {
                if (!Number.isInteger(item.quantidade) || item.quantidade < 1) {
                    item.quantidade = 1;
                }
                if (item.quantidade > item.estoque_max) {
                    item.quantidade = item.estoque_max;
                }
            },

            submitSale() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("vendas.store") }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                this.cart.forEach((item, index) => {
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="itens[${index}][id]" value="${item.id}">`);
                    form.insertAdjacentHTML('beforeend', `<input type="hidden" name="itens[${index}][quantidade]" value="${item.quantidade}">`);
                });

                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="total" value="${this.total}">`);
                form.insertAdjacentHTML('beforeend', `<input type="hidden" name="forma_pagamento" value="${this.paymentMethod}">`);

                document.body.appendChild(form);
                form.submit();
            }
        }
    }
</script>
@endsection

