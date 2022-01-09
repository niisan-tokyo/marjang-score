<?php

namespace Tests\Unit\Logics\RankPointCalculate;

use App\Logics\RankPointCalculate\MLeagueBase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * @group rank_point_calc
 */
class MLeagueBaseTest extends TestCase
{

    private MLeagueBase $obj;

    public function setUp(): void
    {
        parent::setUp();
        $this->obj = new MLeagueBase;
        Config::set('rank_point.m_league_base', [
            'origin' => 25000,
            'zero_point' => 30000,
            'order_point' => [
                1 => 50000,
                2 => 10000,
                3 => -10000,
                4 => -30000
            ]
        ]);
    }

    /**
     * @test
     */
    public function 通常の点数計算()
    {
        $data = [
            0 => ['start_position' => 0, 'score' => 25000, 'user' => 1],
            1 => ['start_position' => 1, 'score' => 40000, 'user' => 2],
            2 => ['start_position' => 2, 'score' => 20000, 'user' => 3],
            3 => ['start_position' => 3, 'score' => 15000, 'user' => 4]
        ];

        $result = $this->obj->run(collect($data))->keyBy('user');

        $this->assertEquals(50, $result[1]['rank_point']);// 25000 - 30000 + 10000 = 5000
        $this->assertEquals(600, $result[2]['rank_point']);// 40000 - 30000 + 50000 = 60000
        $this->assertEquals(-200, $result[3]['rank_point']);// 20000 - 30000 - 10000 = -20000
        $this->assertEquals(-450, $result[4]['rank_point']);// 15000 - 30000 - 30000 = -45000
    }

    /**
     * @test
     */
    public function ２，３位が同点()
    {
        $data = [
            0 => ['start_position' => 0, 'score' => 23000, 'user' => 1],
            1 => ['start_position' => 1, 'score' => 23000, 'user' => 2],
            2 => ['start_position' => 2, 'score' => 20000, 'user' => 3],
            3 => ['start_position' => 3, 'score' => 34000, 'user' => 4]
        ];

        $result = $this->obj->run(collect($data))->keyBy('user');

        $this->assertEquals(540, $result[4]['rank_point']);// 34000 - 30000 + 50000 = 54000
        $this->assertEquals(-400, $result[3]['rank_point']);// 20000 - 30000 - 30000 = -40000
        $this->assertEquals(-70, $result[1]['rank_point']);// 23000 - 30000 - 0 = -7000
        $this->assertEquals(-70, $result[2]['rank_point']);// 23000 - 30000 - 0 = -7000
    }

    /**
     * @test
     */
    public function １，2位、３，４位が同点()
    {
        $data = [
            0 => ['start_position' => 0, 'score' => 15000, 'user' => 1],
            1 => ['start_position' => 1, 'score' => 35000, 'user' => 2],
            2 => ['start_position' => 2, 'score' => 15000, 'user' => 3],
            3 => ['start_position' => 3, 'score' => 35000, 'user' => 4]
        ];

        $result = $this->obj->run(collect($data))->keyBy('user');

        $this->assertEquals(350, $result[2]['rank_point']);// 35000 - 30000 + 30000 = 35000
        $this->assertEquals(350, $result[4]['rank_point']);// 35000 - 30000 + 30000 = 35000
        $this->assertEquals(-350, $result[1]['rank_point']);// 15000 - 30000 - 20000 = -35000
        $this->assertEquals(-350, $result[3]['rank_point']);// 15000 - 30000 - 20000 = -35000
    }

    /**
     * @test
     */
    public function １，２，３位が同点()
    {
        $data = [
            0 => ['start_position' => 0, 'score' => 32000, 'user' => 1],
            1 => ['start_position' => 1, 'score' => 32000, 'user' => 2],
            2 => ['start_position' => 2, 'score' => 32000, 'user' => 3],
            3 => ['start_position' => 3, 'score' => 4000, 'user' => 4]
        ];

        $result = $this->obj->run(collect($data))->keyBy('user');

        $this->assertEquals(188, $result[1]['rank_point']);// 32000 - 30000 + 16800 = 18800
        $this->assertEquals(186, $result[2]['rank_point']);// 32000 - 30000 + 16600 = 18600
        $this->assertEquals(186, $result[3]['rank_point']);// 32000 - 30000 + 16600 = 18600
        $this->assertEquals(-560, $result[4]['rank_point']);// 4000 - 30000 - 30000 = -56000
    }

    /**
     * @test
     */
    public function ２，３，４位が同点()
    {
        $data = [
            0 => ['start_position' => 0, 'score' => 12300, 'user' => 1],
            1 => ['start_position' => 1, 'score' => 12300, 'user' => 2],
            2 => ['start_position' => 2, 'score' => 12300, 'user' => 3],
            3 => ['start_position' => 3, 'score' => 63100, 'user' => 4]
        ];

        $result = $this->obj->run(collect($data))->keyBy('user');

        $this->assertEquals(-277, $result[1]['rank_point']);// 12300 - 30000 - 10000 = -27700
        $this->assertEquals(-277, $result[2]['rank_point']);// 12300 - 30000 - 10000 = -27700
        $this->assertEquals(-277, $result[3]['rank_point']);// 12300 - 30000 - 10000 = -27700
        $this->assertEquals(831, $result[4]['rank_point']);//63100 - 30000 + 50000 = -83100
    }
}
