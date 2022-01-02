@extends('layout')

@section('title', 'ユーザ一覧')

@section('content')
<div><a href="{{ route('user.create') }}"><button>新規作成</button></a></div>
<table border="solid">
    <tr>
        <th>ID</th>
        <th>名前</th>
        <th>表示名</th>
        <th>メール</th>
        <th>フレコ</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->player_name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->friend_code }}</td>
        <td><a href="{{ route('user.show', ['user' => $user->id]) }}"><button>詳細</button></a></td>
        <td><a href="{{ route('user.edit', ['user' => $user->id]) }}"><button>編集</button></a></td>
    </tr>
    @endforeach
</table>
@endsection