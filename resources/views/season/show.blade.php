@extends('layout')

@section('title', 'シーズン詳細')

@section('content')
  @if($season->active)<p><span>現在アクティブです</span></p>@endif
  <label>名前: </label>{{ $season->name }}<br>

  <a href="{{ route('season.edit', ['season' => $season->id]) }}"><button>編集する</button></a>
  <a href="{{ route('season.index') }}"><button>一覧に戻る</button>
@endsection