<?php

 namespace App\Helpers;
use DB;
 class tagihanJalan{

    public static function listTagihanJalan($labnomor){
        $listperiksa = DB::table('transaksi_details AS A')
                        // ->leftJoin('ttariflab AS B','B.tarifkode','=','A.labkodedetil')
                        ->select('A.*')
                        ->where('lab_nomor','=',$labnomor)
                        ->get();
        return $listperiksa;

    }

    public static function uangMuka($key){
        // $uangmuka   = 'SELECT COALESCE(SUM(KasirBiaya),0) AS KasirBiaya From tkasirjalan Where  kasirjenisbayar="T"  and kasirnoreg="'.$key.'"';
        // $uangmuka =DB::select('SELECT COALESCE(SUM(KasirBiaya),0) AS KasirBiaya From tkasirjalan Where kasirnoreg="'.$key.'"');
        $uangmuka   = DB::table('kasir_jalans')
                        ->select(DB::Raw('COALESCE(sum(kasir_biaya),0) AS kasirbiaya'))
                        ->where('kasir_jenis_bayar','T')
                        ->where('kasir_no_reg','=',$key)->first()
                        ;


        return $uangmuka;

    }

    public function barcode($key){
        $listbarcode = DB::table('transaksi_details AS D')
                        ->leftJoin('tarif_labs AS T','T.tarif_kode','=','D.lab_kode_detail')
                        ->leftJoin('tbarcodepemeriksaan AS B','B.kode','=','T.tarif_kelompok')
                        ->select('D.*','T.*','B.*')
                        ->WHERE('D.lab_nomor','=',$key)
                        ->get();
        return $listbarcode;
    }

    public function pasien($key){
        $pasien = DB::table('transaksis')
                ->select('*')
                ->where('lab_nomor',$key)
                ->get();
            return $pasien;
    }
 }
