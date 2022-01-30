@extends('layout')

@section('title', '試合の記録')

<h3>{{ $season->name }}</h3>
@section('content')
<table border="solid">
    <tr>
        <th>記録日</th>
        <th>シェアURL</th>
        <th>東</th>
        <th>南</th>
        <th>西</th>
        <th>北</th>
        <th>コメント</th>
    </tr>
    @foreach ($season->battles as $battle)
    <tr>
        <td><a href="{{ route('battle.edit', ['battle' => $battle->id]) }}">{{ $battle->created_at }}</a></td>
        <td>{{ $battle->share_url }}</td>
        @foreach ($battle->users as $user)
        <td>
            {{ $user->name }}</br>
            {{ $user->pivot->score }}</br>
            {{ $user->pivot->rank_point }}
        </td>
        @endforeach
        <td>{{ $battle->comment }}</td>
    </tr>
    @endforeach
</table>

<table border="solid">
    <tr>
        <th>記録日</th>
        <th>シェアURL</th>
        @foreach ($users as $key => $user)
            <th>{{ $user->name }}<br>{{ $user->sum }}</th>
        @endforeach
        <th>コメント</th>
    </tr>
    @foreach ($season->battles as $battle)
    <tr>
        <td><a href="{{ route('battle.edit', ['battle' => $battle->id]) }}">{{ $battle->created_at }}</a></td>
        <td>{{ $battle->share_url }}</td>
        @foreach ($users as $user)
        <?php $temp = $battle->users->where('id', $user->id)->first() ?>
        @if($temp === null)
        <td></td>
        @else
        <td>{{ $temp->pivot->rank_point }}</td>
        @endif
        @endforeach
        <td>{{ $battle->comment }}</td>
    </tr>
    @endforeach
</table>
@endsection