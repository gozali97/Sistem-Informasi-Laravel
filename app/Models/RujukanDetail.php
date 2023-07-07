<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RujukanDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'rujukan_detail_id';

    public function rujukan()
    {
        return $this->belongsTo(Rujukan::class, "rujukan_id", "rujukan_id");
    }

    public function item()
    {
        return $this->belongsTo(Tarifvar::class, "item_id", "tarifkode");
    }
}
