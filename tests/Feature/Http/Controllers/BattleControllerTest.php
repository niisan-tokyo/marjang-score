<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Battle;
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

        /** @var User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
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

    /**
     * @test
     */
    public function 一覧でランキングを見る()
    {
        $battle = (new Battle);
        $this->season->battles()->save($battle);
        $battle->users()->sync([
            $this->users[0]->id => [
                'score' => 23000,// 23000 - 30000 - 10000 = -17000
                'start_position' => 0,
                'rank_point' => -170
            ],
            $this->users[1]->id => [
                'score' => 34600,// 34600 - 30000 + 50000 = 54600
                'start_position' => 1,
                'rank_point' => 546
            ],
            $this->users[2]->id => [
                'score' => 15400,// 15400 - 30000 - 30000 = -44600
                'start_position' => 2,
                'rank_point' => -446
            ],
            $this->users[3]->id => [
                'score' => 27000,// 27000 - 30000 + 10000 = 7000
                'start_position' => 3,
                'rank_point' => 70
            ],
        ]);

        $battle = (new Battle);
        $this->season->battles()->save($battle);
        $battle->users()->sync([
            $this->users[0]->id => [
                'score' => 45200,// 45200 - 30000 + 50000 = 65200
                'start_position' => 0,
                'rank_point' => 652
            ],
            $this->users[1]->id => [
                'score' => 25600,// 25600 - 30000 - 10000 = 14400
                'start_position' => 1,
                'rank_point' => -144
            ],
            $this->users[2]->id => [
                'score' => 31200,// 31200 - 30000 + 10000 = 11200
                'start_position' => 2,
                'rank_point' => 112
            ],
            $this->users[3]->id => [
                'score' => -2000,// -2000 - 30000 - 30000 = -62000
                'start_position' => 3,
                'rank_point' => -620
            ],
        ]);

        $response = $this->get(route('battle.index'));
        $response->assertSee($this->users[0]->name . '<br>482', false);
        $response->assertSee($this->users[1]->name . '<br>402', false);
        $response->assertSee($this->users[2]->name . '<br>-334', false);
        $response->assertSee($this->users[3]->name . '<br>-550', false);
    }
}
