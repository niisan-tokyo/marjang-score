<?php
namespace App\Logics\RankPointCalculate;

use Illuminate\Support\Collection;

class MLeagueBase implements CalculateInterface
{

    private $temp_data = [];
    private $order_points = [];
    private $pointed = [];

    public function run(Collection $data): Collection
    {
        $zero_point = config('rank_point.m_league_base.zero_point');
        $order_point = config('rank_point.m_league_base.order_point');

        $sorted = $data->sortBy([['score', 'desc']]);
        $score = null;
        $order = 1;
        foreach ($sorted as $val) {
            if ($score !== null and $score !== $val['score']) {
                $this->calculate($zero_point);
            }

            $score = $val['score'];
            $this->reserve($val, $order_point[$order]);
            $order++;
        }
        $this->calculate($zero_point);
        return collect($this->pointed);
    }

    private function reserve(array $data, int $order_point): void
    {
        $this->temp_data[] = $data;
        $this->order_points[] = $order_point;
    }

    private function calculate(int $zero_point): void
    {
        if (empty($this->order_points)) {
            return;
        }
        $average_order_point = array_sum($this->order_points) / count($this->order_points);
        foreach ($this->temp_data as $data) {
            $this->pointed[$data['user']] = $data;
            $this->pointed[$data['user']]['rank_point'] = ($data['score'] - $zero_point + $average_order_point) / 100;
        }

        $this->order_points = [];
        $this->temp_data = [];
    }
}