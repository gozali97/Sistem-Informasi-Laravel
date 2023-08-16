<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTtdDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ttd_dokters', function (Blueprint $table) {
            $table->id();
            $table->string('dokter_id', 10)->nullable();
            $table->string('keterangan')->nullable();
            $table->text('ttd')->nullable();
            $table->string('status', 2)->nullable();
            $table->string('create_by', 50)->nullable();
            $table->string('update_by', 50)->nullable();
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
        Schema::dropIfExists('ttd_dokters');
    }
}
