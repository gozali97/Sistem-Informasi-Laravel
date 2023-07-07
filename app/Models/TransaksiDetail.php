<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'transaksi_details';
    protected $primaryKey = 'lab_auto_nomor';

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, "lab_nomor", "lab_nomor");
    }
}
