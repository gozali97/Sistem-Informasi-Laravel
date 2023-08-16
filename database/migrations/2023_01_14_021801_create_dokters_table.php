<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokters', function (Blueprint $table) {
            $table->string('dokter_kode',6)->primary();
            $table->string('dokter_nama',40);
            $table->string('dokter_nama_lengkap',40);
            $table->string('dokter_alamat',40)->nullable();
            $table->string('dokter_kota',40)->nullable();
            $table->string('dokter_telepon',15)->nullable();
            $table->char('dokter_status',1)->nullable();
            $table->string('dokter_jenis',4)->nullable();
            $table->string('unit_kode',2)->nullable();
            $table->string('spes_kode',6)->nullable();
            $table->decimal('dokter_tarif', 14, 2)->nullable();
            $table->decimal('dokter_tarif_baru',14, 2)->nullable();
            $table->decimal('dokter_jasa_tarif', 14, 2)->nullable();
            $table->decimal('dokter_jasa',14, 2)->nullable();
            $table->decimal('dokter_jasa_2',14, 2)->nullable();
            $table->decimal('dokter_jasa_3',14, 2)->nullable();
            $table->char('dokter_jasa_khusus',1)->nullable();
            $table->string('dokter_tunj_ket',50)->nullable();
            $table->decimal('dokter_tunj_jumlah',14, 2)->nullable();
            $table->string('dokter_npwp',20)->nullable();
            $table->string('dokter_memo',40)->nullable();
            $table->string('dokter_jdw_senin',100)->nullable();
            $table->string('dokter_jdw_selasa',100)->nullable();
            $table->string('dokter_jdw_rabu',100)->nullable();
            $table->string('dokter_jdw_kamis',100)->nullable();
            $table->string('dokter_jdw_jumat',100)->nullable();
            $table->string('dokter_jdw_sabtu',100)->nullable();
            $table->string('dokter_jdw_minggu',100)->nullable();
            $table->string('dokter_perk_kode',10)->nullable();
            $table->string('id_client',10);
            $table->string('prsh_kode',14)->nullable();
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
        Schema::dropIfExists('dokters');
    }
}
