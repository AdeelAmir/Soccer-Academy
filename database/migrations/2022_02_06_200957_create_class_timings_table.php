<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassTimingsTable extends Migration
{
    public function up()
    {
        Schema::create('class_timings', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->integer('day');
            $table->time('time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_timings');
    }
}
