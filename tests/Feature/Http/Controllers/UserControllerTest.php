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
        $response->assertDontSee($user->password);
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
        $response = $this->put(route('user.update', ['user' => $user->id]), $new + [
            'password' => 'abcdefghijk12AG',
            'password_confirmation' => 'abcdefghijk12AG'
        ]);

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
    public function ユーザ更新時、パスワードを変えない場合はパスワードに変化なし()
    {
        // データを作成
        $user = User::factory()->create();
        $new = User::factory()->make()->toArray();

        // レスポンスを取得
        $response = $this->put(route('user.update', ['user' => $user->id]), $new);

        // 正常に動作したことの確認
        $response->assertRedirect(route('user.show', ['user' => $user->id]));

        // データの確認
        $test = User::find($user->id);
        $this->assertEquals($user->password, $test->password);
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
