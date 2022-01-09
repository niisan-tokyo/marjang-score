<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Season;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Illuminate\Support\Str;

/**
 * @group battle
 */
class BattleControllerTest extends TestCase
{
    use RefreshDatabase;

    private $season;
    private $users;

    public function setUp(): void
    {
        parent::setUp();
        $this->season = Season::factory()->active()->create();
        $this->users = User::factory()->count(4)->create();

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
    public function 試合情報の保存()
    {
        $comment = Str::random(30);
        $response = $this->post(route('battle.store'), [
            'season' => $this->season->id,
            'share_url' => 'https://localhost.com',
            'comment' => $comment,
            'battle' => [
                0 => [
                    'user' => $this->users[0]->id,
                    'score' => 23000,// 23000 - 30000 - 10000 = -17000
                    'start_position' => 0
                ],
                1 => [
                    'user' => $this->users[1]->id,
                    'score' => 34600,// 34600 - 30000 + 50000 = 54600
                    'start_position' => 1
                ],
                2 => [
                    'user' => $this->users[2]->id,
                    'score' => 15400,// 15400 - 30000 - 30000 = -44600
                    'start_position' => 2
                ],
                3 => [
                    'user' => $this->users[3]->id,
                    'score' => 27000,// 27000 - 30000 + 10000 = 7000
                    'start_position' => 3
                ],
            ]
        ]);

        $season = Season::with('battles.users')->find($this->season->id);
        $battle = $season->battles[0];

        $this->assertEquals('https://localhost.com', $battle->share_url);
        $this->assertEquals($comment, $battle->comment);

        $users = $battle->users;
        $this->assertEquals(0, $users[0]->pivot->start_position);
        $this->assertEquals(-170, $users[0]->pivot->rank_point);
        $this->assertEquals(23000, $users[0]->pivot->score);

        $this->assertEquals(1, $users[1]->pivot->start_position);
        $this->assertEquals(546, $users[1]->pivot->rank_point);
        $this->assertEquals(34600, $users[1]->pivot->score);

        $this->assertEquals(2, $users[2]->pivot->start_position);
        $this->assertEquals(-446, $users[2]->pivot->rank_point);
        $this->assertEquals(15400, $users[2]->pivot->score);

        $this->assertEquals(3, $users[3]->pivot->start_position);
        $this->assertEquals(70, $users[3]->pivot->rank_point);
        $this->assertEquals(27000, $users[3]->pivot->score);
    }
}
