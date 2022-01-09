<?php
namespace App\Logics\RankPointCalculate;

use Illuminate\Support\Collection;

class Calculate
{
    
    public function exec(Collection $battle_data)
    {
        $logic = $this->getLogic();
        return $logic->run($battle_data);
    }

    public function getLogic(): CalculateInterface
    {
        return new MLeagueBase;
    }
}