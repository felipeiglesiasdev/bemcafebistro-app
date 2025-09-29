<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Bem Café Bistrô</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  {{-- Importando a fonte Poppins do Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>
<body class="font-sans bg-[#6F4E37] flex items-center justify-center h-screen p-4">
  <div class="w-full max-w-sm p-8 bg-amber-50 rounded-xl shadow-2xl">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-bold text-amber-950">☕ Bem Café Bistrô</h1>
      <p class="text-amber-800">Acesso ao painel administrativo</p>
    </div>

    @if($errors->any())
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6" role="alert">
        <p>{{ $errors->first() }}</p>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
      @csrf
      <div>
        <label for="email" class="block text-sm font-medium text-amber-800">Email</label>
        <input type="email" name="email" id="email" required value="{{ old('email') }}"
               class="mt-1 block w-full px-3 py-2 bg-white border border-stone-300 rounded-md shadow-sm placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
      </div>
      <div>
        <label for="password" class="block text-sm font-medium text-amber-800">Senha</label>
        <input type="password" name="password" id="password" required
               class="mt-1 block w-full px-3 py-2 bg-white border border-stone-300 rounded-md shadow-sm placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
      </div>
      <button type="submit"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-800 hover:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-700 cursor-pointer transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg">
        Entrar
      </button>
    </form>
  </div>
</body>
</html>
