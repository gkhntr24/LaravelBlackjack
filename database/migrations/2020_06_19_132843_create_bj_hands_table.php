<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBjHandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bj_hands', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bjgame_id')->nullable();
            $table->foreign('bjgame_id')->references('id')->on('bj_games');
            $table->text('p_hand');
            $table->text('d_hand');
            $table->integer('player_point')->default(0);
            $table->integer('dealer_point')->default(0);
            $table->enum('state',['new','win','lose','draw'])->default('new');
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
        Schema::dropIfExists('bj_hands');
    }
}
