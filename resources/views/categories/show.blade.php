@extends('layouts.app')
@section('content')

<h1 class="text-white">Posts dans la catégorie : {{ $categorie->name }}</h1>

<div class="flex flex-wrap">
  @foreach ($categorie->posts as $post)
  <div class="max-w-sm m-4 overflow-hidden bg-white rounded shadow-lg dark:bg-gray-800">
    <div class="px-6 py-4">
      <h2 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">{{ $post->title }}</h2>
      <p class="mb-4 text-base text-gray-600 dark:text-gray-300">
        {{ Str::limit($post->body, 100) }}
      </p>
      <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
        Slug: {{ $post->slug }}
      </p>
      <a href="{{ route('posts.show', $post->id) }}" class="text-blue-500">Voir le post</a>
    </div>
  </div>
  @endforeach
</div>

@endsection