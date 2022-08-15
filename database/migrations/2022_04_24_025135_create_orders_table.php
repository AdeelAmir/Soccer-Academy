<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->integer('user_id');
            $table->string('email');
            $table->string('password');
            $table->string('payment_intent_id');
            $table->string('client_secret_id');
            $table->string('stripe_customer_id');
            $table->integer('lead_id');
            $table->integer('package_id');
            $table->integer('category_id');
            $table->string('selected_days');
            $table->string('package_type');
            $table->double('registration_fee')->nullable();
            $table->integer('coupon_code_id')->nullable();
            $table->double('coupon_amount')->nullable()->default(0);
            $table->double('sub_fee')->nullable();
            $table->double('tax')->nullable();
            $table->double('processing')->nullable();
            $table->double('amount');
            $table->string('phone');
            $table->string('state');
            $table->string('city');
            $table->string('street');
            $table->string('zipcode');
            $table->integer('status')->comment('0 = unpaid, 1 = paid, 2 = cancelled');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
