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
    </tr>
    @foreach ($season->battles as $battle)
    <tr>
        <td>{{ $battle->created_at }}</td>
        <td>{{ $battle->share_url }}</td>
        @foreach ($battle->users as $user)
        <td>
            {{ $user->name }}</br>
            {{ $user->pivot->score }}</br>
            {{ $user->pivot->rank_point }}
        </td>
        @endforeach
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
    </tr>
    @foreach ($season->battles as $battle)
    <tr>
        <td>{{ $battle->created_at }}</td>
        <td>{{ $battle->share_url }}</td>
        @foreach ($users as $user)
        <?php $temp = $battle->users->where('id', $user->id)->first() ?>
        @if($temp === null)
        <td></td>
        @else
        <td>{{ $temp->pivot->rank_point }}</td>
        @endif
        @endforeach
    </tr>
    @endforeach
</table>
@endsection