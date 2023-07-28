<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabReference extends Model
{
    use HasFactory;

    protected $table = 'lab_references';
    protected $primaryKey = 'id';

    protected $guarded = [];
}
