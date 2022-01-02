@extends('layout')

@section('title', 'ユーザ更新')

@section('content')
<form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST">
  @csrf @method('PUT')
  <label>名前: </label><input name="name" type="text" value="{{ $user->name }}" /><br>
  @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>表示名: </label><input name="player_name" type="text" value="{{ $user->player_name }}" /><br>
  @error('player_name')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>メールアドレス: </label><input name="email" type="text" value="{{ $user->email }}" /><br>
  @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>パスワード: </label><input name="password" type="password" /><br>
  @error('password')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>パスワード(確認): </label><input name="password_confirmation" type="password" /><br>
  <label>フレンドコード: </label><input name="friend_code" type="text" value="{{ $user->friend_code }}" /><br>
  @error('friend_code')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <button type="submit">登録</button>
</form>
@endsection