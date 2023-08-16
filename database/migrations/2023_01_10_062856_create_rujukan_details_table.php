<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRujukanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rujukan_details', function (Blueprint $table) {
            $table->bigIncrements('rujukan_detail_id');
            $table->bigInteger('rujukan_id');
            $table->string('item_id');
            $table->string('lab_nama', 40);
            $table->decimal('lab_banyak', 14, 2);
            $table->decimal('lab_tarif', 14, 2);
            $table->string('status');
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
        Schema::dropIfExists('rujukan_details');
    }
}
