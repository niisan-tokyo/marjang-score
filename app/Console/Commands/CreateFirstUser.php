<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateFirstUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'first-user:create {name} {player_name} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初期ユーザを作成する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = new User($this->arguments());
        $user->is_admin = true;
        $user->password = '1qaz2wsx3edc';
        $user->save();
        return 0;
    }
}
