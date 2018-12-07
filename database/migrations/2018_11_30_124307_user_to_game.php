<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserToGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('user_to_game', function (Blueprint $table) {
        $table->increments('user_to_game_id');
        $table->integer('game_id');
        $table->integer('user_id');
        $table->text('complete');
        $table->text('incomplete');
        $table->text('description');
        $table->text('keyboard');
        $table->integer('guesses');
        $table->integer('mistakes');
        $table->text('letters_played');
        $table->integer('result');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_to_game');
    }
}
