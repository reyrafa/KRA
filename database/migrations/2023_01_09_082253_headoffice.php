<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headoffice', function(Blueprint $table){
            $table->id();
            $table->integer('UID');
            $table->string('fname');
            $table->string('lname');
            $table->string('mname');
            $table->integer('companyID');
            $table->integer('branchID');
            $table->integer('branchUnderID');
            $table->integer('positionID');
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
        Schema::dropIfExists('headoffice');
    }
};
