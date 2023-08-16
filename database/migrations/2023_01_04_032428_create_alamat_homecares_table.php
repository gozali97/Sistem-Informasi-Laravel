<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlamatHomecaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alamat_homecares', function (Blueprint $table) {
            $table->integer('alamat_homecare_id')->autoIncrement();
            $table->integer('user_mobile_id')->nullable();
            $table->string('nama_lengkap');
            $table->string('nomor_rumah');
            $table->string('detail_alamat');
            $table->string('alamat');
            $table->string('longitude');
            $table->string('latitude');
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
        Schema::dropIfExists('alamat_homecares');
    }
}
