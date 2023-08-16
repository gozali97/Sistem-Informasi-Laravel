<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'tkartukrd';
    protected $primaryKey = 'kartukrdkode';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;
}
