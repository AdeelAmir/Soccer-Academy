<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::create('user_evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('respective')->nullable();
            $table->string('attention')->nullable();
            $table->string('concentration')->nullable();
            $table->string('leadership')->nullable();
            $table->string('energetic')->nullable();
            $table->string('discipline')->nullable();
            $table->string('running')->nullable();
            $table->string('passing_receiving')->nullable();
            $table->string('kicking')->nullable();
            $table->string('ball_control')->nullable();
            $table->string('shooting')->nullable();
            $table->string('balance')->nullable();
            $table->date('evaluation_date');
            $table->double('total_marks');
            $table->double('obtained_marks');
            $table->string('grade');
            $table->string('report_pdf')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_evaluations');
    }
}
