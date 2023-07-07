<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifLab extends Model
{
    use HasFactory;
    protected $table = 'tarif_labs';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function tarif_var(){
        return $this->hasOne(TarifVar::class, 'var_kode', 'tarif_kelompok');
    }
}
