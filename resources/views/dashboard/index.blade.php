@extends('layouts.app')

@section('content')
<div class="p-6">
  <h1 class="text-3xl font-bold">Bem-vindo, {{ Auth::user()->name }}!</h1>
  <p class="mt-2 text-gray-600">Aqui você verá os relatórios de vendas e estoque.</p>
</div>
@endsection
