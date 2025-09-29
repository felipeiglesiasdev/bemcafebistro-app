<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem Café Bistrô - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white h-screen shadow-md flex flex-col">
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold">☕ Bem Café</h2>
        </div>
        <nav class="flex-1 p-4">
            <ul class="space-y-2">
                <li>
                    <a href="/dashboard" class="block p-2 rounded hover:bg-gray-200">📊 Dashboard</a>
                </li>
                <li>
                    <a href="/produtos" class="block p-2 rounded hover:bg-gray-200">📦 Produtos</a>
                </li>
                <li>
                    <a href="/estoque" class="block p-2 rounded hover:bg-gray-200">📋 Estoque</a>
                </li>
                <li>
                    <a href="/vendas" class="block p-2 rounded hover:bg-gray-200">💰 Vendas</a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-600 text-white p-2 rounded hover:bg-red-700">
                    Sair
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="flex-1 p-6 overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>
