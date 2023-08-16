<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerusahaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaans', function (Blueprint $table) {
            $table->string('prsh_kode', 6)->primary();
            $table->string('prsh_nama', 50);
            $table->string('prsh_alamat_1', 100)->nullable();
            $table->string('prsh_alamat_2', 100)->nullable();
            $table->string('prsh_kontak', 50)->nullable();
            $table->text('prsh_lingkup')->nullable();
            $table->text('prsh_tidak_di_tanggung')->nullable();
            $table->text('prsh_prosedur')->nullable();
            $table->text('prsh_penagihan')->nullable();
            $table->string('prsh_lain_lain')->nullable();
            $table->binary('prsh_logo')->nullable();
            $table->binary('prsh_kartu')->nullable();
            $table->char('prsh_status', 1)->nullable();
            $table->char('prsh_jenis', 1)->nullable();
            $table->char('prsh_kerja_sama', 1)->nullable();
            $table->string('prsh_inap_di_tanggung', 100)->nullable();
            $table->decimal('prsh_inap_plafon', 14, 2)->nullable();
            $table->binary('prsh_kartu_2')->nullable();
            $table->binary('prsh_kartu_3')->nullable();
            $table->binary('prsh_kartu_4')->nullable();
            $table->binary('prsh_kartu_5')->nullable();
            $table->timestamp('prsh_berlaku_tgl_1')->nullable();
            $table->timestamp('prsh_berlaku_tgl_2')->nullable();
            $table->string('perk_kode', 10)->nullable();
            $table->decimal('prsh_diskon', 14, 2)->nullable();
            $table->string('id_client', 14, 2)->nullable();
            $table->decimal('prsh_diskon_2', 14, 2)->nullable();
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
        Schema::dropIfExists('perusahaans');
    }
}
