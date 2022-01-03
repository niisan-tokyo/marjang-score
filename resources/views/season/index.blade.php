@extends('layout')

@section('title', 'シーズン一覧')

@section('content')
<div><a href="{{ route('season.create') }}"><button>新規作成</button></a></div>
<table border="solid">
    <tr>
        <th></th>
        <th>ID</th>
        <th>名前</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($seasons as $season)
    <tr>
        <td>@if($season->active)☆彡@endif</td>
        <td>{{ $season->id }}</td>
        <td>@if($season->active)☆彡@endif{{ $season->name }}</td>
        <td><a href="{{ route('season.show', ['season' => $season->id]) }}"><button>詳細</button></a></td>
        <td><a href="{{ route('season.edit', ['season' => $season->id]) }}"><button>編集</button></a></td>
    </tr>
    @endforeach
</table>
@endsection