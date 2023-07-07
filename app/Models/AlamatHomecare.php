<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatHomecare extends Model
{
    protected $table ='alamat_homecares';
    public $timestamps=false;
    public $incrementing = false;
    protected $primaryKey = 'alamat_homecare_id';
    protected $guarded = [];
}
