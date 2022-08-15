<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassAssignsTable extends Migration
{
    public function up()
    {
        Schema::create('class_assigns', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->integer('player_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_assigns');
    }
}
