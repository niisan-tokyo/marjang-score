@extends('layout')

@section('title', 'シーズン作成')

@section('content')
<form action="{{ route('season.store') }}" method="POST">
  @csrf @method('POST')
  <label>名前: </label><input name="name" type="text" /><br>
  @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <button type="submit">作成</button>
</form>
@endsection