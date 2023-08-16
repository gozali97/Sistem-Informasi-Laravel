<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_references', function (Blueprint $table) {
            $table->id();
            $table->string('lab_kode');
            $table->string('ref_sex');
            $table->string('ref_begin_age');
            $table->string('ref_end_age');
            $table->string('ref_value')->nullable();
            $table->string('ref_si_value')->nullable();
            $table->string('start',50)->nullable();
            $table->string('end', 50)->nullable();
            $table->string('ref_range', 50)->nullable();
            $table->timestamp('update_date')->nullable();
            $table->string('update_user')->nullable();
            $table->string('id_client')->nullable();
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
        Schema::dropIfExists('lab_references');
    }
}
