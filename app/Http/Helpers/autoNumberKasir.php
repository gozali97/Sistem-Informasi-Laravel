<?php

// use Illuminate\Support\Facades\Auth;
 namespace App\Helpers;
 use Auth;

 use App\Models\Nomor;

 class autoNumberKasir  {
     
    public static function autoNumberkasir($kode, $len, $simpan){
        date_default_timezone_set("Asia/Bangkok");

        $_this = new self;
        $nomor = $_this->ambilNomorAkhir($kode, $simpan);
        $nomor = $kode.sprintf('%0'.$len.'s', $nomor);

        return $nomor;
    }

    public function simpanNomor($kode, $nomor)
    {
        $nomer    = new Nomor;

        $nomer->nomorkode     = $kode;
        $nomer->nomorakhir    = $nomor;
        $nomer->idclient      = '';

        $nomer->save();

    }

    public function updateNomor($kode, $nomor)
    {
        Nomor::where('nomorkode',$kode)
        ->update(['nomorakhir'=>$nomor]);
    }

    public function ambilNomorAkhir($kode, $simpan)
    {
        $nomors  = Nomor::where('nomorkode',$kode)->first();
        $nomorAkhir =0;
        // dd($nomors);
        if(count((array)$nomors)>0){
            $nomorAkhir = $nomors->nomorakhir+1;
            
            if($simpan){
                $this->updateNomor($kode, $nomorAkhir);
            }
        }else {
                $nomorAkhir = 1;

                if($simpan){
                    $this->simpanNomor($kode, $nomorAkhir);
                }
        }

        return $nomorAkhir;
    }
 }
 