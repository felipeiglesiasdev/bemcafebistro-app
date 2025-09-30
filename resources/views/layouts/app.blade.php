<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem Café Bistrô - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-[#F5F5DC]/30">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-[#6F4E37]/10">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-30 w-72 bg-white shadow-2xl transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 flex flex-col"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
        >
            <!-- Logo -->
            <div class="flex items-center justify-center p-6 border-b border-gray-200">
                <img src="{{ asset('logo.png') }}" alt="Logo Bem Café Bistrô" class="h-24 w-24 rounded-full object-cover">
            </div>

            <!-- Navigation Links -->
            <nav class="flex-grow px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-lg rounded-lg text-stone-700 hover:bg-[#6F4E37]/10 hover:text-[#4a2e1a] transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#6F4E37]/10 text-[#4a2e1a] font-semibold' : '' }}">
                    <i class="bi bi-grid-1x2-fill w-6 h-6 mr-4"></i>
                    Dashboard
                </a>
                <a href="{{ route('categorias.index') }}" class="flex items-center px-4 py-3 text-lg rounded-lg text-stone-700 hover:bg-[#6F4E37]/10 hover:text-[#4a2e1a] transition-colors duration-200 {{ request()->routeIs('categorias.*') ? 'bg-[#6F4E37]/10 text-[#4a2e1a] font-semibold' : '' }}">
                    <i class="bi bi-tags-fill w-6 h-6 mr-4"></i>
                    Categorias
                </a>
                <a href="{{ route('produtos.index') }}" class="flex items-center px-4 py-3 text-lg rounded-lg text-stone-700 hover:bg-[#6F4E37]/10 hover:text-[#4a2e1a] transition-colors duration-200 {{ request()->routeIs('produtos.*') ? 'bg-[#6F4E37]/10 text-[#4a2e1a] font-semibold' : '' }}">
                    <i class="bi bi-box-seam-fill w-6 h-6 mr-4"></i>
                    Produtos
                </a>
                <a href="{{ route('estoque.index') }}" class="flex items-center px-4 py-3 text-lg rounded-lg text-stone-700 hover:bg-[#6F4E37]/10 hover:text-[#4a2e1a] transition-colors duration-200 {{ request()->routeIs('estoque.*') ? 'bg-[#6F4E37]/10 text-[#4a2e1a] font-semibold' : '' }}">
                    <i class="bi bi-clipboard-check-fill w-6 h-6 mr-4"></i>
                    Estoque
                </a>
                <a href="{{ route('vendas.index') }}" class="flex items-center px-4 py-3 text-lg rounded-lg text-stone-700 hover:bg-[#6F4E37]/10 hover:text-[#4a2e1a] transition-colors duration-200 {{ request()->routeIs('vendas.*') ? 'bg-[#6F4E37]/10 text-[#4a2e1a] font-semibold' : '' }}">
                    <i class="bi bi-cash-coin w-6 h-6 mr-4"></i>
                    Vendas
                </a>
            </nav>

            <!-- User Info and Logout Button -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <div>
                        <p class="font-semibold text-stone-800">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="cursor-pointer w-full flex items-center justify-center px-4 py-3 text-lg rounded-lg text-white bg-[#A0522D] hover:bg-[#8B4513] transition-colors duration-200">
                        <i class="bi bi-box-arrow-right w-6 h-6 mr-3"></i>
                        Sair
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header for Mobile -->
            <header class="flex items-center justify-end p-4 bg-white border-b lg:hidden">
                <button @click="sidebarOpen = !sidebarOpen" class="text-stone-800 focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </header>

            <!-- Main content -->
            <main class="flex-1 p-6 lg:p-10 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
     @stack('scripts')
</body>
</html>

