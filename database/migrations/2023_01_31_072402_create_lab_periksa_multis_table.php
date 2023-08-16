


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabPeriksaMultisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_periksa_multis', function (Blueprint $table) {
            $table->id();
            $table->string('l', 10);
            $table->string('test_id', 20);
            $table->string('test_nama', 50)->nullable();
            $table->string('create_by', 50)->nullable();
            $table->timestamp('create_date')->nullable();
            $table->string('update_by', 50)->nullable();
            $table->timestamp('update_date')->nullable();
            $table->string('test_abbr', 50)->nullable();
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
        Schema::dropIfExists('lab_periksa_multis');
    }
}
