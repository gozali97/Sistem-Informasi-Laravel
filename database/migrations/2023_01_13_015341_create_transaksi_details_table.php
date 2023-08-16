<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->string('lab_nomor', 15);
            $table->string('lab_kode_detail', 8);
            $table->increments('lab_auto_nomor');
            $table->string('lab_nama', 40);
            $table->decimal('lab_banyak', 14, 2);
            $table->decimal('lab_tarif', 14, 2);
            $table->decimal('lab_diskon_prs', 9, 2);
            $table->decimal('lab_diskon', 14, 2);
            $table->decimal('lab_asuransi', 14, 2)->nullable();
            $table->decimal('lab_jumlah', 14, 2);
            $table->decimal('lab_pribadi', 14, 2);
            $table->string('lab_tarif_askes', 6)->nullable();
            $table->string('id_client', 10)->nullable();
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
        Schema::dropIfExists('transaksi_details');
    }
}
