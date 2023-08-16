<?php
 namespace App\Helpers;

 use App\Models\Nomor;

 class autoNumberTransUnit  {
     
    public static function autoNumber($kode, $len, $simpan){
        date_default_timezone_set("Asia/Bangkok");

        $_this = new self;
        $tgl    = date('y').date('m').date('d');

        $kodetgl= $kode.''.$tgl.'-';
        
        $nomor = $_this->ambilNomorAkhir($kodetgl, $simpan);
        $nomor = $kode.' '.sprintf('%0'.$len.'s', $nomor);

        return $nomor;
    }

    public function simpanNomor($kode, $nomor)
    {
        $nmr    = new Nomor;

        $nmr->nomorkode     = $kode;
        $nmr->nomorakhir    = $nomor;
        $nmr->idclient      ='';

        $nmr->save();

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
 