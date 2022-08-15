<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_conversions', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->integer('order_id')->nullable();
            $table->integer('parent_id');
            $table->integer('conversion_type')->nullable()->comment('1- Get Register, 2- Schedule Free Class');
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
        Schema::dropIfExists('lead_conversions');
    }
}