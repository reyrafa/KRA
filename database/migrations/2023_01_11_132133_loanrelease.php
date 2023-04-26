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
        Schema::create('loanrelease', function(Blueprint $table){
            $table->id();
            $table->string('accountName')->nullable();
            $table->double('accountNumber')->nullable();
            $table->string('newSubCategoryDesc')->nullable();
            $table->date('grantedDate')->nullable();
            $table->integer('principalBalance')->nullable();
            $table->string('termClassificationDesc')->nullable();
            $table->string('deductionDesc')->nullable();
            $table->integer('loanTerms')->nullable();
            $table->string('statusDesc')->nullable();
            $table->integer('transactionAmount')->nullable();
            $table->string('securityTypeDesc')->nullable();
            $table->string('loanTypeDesc')->nullable();
            $table->integer('fileUploadID');
      
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
        Schema::dropIfExists('loanrelease');
    }
};
