<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BarcodePemeriksaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 5)->nullable();
            $table->string('nama');
            $table->string('sign')->nullable();
            $table->string('inst')->nullable();
            $table->string('kopi')->nullable();
            $table->string('user_id')->nullable();
            $table->date('use_date')->nullable();
            $table->string('id_client')->nullable();
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
        Schema::dropIfExists('barcode_pemeriksaan');
    }
}
