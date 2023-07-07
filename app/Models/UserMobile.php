<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserMobile extends Authenticatable
{
    use Notifiable, MustVerifyEmail;
    use HasFactory;

    protected $table 	= 'user_mobiles';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'nama_lengkap', 'no_ktp', 'email', 'telepon', 'password', 'status_user', 'create_date', 'create_by', 'update_date', 'update_by', 'create_at', 'update_at'];

    public function pasien(){
        return $this->hasOne(Pasien::class, "user_mobile_id", "id");
    }
}
