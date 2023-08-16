<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlamatHilabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alamat_hilabs', function (Blueprint $table) {
            $table->increments('lokasi_id');
            $table->string('nama_lokasi');
            $table->string('alamat');
            $table->string('provinsi');
            $table->string('kota');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('status');
            $table->timestamp('create_date');
            $table->string('create_by');
            $table->timestamp('update_date');
            $table->string('update_by');
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
        Schema::dropIfExists('alamat_hilabs');
    }
}
