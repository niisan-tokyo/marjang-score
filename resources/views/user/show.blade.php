@extends('layout')

@section('title', 'ユーザ詳細')

@section('content')
  <label>名前: </label>{{ $user->name }}<br>
  <label>表示名: </label>{{ $user->player_name }}<br>
  <label>メールアドレス: </label>{{ $user->email }}<br>
  <label>フレンドコード: </label>{{ $user->friend_code }}<br>
@endsection