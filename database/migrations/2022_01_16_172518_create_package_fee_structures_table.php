<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_fee_structures', function (Blueprint $table) {
            $table->id();
            $table->integer('package');
            $table->string('fee_Type');
            $table->string('registration_fee')->nullable();
            $table->string('holding_fee')->nullable();
            $table->string('late_payment_fee')->nullable();
            $table->string('termination_fee')->nullable();
            $table->string('reactivation_fee')->nullable();
            $table->string('monthly_fee_1day')->nullable();
            $table->string('monthly_fee_2day')->nullable();
            $table->string('monthly_fee_3day')->nullable();
            $table->string('monthly_fee_4day')->nullable();
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
        Schema::dropIfExists('package_fee_structures');
    }
}