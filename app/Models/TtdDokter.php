<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtdDokter extends Model
{
    use HasFactory;
    protected $table = 'ttd_dokters';
    protected $gurded = [];
}
