<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'transaksis';
    protected $primaryKey = 'transaksi_id';

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, "lab_nomor", "lab_nomor");
    }

    public function pasiens()
    {
        return $this->belongsToMany(Pasien::class, 'pasien_nomor_rm', 'pasien_nomor_rm');
    }
}
