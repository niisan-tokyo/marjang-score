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
  <label>フレンドコード: </label><input name="friend_code" type="text" value="{{ $user->friend_code }}" /><br>
  @error('friend_code')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror
  @if(Auth::user()->is_admin)
    <label>管理者にする？
      <select name="is_admin">
        <option value="true" @if($user->is_admin)selected @endif>はい</option>
        <option value="false" @if(!$user->is_admin)selected @endif>いいえ</option>
      </select>
    </label><br>
  @endif
  <button type="submit">登録</button>
</form>
@endsection