<?php

namespace Tests\Feature\Console\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use Illuminate\Support\Str;

class CreateFirstUserTest extends TestCase
{
    
    use RefreshDatabase;

    /**
     * @test
     */
    public function 初期ユーザ作成()
    {
        $mail = Str::random(20) . '@example.com';
        Artisan::call('first-user:create', ['name' => 'someone', 'player_name' => 'displayname', 'email' => $mail]);
        $user = User::whereEmail($mail)->first();
        $this->assertEquals('someone', $user->name);
        $this->assertEquals('displayname', $user->player_name);
    }
}
