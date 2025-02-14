@extends('layouts.app')
@section('content')

<div class="container">
  <h1>Créer une nouvelle catégorie</h1>

  <form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="name">Nom de la catégorie :</label>
      <input type="text" name="name" id="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="slug">Slug de la catégorie :</label>
      <input type="text" name="slug" id="slug" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Créer</button>
  </form>
</div>

@endsection