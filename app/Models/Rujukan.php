<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rujukan extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'rujukan_id';

    public function details()
    {
        return $this->hasMany(RujukanDetail::class, "rujukan_id", "rujukan_id");
    }
}
