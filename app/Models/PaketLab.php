<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketLab extends Model
{
    use HasFactory;
    protected $table = 'paket_labs';
    protected $primaryKey = 'paket_kode';
    public $incrementing = false;
    protected $guarded = [];
}
