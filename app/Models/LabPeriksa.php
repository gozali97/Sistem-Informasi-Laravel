<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPeriksa extends Model
{
    use HasFactory;
    protected $primaryKey = 'lab_kode';

    public $incrementing = false;
    protected $guarded = [];
}
