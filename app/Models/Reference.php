<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $table ='tlabreference';
    public $timestamps=false;

    // protected $fillable = [
    //     'nama', 'sign', 'inst','kopi','idclient','usedate'
    // ];
}
