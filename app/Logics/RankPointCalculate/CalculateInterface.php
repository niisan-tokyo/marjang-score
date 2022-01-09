<?php
namespace App\Logics\RankPointCalculate;

use Illuminate\Support\Collection;

interface CalculateInterface
{
    public function run(Collection $data): Collection;
}