<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Season;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @group season
 */
class SeasonControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function 一覧表示()
    {
        $seasons = Season::factory()->count(2)->create();
        $seasons[1]->active = true;
        $seasons[1]->save();

        $response = $this->get(route('season.index'));

        $response->assertSee($seasons[0]->name);
        $response->assertSee('☆彡' . $seasons[1]->name);
    }

    /**
     * @test
     */
    public function 新しいシーズンを作る()
    {
        $name = Str::random(10);

        $response = $this->post(route('season.store'), ['name' => $name]);

        $test = Season::first();
        $response->assertRedirect(route('season.show', ['season' => $test->id]));
        $this->assertEquals($name, $test->name);
    }

    /**
     * @test
     */
    public function シーズンを更新する()
    {
        $season = Season::factory()->create();
        $new_name = Str::random(10);

        $response = $this->put(route('season.update', ['season' => $season->id]), ['name' => $new_name]);

        $response->assertRedirect(route('season.show', ['season' => $season]));
        $test = Season::find($season->id);
        $this->assertEquals($new_name, $test->name);
    }

    /**
     * @test
     */
    public function 非アクティブなシーズンを確認する()
    {
        $season = Season::factory()->create();

        $response = $this->get(route('season.show', ['season' => $season]));

        $response->assertSee($season->name);
        $response->assertDontSee('現在アクティブです');
    }

    /**
     * @test
     */
    public function アクティブなシーズンを確認する()
    {
        $season = Season::factory()->create(['active' => true]);

        $response = $this->get(route('season.show', ['season' => $season]));

        $response->assertSee($season->name);
        $response->assertSee('現在アクティブです');
    }

    /**
     * @test
     */
    public function シーズンをアクティブにする()
    {
        $active_season = Season::factory()->create(['active' => true]);
        $non_active_season = Season::factory()->create(['active' => false]);

        $response = $this->put(route('season.activate', ['season' => $non_active_season]));

        $response->assertRedirect(route('season.index'));

        // もともとアクティブだったシーズンはノンアクティブに
        $test = Season::find($active_season->id);
        $this->assertFalse($test->active);

        // アクティブになる
        $test = Season::find($non_active_season->id);
        $this->assertTrue($test->active);
    }
}
