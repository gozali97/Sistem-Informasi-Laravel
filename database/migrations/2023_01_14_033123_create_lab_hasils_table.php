<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabHasilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_hasils', function (Blueprint $table) {
            $table->id();
            $table->string('lab_nomor');
            $table->string('lab_auto_nomor', 50);
            $table->integer('user_mobile_id');
            $table->string('pasien_nomor_rm', 13);
            $table->string('lab_kode', 12);
            $table->string('lab_nama', 50);
            $table->string('lab_metode', 30);
            $table->string('lab_hasil', 4000);
            $table->string('lab_satuan', 15);
            $table->string('lab_harga_norm', 100);
            $table->string('lab_keterangan', 200)->nullable();
            $table->char('lab_numeric', 1)->nullable();
            $table->string('id_client', 10)->nullable();
            $table->string('status_pemeriksaan', 10)->nullable();
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
        Schema::dropIfExists('lab_hasils');
    }
}
