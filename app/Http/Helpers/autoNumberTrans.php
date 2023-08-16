<?php

// use Illuminate\Support\Facades\Auth;
namespace App\Helpers;

use Auth;

use App\Models\Nomor;

class autoNumberTrans
{

    public static function autoNumber($kode, $len, $simpan)
    {
        date_default_timezone_set("Asia/Bangkok");

        $_this = new self;
        $nomor = $_this->ambilNomorAkhir($kode, $simpan);
        $nomor = $kode . sprintf('%0' . $len . 's', $nomor);

        return $nomor;
    }

    public function simpanNomor($kode, $nomor)
    {
        $nmr = new Nomor;

        $nmr->nomorkode = $kode;
        $nmr->nomorakhir = $nomor;
        $nmr->idclient = Auth::User()->idlab;

        $nmr->save();

    }

    public function updateNomor($kode, $nomor)
    {
        $nmr = Nomor::where('nomorkode', '=', $kode)->first();

        $nmr->nomorakhir = $nomor;

        $nmr->save();
    }

    public function ambilNomorAkhir($kode, $simpan)
    {
        $nomors = Nomor::where('nomorkode', $kode)->first();
        $nomorAkhir = 0;

        if (count((array)$nomors) > 0) {
            $nomorAkhir = $nomors->nomorakhir + 1;

            if ($simpan) {
                $this->updateNomor($kode, $nomorAkhir);
            }
        } else {
            $nomorAkhir = 1;

            if ($simpan) {
                $this->simpanNomor($kode, $nomorAkhir);
            }
        }

        return $nomorAkhir;
    }
}
