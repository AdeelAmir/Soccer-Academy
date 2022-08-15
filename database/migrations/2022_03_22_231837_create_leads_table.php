<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_number')->nullable();
            $table->string('parentFirstName')->nullable();
            $table->string('parentLastName')->nullable();
            $table->string('parentPhone')->nullable();
            $table->string('parentPhone2')->nullable();
            $table->string('parentEmail')->nullable();
            $table->date('parentDOB')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('zipcode')->nullable();
            $table->integer('getregister_or_schedulefreeclass')->nullable()->comment('1- Get Register, 2- Schedule Free Class');
            $table->integer('lead_status')->default(1);
            $table->string('subscribe')->nullable();
            $table->boolean('is_duplicate')->comment('1- Duplicate, 0- Unique');
            $table->integer('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
