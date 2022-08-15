<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendencesTable extends Migration
{
    public function up()
    {
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            $table->integer('player_id');
            $table->date('attendence_date');
            $table->string('status')->comment('P=Present, A=Absent, L=Late');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendences');
    }
}
