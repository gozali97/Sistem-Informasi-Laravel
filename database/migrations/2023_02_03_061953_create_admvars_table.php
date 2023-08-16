<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmvarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admvars', function (Blueprint $table) {
            $table->id();
            $table->string('var_seri')->nullable();
            $table->string('var_kode')->nullable();
            $table->string('var_panjang')->nullable();
            $table->string('var_nama')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status', 1)->nullable();
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
        Schema::dropIfExists('admvars');
    }
}
