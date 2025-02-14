@extends('layouts.app')
@section('content')

<h1 class="text-white">Post :</h1>

<div class="max-w-sm m-4 overflow-hidden bg-white rounded shadow-lg dark:bg-gray-800">
  <div class="px-6 py-4">
    <h2 class="mb-2 text-xl font-bold text-gray-800 dark:text-white">{{ $post->title }}</h2>
    <p class="mb-4 text-base text-gray-600 dark:text-gray-300">
      {{ $post->body }}
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

    <!-- Affichage des commentaires -->
    <h3 class="mt-4 text-lg font-semibold text-white">Commentaires :</h3>
    <div class="mt-2">
      @foreach ($post->comments as $comment)
      <div class="p-2 mb-2 bg-gray-100 border rounded">
        <p class="text-gray-800">{{ $comment->body }}</p>
        <p class="text-sm text-gray-500">Commenté le {{ $comment->created_at->format('d/m/Y') }}</p>
      </div>
      @endforeach
    </div>

    <form action="{{ route('comments.store') }}" method="POST" class="mt-4">
      @csrf
      <input type="hidden" name="id_post" value="{{ $post->id }}">
      <div class="form-group">
        <label for="body" class="block text-sm font-medium text-white">Laisser un commentaire :</label>
        <textarea name="body" id="body" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
      </div>
      <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Envoyer</button>
    </form>
  </div>
</div>

@endsection