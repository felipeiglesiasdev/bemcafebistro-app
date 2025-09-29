<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login - Bem Café Bistrô</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    @if($errors->any())
      <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="/admin">
      @csrf
      <div class="mb-4">
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" required
               class="w-full p-2 border border-gray-300 rounded mt-1">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700">Senha</label>
        <input type="password" name="password" required
               class="w-full p-2 border border-gray-300 rounded mt-1">
      </div>
      <button type="submit"
              class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
        Entrar
      </button>
    </form>
  </div>
</body>
</html>
