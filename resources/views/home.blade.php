@extends('layout')

@section('title', 'home')

@section('content')
<ul>
    <li><a href="{{ route('user.index') }}">ユーザー</a></li>
    <li><a href="{{ route('season.index') }}">シーズン</a></li>
    <li><a href="{{ route('battle.index') }}">試合</a></li>
</ul>
@endsection