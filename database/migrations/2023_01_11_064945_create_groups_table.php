<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('grup_jenis', 8);
            $table->string('grup_kode', 4);
            $table->string('grup_nama', 50);
            $table->string('perk_kode', 10)->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('status', 2)->nullable();
            $table->string('id_client', 10);
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
        Schema::dropIfExists('groups');
    }
}
