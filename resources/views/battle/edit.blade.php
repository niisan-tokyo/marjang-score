@extends('layout')

@section('title', '試合の記録')

<h3>{{ $season->name }}</h3>
@section('content')
<form action="{{ route('battle.update', ['battle' => $battle->id]) }}" method="POST">
  @csrf @method('PUT')
  <input type="hidden" name="season" value="{{ $season->id }}" />
  <label>牌譜URL: <input name="share_url" type="text" value="{{ $battle->share_url }}"/></label><br>
  <table border="solid">
      <tr>
          <th>起家</th>
          <th>ユーザー</th>
          <th>得点</th>
      </tr>
      <?php $kike = ['東', '南', '西', '北']; ?>
  @for($i = 0; $i !== 4; $i++)
    <tr>
        <td>{{ $kike[$i] }}<input type="hidden" name="battle[{{ $i }}][start_position]" value="{{ $i }}"</td>
        <td><select name="battle[{{ $i }}][user]">
            @foreach($users as $user) 
                <option value="{{ $user->id }}" @if($user->id === $battle->users[$i]->id) selected @endif>
                    {{ $user->player_name }}({{ $user->name }})
                </option>
            @endforeach
        </select></td>
        <td><input type="number" name="battle[{{ $i }}][score]" value="{{ $battle->users[$i]->pivot->score }}"/></td>
    </tr>
  @endfor
  </table>
  <label>コメント: <textarea name="comment" rows="7" cols="50">{{ $battle->comment }}</textarea><br>
  <button type="submit">更新</button>
  @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</form>
@endsection