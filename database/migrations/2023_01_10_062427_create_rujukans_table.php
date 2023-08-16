<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRujukansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rujukans', function (Blueprint $table) {
            $table->increments('rujukan_id');
            $table->string('pasien_nomor_rm');
            $table->integer('user_mobile_id');
            $table->string('rujukan_no');
            $table->string('nama_pasien');
            $table->dateTime('tanggal_transaksi');
            $table->string('nama_dokter');
            $table->string('rumah_sakit');
            $table->string('file_rujukan')->nullable();
            $table->decimal('total_harga', 14, 2)->nullable();
            $table->string('status')->nullable();
            $table->dateTime('create_date')->nullable();
            $table->string('create_by')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->string('update_by')->nullable();
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
        Schema::dropIfExists('rujukans');
    }
}
