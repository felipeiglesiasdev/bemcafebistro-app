<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem Café Bistrô - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-sans bg-amber-50/50">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-amber-100/20">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <div class="flex items-center justify-center p-6 border-b">
                <h2 class="text-2xl font-bold text-amber-900">☕ Bem Café</h2>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg text-amber-900 hover:bg-amber-100 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-amber-100 font-semibold' : '' }}">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('produtos.index') }}" class="flex items-center p-3 rounded-lg text-amber-900 hover:bg-amber-100 transition-colors duration-200 {{ request()->routeIs('produtos.*') ? 'bg-amber-100 font-semibold' : '' }}">
                     <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7v10l8 4m0-14L4 7m8 4v10M4 7l8 4"/></svg>
                    Produtos
                </a>
                <a href="{{ route('estoque.index') }}" class="flex items-center p-3 rounded-lg text-amber-900 hover:bg-amber-100 transition-colors duration-200 {{ request()->routeIs('estoque.*') ? 'bg-amber-100 font-semibold' : '' }}">
                     <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    Estoque
                </a>
                <a href="{{ route('vendas.index') }}" class="flex items-center p-3 rounded-lg text-amber-900 hover:bg-amber-100 transition-colors duration-200 {{ request()->routeIs('vendas.*') ? 'bg-amber-100 font-semibold' : '' }}">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01v.01M12 14c-1.657 0-3-.895-3-2s1.343-2 3-2 3-.895 3-2-1.343-2-3-2m0 8c1.11 0 2.08-.402 2.599-1M12 14v1m0-1v-.01m0 2v-1m0 1v1m0-2.01v-.01M12 20v-1m0-1V4m0 16v-1m0 1v1m0-2.01v-.01M4 9.01V9m0-1v1.01M4 14v-1m0 1v1m0-2.01v-.01M20 9.01V9m0-1v1.01M20 14v-1m0 1v1m0-2.01v-.01M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    Vendas
                </a>
            </nav>
            <div class="p-4 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center p-3 rounded-lg text-white bg-amber-800 hover:bg-amber-900 transition-colors duration-200">
                        <svg class="h-6 w-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sair
                    </button>
                </form>
            </div>
        </aside>

        <!-- Conteúdo Principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between p-4 bg-white border-b md:justify-end">
                <button @click="sidebarOpen = !sidebarOpen" class="text-amber-900 focus:outline-none md:hidden">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </button>

                <div class="flex items-center space-x-4">
                    <span class="text-amber-900 font-medium">Olá, {{ Auth::user()->name }}</span>
                </div>
            </header>

            <!-- Main content -->
            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
