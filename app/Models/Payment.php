<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['va_number','amount','status'];

    public function checkBalance()
    {
        // proses untuk mengecek saldo
        // ...
    }

    public function transfer($va_number, $amount)
    {
        // proses untuk mentransfer dana
        // ...
    }

    public function savePayment($va_number, $amount)
    {
        $this->va_number = $va_number;
        $this->amount = $amount;
        $this->status = 'success';
        $this->save();
    }
}
