<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasirJalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kasir_jalans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kasir_nomor', 15);
            $table->string('kasir_no_reg', 15);
            $table->string('kasir_no_trans', 15);
            $table->timestamp('kasir_tanggal');
            $table->timestamp('trans_tanggal');
            $table->string('pasien_nomor_rm', 13);
            $table->string('pasien_nama', 50);
            $table->string('pasien_alamat', 50);
            $table->string('pasien_kota', 40);
            $table->string('unit_kode', 2)->nullable();
            $table->decimal('kasir_biaya', 14, 2)->nullable();
            $table->decimal('kasir_potongan', 14, 2)->nullable();
            $table->decimal('kasir_asuransi', 14, 2)->nullable();
            $table->decimal('kasir_jumlah', 14, 2)->nullable();
            $table->decimal('kasir_tunai', 14, 2)->nullable();
            $table->decimal('kasir_kartu', 14, 2)->nullable();
            $table->decimal('kasir_pribadi', 14, 2)->nullable();
            $table->decimal('kasir_bon_karyawan', 14, 2)->nullable();
            $table->string('kasir_keterangan', 50)->nullable();
            $table->string('prsh_kode', 6)->nullable();
            $table->string('kasir_pot_kode', 6)->nullable();
            $table->string('kasir_pot_ket', 50)->nullable();
            $table->string('kasir_kartu_kode', 6)->nullable();
            $table->string('kasir_kartu_nama', 50)->nullable();
            $table->string('kasir_kartu_nomor', 50)->nullable();
            $table->string('kasir_atas_nama', 50)->nullable();
            $table->string('kasir_bon_kode', 6)->nullable();
            $table->string('kasir_bon_nama', 40)->nullable();
            $table->string('kasir_bon_keterangan', 40)->nullable();
            $table->char('kasir_status', 1)->nullable();
            $table->string('user_id', 50)->nullable();
            $table->timestamp('user_date')->nullable();
            $table->char('user_shift', 1)->nullable();
            $table->string('id_client', 10)->nullable();
            $table->char('kasir_jenis_bayar', 1)->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->integer('user_mobile_id');
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
        Schema::dropIfExists('kasir_jalans');
    }
}
