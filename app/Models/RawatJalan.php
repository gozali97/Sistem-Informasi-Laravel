<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawatJalan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'jalan_no_reg';
    public $incrementing = false;
}
