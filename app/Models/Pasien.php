<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table    ='pasiens';
    public $timestamps  =false;
    public $incrementing = false;
    protected  $primaryKey = 'pasien_nomor_rm';
    protected $guarded = [];

    public function users(){
        return $this->hasOne(UserMobile::class, "id", "user_mobile_id");
    }

    public static function insertData($data)
    {
        return Pasien::create($data);
    }

    public function transaksis()
    {
        return $this->belongsToMany(Transaksi::class, 'pasien_nomor_rm', 'pasien_nomor_rm');
    }
}
