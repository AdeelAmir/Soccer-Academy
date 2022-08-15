<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageItemsTable extends Migration
{
    public function up()
    {
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->integer('package');
            $table->string('item');
            $table->string('type')->nullable();
            $table->string('price');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('package_items');
    }
}
