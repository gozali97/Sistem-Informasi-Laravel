<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->increments('transaksi_id');
            $table->string('lab_nomor', 15);
            $table->integer('user_mobile_id');
            $table->timestamp('lab_tanggal');
            $table->string('lab_reff_no', 15);
            $table->char('lab_jenis', 1);
            $table->string('lab_no_reg', 15);
            $table->char('lab_pas_baru', 1);
            $table->string('tt_nomor', 10)->nullable();
            $table->string('kelas_kode', 50);
            $table->string('dokter_kode', 6);
            $table->string('dokter_nama', 40);
            $table->string('lab_jam_sample', 5);
            $table->string('pasien_nomor_rm', 13);
            $table->char('pasien_gender', 1);
            $table->string('pasien_nama', 40);
            $table->string('pasien_alamat', 40);
            $table->string('pasien_kota', 100);
            $table->string('pasien_umur_thn', 5);
            $table->string('pasien_umur_bln', 5);
            $table->string('pasien_umur_hr', 5);
            $table->string('lab_catatan', 4000)->nullable();
            $table->string('lab_petugas', 20)->nullable();
            $table->string('lab_cat_hasil', 400)->nullable();
            $table->string('prsh_kode', 6);
            $table->decimal('lab_jumlah', 14, 2);
            $table->decimal('lab_asuransi', 14, 2)->nullable();
            $table->decimal('lab_pribadi', 14, 2);
            $table->char('lab_byr_jenis', 1)->nullable();
            $table->timestamp('lab_byr_tgl')->nullable();
            $table->string('lab_byr_nomor', 15)->nullable();
            $table->string('lab_byr_ket', 40)->nullable();
            $table->string('user_id', 25)->nullable();
            $table->timestamp('user_date')->nullable();
            $table->string('user_id2', 25)->nullable();
            $table->timestamp('user_date2')->nullable();
            $table->char('lab_ambil_status', 1)->nullable();
            $table->timestamp('lab_ambil_tgl')->nullable();
            $table->string('lab_ambil_jam', 5)->nullable();
            $table->string('lab_ambil_nama', 25)->nullable();
            $table->string('jenis_layanan', 50)->nullable();
            $table->char('lab_cetak_status', 1)->nullable();
            $table->string('id_client', 25)->nullable();
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
        Schema::dropIfExists('transaksis');
    }
}
