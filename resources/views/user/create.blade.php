@extends('layout')

@section('title', 'ユーザ登録')

@section('content')
<form action="{{ route('user.store') }}" method="POST">
  @csrf @method('POST')
  <label>名前: </label><input name="name" type="text"/><br>
  <label>表示名: </label><input name="player_name" type="text"/><br>
  <label>メールアドレス: </label><input name="email" type="text"/><br>
  <label>パスワード: </label><input name="password" type="password" /><br>
  <label>パスワード(確認): </label><input name="password_confirmation" type="password" /><br>
  <label>フレンドコード: </label><input name="friend_code" type="text"/><br>
  <button type="submit">登録</button>
</form>
@endsection