<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationLevelsTable extends Migration
{
    public function up()
    {
        Schema::create('location_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id');
            $table->integer('level');
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_levels');
    }
}
