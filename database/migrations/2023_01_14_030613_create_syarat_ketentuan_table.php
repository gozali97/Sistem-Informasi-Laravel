<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyaratKetentuanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syarat_ketentuan', function (Blueprint $table) {
            $table->id();
            $table->integer('kategori_nama');
            $table->string('judul_syarat_ketentuan');
            $table->text('syarat_ketentuan_value');
            $table->string('path_gambar');
            $table->string('status');
            $table->string('create_by');
            $table->string('update_by');
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
        Schema::dropIfExists('syarat_ketentuans');
    }
}
