<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\LoginHash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group login
 */
class LoginHashTest extends TestCase
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
    public function ログイン用のURLを発行する()
    {
        $this->user->login_hash = Str::random(100);
        $notification = new LoginHash;

        $mail = $notification->toMail($this->user);
        $this->assertEquals(route('login-check', ['hash' => $this->user->login_hash]), $mail->actionUrl);
    }
}
