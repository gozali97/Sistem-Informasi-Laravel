<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VTransaksi extends Model
{
    use HasFactory;
    protected $table 	= 'vjalantrans5';
    public $timestamps 	= false;
    protected $primaryKey = null;
    public $incrementing = false;
    protected $guarded = [];
   
}
