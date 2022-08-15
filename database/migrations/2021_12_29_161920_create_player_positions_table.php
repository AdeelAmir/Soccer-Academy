<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayerPositionsTable extends Migration
{
    public function up()
    {
        Schema::create('player_positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('symbol');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('player_positions');
    }
}
