<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */
    public function ユーザを作成する()
    {
        // 適当な入力値を生成
        $user = User::factory()->make()->toArray();

        // 登録処理にリクエストを投げてユーザを生成
        $response = $this->post(route('user.store'), $user + [
            'password' => 'abcdefghijk12AG',
            'password_confirmation' => 'abcdefghijk12AG'
        ]);

        // 入力内容がちゃんと保存されていることを確認
        $test = User::first();
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
    public function ユーザ生成時のバリデーション()
    {
        $response = $this->post(route('user.store'), ['password' => 'abcdefg']);
        $response->assertInvalid([
            'name' => '名前は、必ず指定してください。',
            'email' => 'メールアドレスは、必ず指定してください。',
            'password' => 'パスワードとパスワード確認が一致しません。'
        ]);
    }
}
