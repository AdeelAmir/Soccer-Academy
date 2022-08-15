<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPowersTable extends Migration
{
    public function up()
    {
        Schema::create('user_powers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('parent_location')->nullable();
            $table->string('parent_level')->nullable();
            $table->string('parent_category')->nullable();
            $table->string('player_location')->nullable();
            $table->string('player_level')->nullable();
            $table->string('player_category')->nullable();
            $table->string('coach_location')->nullable();
            $table->string('coach_level')->nullable();
            $table->string('coach_category')->nullable();
            $table->boolean('kpi');
            $table->boolean('lead_funnel');
            $table->boolean('reports');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_powers');
    }
}
