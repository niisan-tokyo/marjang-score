<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

/**
 * @group user
 */
class UserControllerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        /** @var User $user */
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();
        $this->actingAs($user);
    }

    /**
     * @test
     */
    public function ユーザ一覧()
    {
        //データ生成
        $users = User::factory()->count(2)->create();

        // リクエスト
        $response = $this->get(route('user.index'));

        // レスポンスの確認
        $response->assertSee($users[0]->name);
        $response->assertSee($users[0]->player_name);
        $response->assertSee($users[0]->email);
        $response->assertSee($users[0]->friend_code);
        $response->assertSee($users[1]->name);
        $response->assertSee($users[1]->player_name);
        $response->assertSee($users[1]->email);
        $response->assertSee($users[1]->friend_code);
    }

    /**
     * @test
     */
    public function ユーザを作成する()
    {
        // 適当な入力値を生成
        $user = User::factory()->make()->toArray();

        // 登録処理にリクエストを投げてユーザを生成
        $response = $this->post(route('user.store'), $user);

        // 入力内容がちゃんと保存されていることを確認
        $test = User::orderBy('id', 'desc')->first();
        $this->assertEquals($user['name'], $test->name);
        $this->assertEquals($user['player_name'], $test->player_name);
        $this->assertEquals($user['email'], $test->email);
        $this->assertEquals($user['friend_code'], $test->friend_code);

        // レスポンスはリダイレクトになっている
        $response->assertRedirect(route('user.show', ['user' => $test->id]));
    }

    /**
     * @test
     */
    public function ユーザを閲覧する()
    {
        // データを作成
        $user = User::factory()->create();
        $other = User::factory()->create();

        // レスポンスを取得
        $response = $this->get(route('user.show', ['user' => $user->id]));

        // レスポンスの確認
        $response->assertSee($user->name);
        $response->assertSee($user->email);
        $response->assertSee($user->player_name);
        $response->assertSee($user->friend_code);
        $response->assertDontSee($other->email);
    }

    /**
     * @test
     */
    public function ユーザの編集画面()
    {
        // データを作成
        $user = User::factory()->create();

        // レスポンスを取得
        $response = $this->get(route('user.edit', ['user' => $user->id]));

        // レスポンスの確認
        $response->assertSee($user->name);
        $response->assertSee($user->email);
        $response->assertSee($user->player_name);
        $response->assertSee($user->friend_code);
    }

    /**
     * @test
     */
    public function ユーザの更新()
    {
        // データを作成
        $user = User::factory()->create();
        $new = User::factory()->make()->toArray();

        // レスポンスを取得
        $response = $this->put(route('user.update', ['user' => $user->id]), $new);

        // レスポンスはリダイレクトになっている
        $response->assertRedirect(route('user.show', ['user' => $user->id]));

        // データの確認
        $test = User::find($user->id);
        $this->assertEquals($new['name'], $test->name);
        $this->assertEquals($new['player_name'], $test->player_name);
        $this->assertEquals($new['email'], $test->email);
        $this->assertEquals($new['friend_code'], $test->friend_code);
    }

    /**
     * @test
     */
    public function 管理者は管理者権限を付与できる()
    {
        // データを作成
        $user = User::factory()->create();
        /** @var User */
        $new = User::factory()->make();
        $new->is_admin = true;

        // レスポンスを取得
        $response = $this->put(route('user.update', ['user' => $user->id]), $new->toArray());

        // レスポンスはリダイレクトになっている
        $response->assertRedirect(route('user.show', ['user' => $user->id]));

        // データの確認
        $test = User::find($user->id);
        $this->assertEquals($new['name'], $test->name);
        $this->assertEquals($new['player_name'], $test->player_name);
        $this->assertEquals($new['email'], $test->email);
        $this->assertEquals($new['friend_code'], $test->friend_code);
        $this->assertTrue($test->is_admin);
    }

    /**
     * @test
     */
    public function 一般ユーザは自分の情報更新をすることができるが管理者権限は取得できない()
    {
        // データを作成
        /** @var User */
        $user = User::factory()->create();
        $this->actingAs($user);
        /** @var User */
        $new = User::factory()->make();
        $new->is_admin = true;

        // レスポンスを取得
        $response = $this->put(route('user.update', ['user' => $user->id]), $new->toArray());

        // レスポンスはリダイレクトになっている
        $response->assertRedirect(route('user.show', ['user' => $user->id]));

        // データの確認
        $test = User::find($user->id);
        $this->assertEquals($new['name'], $test->name);
        $this->assertEquals($new['player_name'], $test->player_name);
        $this->assertEquals($new['email'], $test->email);
        $this->assertEquals($new['friend_code'], $test->friend_code);
        $this->assertFalse($test->is_admin);
    }

    /**
     * @test
     */
    public function ユーザ生成時のバリデーション()
    {
        $response = $this->post(route('user.store'), []);
        $response->assertInvalid([
            'name' => '名前は、必ず指定してください。',
            'email' => 'メールアドレスは、必ず指定してください。',
        ]);
    }
}
