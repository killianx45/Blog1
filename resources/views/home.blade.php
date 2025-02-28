@extends('layouts.app')
@section('content')

<h1 class="text-white">{{ $title }}</h1>
<button class="w-full py-2 text-white bg-blue-500 hover:bg-blue-700">
  <a href="{{ route('posts.create') }}">Créer un post</a>
</button>

@foreach ($posts as $post)
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
  <div class="max-w-sm overflow-hidden bg-white rounded shadow-lg dark:bg-gray-800">
    <div class="px-6 py-4">
      <h2 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">{{ $post->title }}</h2>
      <p class="mb-4 text-base text-gray-600 dark:text-gray-300">
        {{ Str::limit($post->body, 50) }}
      </p>
      <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
        Slug: {{ $post->slug }}
      </p>
      <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Par {{ $name_user }}
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          {{ $post->created_at->format('d/m/Y') }}
        </p>
      </div>
    </div>
    <button class="w-full py-2 text-white bg-blue-500 hover:bg-blue-700">
      <a href="{{ route('posts.show', $post->id) }}">Voir le post</a>
    </button>
  </div>
</div>
@endforeach

@endsection