<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->comment('1- Invoice, 2- Subscription');
            $table->integer('bill_to')->comment('User Id (parent id)');
            $table->integer('order_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->double('total_amount');
            $table->double('amount_paid');
            $table->integer('status')->comment('1- Pending, 2- Paid, 3- Failed');
            $table->date('paid_date')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
