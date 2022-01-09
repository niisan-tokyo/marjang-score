<?php
namespace App\Logics\RankPointCalculate;

use Illuminate\Support\Collection;

class MLeagueBase implements CalculateInterface
{

    public function run(Collection $data): Collection
    {
        $zero_point = config('rank_point.m_league_base.zero_point');
        $order_point = config('rank_point.m_league_base.order_point');

        $sorted = $data->sortBy([['score', 'desc']]);
        $pointed = [];
        $order = 1;
        foreach ($sorted as $val) {
            $pointed[$val['user']] = $val;
            $pointed[$val['user']]['rank_point'] = ($val['score'] - $zero_point + $order_point[$order]) / 100;
            $order++;
        }
        return collect($pointed);
    }
}