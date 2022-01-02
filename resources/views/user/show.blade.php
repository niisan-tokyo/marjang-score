@extends('layout')

@section('title', 'ユーザ詳細')

@section('content')
  <label>名前: </label>{{ $user->name }}<br>
  <label>表示名: </label>{{ $user->player_name }}<br>
  <label>メールアドレス: </label>{{ $user->email }}<br>
  <label>フレンドコード: </label>{{ $user->friend_code }}<br>

  <a href="{{ route('user.edit', ['user' => $user->id]) }}"><button>編集する</button></a>
  <a href="{{ route('user.index') }}"><button>一覧に戻る</button>
@endsection