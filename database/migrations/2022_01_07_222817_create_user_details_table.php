<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('parent_id')->nullable();
            $table->string('firstName');
            $table->string('middleName')->nullable();
            $table->string('lastName');
            $table->string('dob');
            $table->string('gender');
            $table->text('managerLocations')->nullable();
            $table->text('coachLevels')->nullable();
            $table->text('coachCategories')->nullable();
            $table->text('coachLocations')->nullable();
            $table->string('athletesParent')->nullable();
            $table->string('athletesLevel')->nullable();
            $table->string('athletesCategory')->nullable();
            $table->string('athletesTrainingDays')->nullable();
            $table->string('athletesDoctorName')->nullable();
            $table->string('athletesDoctorPhoneNumber')->nullable();
            $table->string('athletesInsuranceName')->nullable();
            $table->string('athletesPolicyNumber')->nullable();
            $table->string('athletesHeightFt')->nullable();
            $table->string('athletesHeightInches')->nullable();
            $table->string('athletesWeight')->nullable();
            $table->string('athletesAllergies')->nullable();
            $table->string('athletesRelationship')->nullable();
            $table->string('athletesPosition')->nullable();
            $table->string('email')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('socialMedia')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('profile_pic')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
