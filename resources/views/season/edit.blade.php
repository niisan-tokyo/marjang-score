@extends('layout')

@section('title', 'シーズン更新')

@section('content')
<form action="{{ route('season.update', ['season' => $season->id]) }}" method="POST">
  @csrf @method('PUT')
  <label>名前: </label><input name="name" type="text" value="{{ $season->name }}" /><br>
  @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <button type="submit">更新</button>
</form>
@endsection