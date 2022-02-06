@extends('layout')

@section('title', 'ユーザ登録')

@section('content')
<form action="{{ route('user.store') }}" method="POST">
  @csrf @method('POST')
  <label>名前: </label><input name="name" type="text" /><br>
  @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>表示名: </label><input name="player_name" type="text"/><br>
  @error('player_name')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>メールアドレス: </label><input name="email" type="text"/><br>
  @error('email')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  <label>フレンドコード: </label><input name="friend_code" type="text"/><br>
  @error('friend_code')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  @if(Auth::user()->is_admin)
    <label>管理者にする？
      <select name="is_admin">
        <option value="true">はい</option>
        <option value="false">いいえ</option>
      </select>
    </label><br>
  @endif
  <button type="submit">登録</button>
</form>
@endsection