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
        Schema::create('fileUpload', function(Blueprint $table){
            $table->id();
            $table->integer('uploaderID');
            $table->integer('monthUploadID');
            $table->integer('yearUploadID');
            $table->integer('branchID');
            $table->integer('branchUnderID');
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
        Schema::dropIfExists('fileUpload');
    }
};
