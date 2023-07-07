<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPelaku extends Model
{
    protected $table ='tpelaku';
    public $timestamps=false;
    protected $primaryKey = 'pelakukode';
}
