<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string("invoice_no");
            $table->string("title");
            $table->integer("bill_to");
            $table->string("fullName")->nullable();
            $table->string("state")->nullable();
            $table->string("city")->nullable();
            $table->string("street")->nullable();
            $table->string("zipcode")->nullable();
            $table->integer("player")->nullable();
            $table->string("due_type");
            $table->date("send_date");
            $table->date("due_date")->nullable();
            $table->double("discount");
            $table->double("discount_price");
            $table->double("processing_fee");
            $table->double("processing_fee_price");
            $table->double("tax_rate");
            $table->double("tax_rate_price");
            $table->double("total_bill");
            $table->text("message")->nullable();
            $table->integer("invoice_status")->default(1);
            $table->string("invoice_pdf");
            $table->integer("transaction_id")->nullable();
            $table->integer("created_by");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
