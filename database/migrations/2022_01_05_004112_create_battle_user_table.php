<?php

use App\Models\Battle;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBattleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('battle_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Battle::class);
            $table->foreignIdFor(User::class);
            $table->unsignedTinyInteger('start_position');
            $table->integer('score');
            $table->integer('rank_point');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('battle_user');
    }
}
