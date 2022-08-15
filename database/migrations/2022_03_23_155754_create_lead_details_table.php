<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('lead_details', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->string('playerFirstName')->nullable();
            $table->string('playerLastName')->nullable();
            $table->date('playerDOB')->nullable();
            $table->integer('playerAge')->nullable();
            $table->string('playerGender')->nullable();
            $table->string('playerRelationship')->nullable();
            $table->string('location')->nullable();
            $table->string('locationZipcode')->nullable();
            $table->string('message')->nullable();
            $table->integer('free_class')->nullable();
            $table->string('free_class_date')->nullable();
            $table->time('free_class_time')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_details');
    }
}
