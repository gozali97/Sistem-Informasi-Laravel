<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogbooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('user_mobile_id');
            $table->string('log_ip_address', 20)->nullable();
            $table->timestamp('log_date')->nullable();
            $table->string('log_menu_no', 10)->nullable();
            $table->string('log_menu_nama', 100)->nullable();
            $table->char('log_jenis', 1)->nullable();
            $table->string('log_no_bukti', 30)->nullable();
            $table->string('log_keterangan', 300)->nullable();
            $table->decimal('log_jumlah', 14, 2)->nullable();
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
        Schema::dropIfExists('logbooks');
    }
}
