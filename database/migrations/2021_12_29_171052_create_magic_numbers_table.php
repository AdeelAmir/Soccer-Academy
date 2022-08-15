<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMagicNumbersTable extends Migration
{
    public function up()
    {
        Schema::create('magic_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('processing_fee')->nullable();
            $table->string('document_deadline')->nullable();
            $table->string('holding_deadline')->nullable();
            $table->string('parent_visit')->nullable();
            $table->string('payment_reminder')->nullable();
            $table->string('affiliate_commission')->nullable();
            $table->string('tax_rate')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('magic_numbers');
    }
}
