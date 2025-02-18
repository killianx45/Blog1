@extends('layouts.app')
@section('content')


<h1 class="text-white">Bienvenue sur la page des catégories</h1>

<button class="w-full py-2 text-white bg-blue-500 hover:bg-blue-700">
  <a href="{{ route('category.create') }}">Créer une catégorie</a>
</button>

<div class="flex flex-wrap">
  @foreach ($category as $cat)
  <div class="w-full p-2 md:w-1/2 lg:w-1/3 xl:w-1/4">
    <div class="max-w-sm overflow-hidden bg-white rounded shadow-lg dark:bg-gray-800">
      <div class="px-6 py-4">
        <h2 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">{{ $cat->name }}</h2>
        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
          Slug: {{ $cat->slug }}
        </p>
      </div>
      <button class="w-full py-2 text-white bg-blue-500 hover:bg-blue-700">
        <a href="{{ route('categories.show', $cat->id) }}">Voir la catégorie</a>
      </button>
    </div>
  </div>
  @endforeach
</div>
@endsection