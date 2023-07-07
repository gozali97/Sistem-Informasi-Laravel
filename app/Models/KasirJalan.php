<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasirJalan extends Model
{
    use HasFactory;
    protected $primaryKey = 'kasir_nomor';
    protected $guarded = [];
}
