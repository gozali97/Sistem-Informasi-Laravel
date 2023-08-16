<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarif_labs', function (Blueprint $table) {
            $table->id();
            $table->string('tarif_kode');
            $table->string('tarif_kelompok', 4);
            $table->string('tarif_nama');
            $table->decimal('tarif_jalan', 14, 2);
            $table->string('tarif_status', 1)->nullable();
            $table->string('tarif_tipe')->nullable();
            $table->string('id_client')->nullable();
            $table->string('path_gambar')->nullable();
            $table->decimal('promo_value')->nullable();
            $table->decimal('promo_percent')->nullable();
            $table->decimal('fix_value')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('catatan')->nullable();
            $table->string('manfaat')->nullable();
            $table->dateTime('periode_start')->nullable();
            $table->dateTime('periode_end')->nullable();
            $table->string('status_promo', 2)->nullable();
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
        Schema::dropIfExists('tarif_labs');
    }
}
