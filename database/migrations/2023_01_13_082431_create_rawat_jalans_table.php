<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawatJalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rawat_jalans', function (Blueprint $table) {
            $table->string('jalan_no_reg')->primary();
            $table->string('pasien_nomor_rm', 13);
            $table->integer('user_mobile_id');
            $table->char('pasien_gender', 1);
            $table->string('pasien_nama', 40);
            $table->string('pasien_alamat', 40);
            $table->string('pasien_kota', 30);
            $table->string('pasien_umur_thn', 5);
            $table->string('pasien_umur_bln', 5);
            $table->string('pasien_umur_hr', 5);
            $table->timestamp('jalan_tanggal');
            $table->integer('unit_kode');
            $table->string('dokter_kode', 6);
            $table->string('jalan_no_urut', 4);
            $table->char('jalan_asal_pasien', 1);
            $table->char('jalan_pas_baru', 1);
            $table->char('jalan_cara_daftar', 1)->nullable();
            $table->string('jalan_ket_sumber', 50)->nullable();
            $table->string('jalan_diag_poli', 60)->nullable();
            $table->char('jalan_kasus_poli', 1)->nullable();
            $table->string('jalan_diag_kode_1', 12)->nullable();
            $table->string('jalan_diag_nama_1', 50)->nullable();
            $table->char('jalan_kasus_1', 1)->nullable();
            $table->string('jalan_diag_imun_1', 2)->nullable();
            $table->string('jalan_diag_kode_2', 12)->nullable();
            $table->string('jalan_diag_nama_2', 50)->nullable();
            $table->char('jalan_kasus_2', 1)->nullable();
            $table->string('jalan_diag_imun_2', 2)->nullable();
            $table->string('jalan_diag_kode_3', 12)->nullable();
            $table->string('jalan_diag_nama_3', 50)->nullable();
            $table->char('jalan_kasus_3', 1)->nullable();
            $table->string('jalan_diag_imun_3', 2)->nullable();
            $table->string('jalan_tind_kode_1', 6)->nullable();
            $table->string('jalan_tind_nama_1', 60)->nullable();
            $table->string('jalan_tind_kode_2', 6)->nullable();
            $table->string('jalan_tind_nama_2', 60)->nullable();
            $table->string('jalan_tind_kode_3', 6)->nullable();
            $table->string('jalan_tind_nama_3', 60)->nullable();
            $table->string('jalan_tind_kode_4', 6)->nullable();
            $table->string('jalan_tind_nama_4', 60)->nullable();
            $table->string('jalan_tind_kode_5', 6)->nullable();
            $table->string('jalan_tind_nama_5', 60)->nullable();
            $table->char('jalan_status', 1);
            $table->char('jalan_jenis_bayar', 1);
            $table->decimal('jalan_daftar', 14, 2);
            $table->decimal('jalan_kartu', 14, 2);
            $table->decimal('jalan_periksa', 14, 2);
            $table->decimal('jalan_jumlah', 14, 2);
            $table->decimal('jalan_potongan', 14, 2)->nullable();
            $table->decimal('jalan_pribadi', 14, 2);
            $table->decimal('jalan_asuransi', 14, 2);
            $table->decimal('jalan_asuransi_daftar', 14, 2);
            $table->decimal('jalan_asuransi_periksa', 14, 2);
            $table->decimal('jalan_plafon', 14, 2);
            $table->string('prsh_kode', 6);
            $table->string('member_nomor', 25)->nullable();
            $table->string('jalan_id_pasien', 50)->nullable();
            $table->string('user_id_1', 50)->nullable();
            $table->timestamp('user_date_1');
            $table->string('user_id_2', 50)->nullable();
            $table->timestamp('user_date_2');
            $table->char('jalan_byr_jenis', 1);
            $table->string('jalan_byr_nomor', 15)->nullable();
            $table->string('jalan_anamnesa', 50)->nullable();
            $table->string('id_client', 10)->nullable();
            $table->string('pngrm_kode', 6);
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
        Schema::dropIfExists('rawat_jalans');
    }
}
