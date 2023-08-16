<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaketLabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paket_labs', function (Blueprint $table) {
            $table->string('paket_kode', 8)->primary();
            $table->string('paket_kelompok', 2)->nullable();
            $table->string('paket_nama', 50);
            $table->decimal('paket_vip', 14, 2)->nullable();
            $table->decimal('paket_utama', 14, 2)->nullable();
            $table->decimal('paket_kelas_1', 14, 2)->nullable();
            $table->decimal('paket_kelas_2', 14, 2)->nullable();
            $table->decimal('paket_kelas_3', 14, 2)->nullable();
            $table->decimal('paket_jalan', 14, 2)->nullable();
            $table->string('paket_status', 1)->nullable();
            $table->string('paket_askes', 12)->nullable();
            $table->dateTime('paket_periode')->nullable();
            $table->decimal('paket_diskon', 14, 2)->nullable();
            $table->timestamp('periode_start')->nullable();
            $table->timestamp('periode_end')->nullable();
            $table->string('id_client', 10)->nullable();
            $table->string('path_gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('catatan')->nullable();
            $table->string('manfaat')->nullable();
            $table->decimal('persen_diskon', 14, 2)->nullable();
            $table->decimal('fix_value_diskon', 14, 2)->nullable();
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
        Schema::dropIfExists('paket_labs');
    }
}
