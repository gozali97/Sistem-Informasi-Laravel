<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_mobiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_lengkap');
            $table->string('no_ktp');
            $table->string('email');
            $table->string('telepon');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('domisili');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('password');
            $table->string('status_user');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_token', 40)->nullable();
            $table->dateTime('create_date');
            $table->string('create_by');
            $table->dateTime('update_date');
            $table->string('update_by');
            $table->rememberToken();
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
        Schema::dropIfExists('user_mobiles');
    }
}
