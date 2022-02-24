<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginHash;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * @group login
 */
class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function ログインurlを発行する()
    {
        Notification::fake();
        $response = $this->post(route('login-publish', ['email' => $this->user->email]));
        $response->assertRedirect(route('login-published'));

        $user = User::find($this->user->id);
        Notification::assertSentTo($user, LoginHash::class);
    }

    /**
     * @test
     */
    public function メール経由でログインする()
    {
        $this->user->login_hash = Str::random(100);
        $this->user->hash_limit = Carbon::now()->addMinutes(10);
        $this->user->save();

        $response = $this->get(route('login-check', ['hash' => $this->user->login_hash]));
        $response->assertRedirect(route('home'));

        // ログインしている
        $this->assertFalse(Auth::guest());
    }

    /**
     * @test
     */
    public function パスワードでログインする()
    {
        $password = Str::random(20);
        $this->user->password = $password;
        $this->user->save();

        $response = $this->post(route('login-password-post'), [
            'email' => $this->user->email,
            'password' => $password
        ]);
        
        $response->assertRedirect(route('home'));
        $this->assertFalse(Auth::guest());
        $this->assertEquals($this->user->id, Auth::id());
    }
}
