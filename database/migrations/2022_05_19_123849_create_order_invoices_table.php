<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('invoice_id');
            $table->date('invoice_date');
            $table->date('invoice_expiry');
            $table->double('tax')->nullable()->comment('Tax Percentage');
            $table->double('processing')->nullable()->comment('Processing Percentage');
            $table->double('amount');
            $table->integer('status')->comment('1 = In Progress, 2 = Expired');
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
        Schema::dropIfExists('order_invoices');
    }
}