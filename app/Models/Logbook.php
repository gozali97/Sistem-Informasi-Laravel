<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table ='logbooks';
    protected $primaryKey = 'user_id';
}