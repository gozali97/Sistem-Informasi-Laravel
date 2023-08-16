<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabPeriksasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_periksas', function (Blueprint $table) {
            $table->string('lab_kode', 8);
            $table->string('lab_nama', 50)->nullable();
            $table->string('lab_grup')->nullable();
            $table->string('lab_sub_grup')->nullable();
            $table->string('lab_tipe')->nullable();
            $table->string('lab_satuan')->nullable();
            $table->string('lab_seq')->nullable();
            $table->string('lab_barcode')->nullable();
            $table->string('grup_kode')->nullable();
            $table->string('lab_jenis')->nullable();
            $table->string('lab_kelompok')->nullable();
            $table->string('lab_rekap')->nullable();
            $table->string('lab_metode', 30)->nullable();
            $table->string('lab_alias', 50)->nullable();
            $table->string('lab_numeric')->nullable();
            $table->string('si_factor', 50)->nullable();
            $table->string('si_unit', 10)->nullable();
            $table->string('schedule')->nullable();
            $table->string('id_client');
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
        Schema::dropIfExists('lab_periksas');
    }
}
