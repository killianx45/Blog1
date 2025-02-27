@extends('layouts.app')
@section('content')
<div class="container px-4 mx-auto">
  @if ($errors->any())
  <div class="mb-4 alert alert-danger">
    <ul class="pl-5 list-disc">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('posts.store') }}" method="POST" class="space-y-4">
    @csrf
    <div class="form-group">
      <label for="title" class="block text-sm font-medium text-white">Entrez le titre du post : </label>
      <input type="text" name="title" id="title" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('title') }}">
    </div>

    <div class="form-group">
      <label for="body" class="block text-sm font-medium text-white">Entrez le contenu du post : </label>
      <textarea name="body" id="body" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('body') }}</textarea>
    </div>

    <div class="form-group">
      <label for="categories" class="text-white">Sélectionnez les catégories :</label>
      @foreach ($categories as $category)
      <div>
        <input type="checkbox" name="categories[]" value="{{ $category->id }}">
        <label class="text-white">{{ $category->name }}</label>
      </div>
      @endforeach
    </div>

    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Créer !</button>
  </form>
</div>
@endsection