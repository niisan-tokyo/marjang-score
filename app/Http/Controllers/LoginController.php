<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginHash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    /**
     * ログイン用のメールアドレスを受け取る
     * 正しいメールアドレスであれば、そこにメールを送ってログインできる
     *
     * @param Request $request
     */
    public function publishHash(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user or $user->login_hash or $user->hash_limit > Carbon::now()) {
            return redirect(route('login-index'));
        }

        $user->login_hash = Str::random(256);
        $user->hash_limit = Carbon::now()->addMinutes(10);
        
        if ($user->save()) {
            $user->notify(new LoginHash);
        }
        return redirect(route('login-published'));
    }

    /**
     * hashをチェックして入場する
     *
     * @param string $hash
     */
    public function hashCheck(string $hash)
    {
        $user = User::whereLoginHash($hash)->where('hash_limit', '>', Carbon::now())->first();
        if ($user) {
            $user->login_hash = null;
            $user->hash_limit = null;
            $user->save();
            Auth::login($user);
        }
        return redirect(route('home'));
    }

    public function password(Request $request)
    {
        Auth::attempt($request->all());
        return redirect(route('home'));
    }
}
