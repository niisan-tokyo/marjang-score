<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBattleRequest;
use App\Logics\RankPointCalculate\Calculate;
use App\Models\Battle;
use App\Models\Season;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BattleController extends Controller
{

    public function index(Request $request)
    {
        $season = ($request->season_id) ? Season::findOrFail($request->season_id) : Season::active()->firstOrFail();
        $season->load('battles.users');
        $users = collect($season->battles->reduce(function($carry, $item) {
            foreach ($item->users as $user) {
                $carry[$user->id] ??= $user;
                $carry[$user->id]->sum ??= 0;
                $carry[$user->id]->sum += $user->pivot->rank_point; 
            }
            return $carry;
        }))->sortBy([['sum', 'desc']]);

        return view('battle.index', compact('season', 'users'));
    }
    
    public function create(Request $request)
    {
        $season = ($request->season_id) ? Season::findOrFail($request->season_id): Season::active()->firstOrFail();
        return view('battle.create', [
            'users' => User::all(),
            'season' => $season
        ]);
    }

    public function store(StoreBattleRequest $request)
    {
        $data = $request->validated();
        $this->saveBattle(new Battle, $data);
        return redirect(route('battle.index'));
    }

    public function edit(Battle $battle)
    {
        return view('battle.edit', [
            'users' => User::all(),
            'season' => $battle->season,
            'battle' => $battle
        ]);
    }

    public function update(Battle $battle, StoreBattleRequest $request)
    {
        $data = $request->validated();
        $this->saveBattle($battle, $data);
        return redirect(route('battle.index'));
    }

    private function saveBattle(Battle $battle, array $data)
    {
        $result = (new Calculate)->exec(collect($data['battle']));
        DB::transaction(function() use ($battle, $data, $result) {
            $season = Season::find($data['season']);
            $battle->share_url = $data['share_url'];
            $battle->comment = $data['comment'];
            $season->battles()->save($battle);

            // ユーザと点数計算の結果を入れる
            $sync = $result->mapWithKeys(fn($item, $key) => [
                $item['user'] => collect($item)->only(['score', 'start_position', 'rank_point'])->toArray()
            ])->toArray();
            $battle->users()->sync($sync);
        });
    }
}
