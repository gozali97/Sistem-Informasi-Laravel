<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasiensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->string('pasien_nomor_rm', 13);
            $table->integer('user_mobile_id')->nullable();
            $table->string('pasien_nama');
            $table->string('pasien_gender');
            $table->string('pasien_alamat', 40);
            $table->integer('pasien_kelurahan');
            $table->integer('pasien_kecamatan');
            $table->integer('pasien_kota');
            $table->integer('pasien_prov');
            $table->string('pasien_rt', 8);
            $table->string('pasien_rw', 8);
            $table->string('pasien_wilayah', 10);
            $table->string('pasien_telp', 15);
            $table->string('pasien_hp', 20);
            $table->string('pasien_tmp_lahir', 30);
            $table->string('pasien_tgl_lahir', 12);
            $table->string('pasien_kerja_kode', 3);
            $table->string('pasien_kerja', 30);
            $table->string('pasien_kerja_alamat', 40);
            $table->string('pasien_gol_darah', 1);
            $table->string('pasien_agama', 1);
            $table->string('pasien_pdk', 1);
            $table->string('pasien_status_kw', 1);
            $table->string('pasien_klg_nama', 40)->nullable();
            $table->string('pasien_klg_kerja', 30)->nullable();
            $table->char('pasien_klg_pdk', 1)->nullable();
            $table->char('pasien_klg_hub', 1)->nullable();
            $table->string('pasien_no_id')->unique();
            $table->string('member_nomor', 25)->nullable();
            $table->char('pasien_jenis', 1)->nullable();
            $table->dateTime('pasien_tgl_input');
            $table->string('user_id', 10)->nullable();
            $table->string('pasien_panggilan', 50)->nullable();
            $table->string('pasien_prioritas', 1)->nullable();
            $table->string('pasien_klg_tlp', 30)->nullable();
            $table->string('pasien_klg_alamat', 200)->nullable();
            $table->string('id_client', 10)->nullable();
            $table->string('pasien_perusahaan', 15)->nullable();
            $table->string('pasien_divisi', 15)->nullable();
            $table->string('pasien_posisi', 15)->nullable();
            $table->string('no_mcu', 15)->nullable();
            $table->string('pasien_foto')->nullable();
            $table->string('pasien_email', 150)->nullable();
            $table->string('pasien_kontak_darurat')->nullable();
            $table->string('pasien_catatan')->nullable();
            $table->string('pasien_title', 15)->nullable();
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
        Schema::dropIfExists('pasiens');
    }
}
