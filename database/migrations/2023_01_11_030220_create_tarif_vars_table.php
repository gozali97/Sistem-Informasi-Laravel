<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTarifVarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarif_vars', function (Blueprint $table) {
            $table->id();
            $table->string('var_seri', 10);
            $table->string('var_kode', 4);
            $table->integer('var_panjang');
            $table->string('var_nama');
            $table->decimal('var_nilai', 14, 2);
            $table->string('var_kelompok', 6);
            $table->decimal('var_nilai_lama', 14, 2)->nullable();
            $table->string('path_gambar')->nullable();
            $table->char('status', 1)->nullable();
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
        Schema::dropIfExists('tarif_vars');
    }
}
