<?php
namespace App\Helpers;

use DB;
use App\Cpanel;
class initGlobal{
    public static function initGlobal(){
        $ob_lab = DB::table('tcpanel')->select('id')->first();
        // $lab=array();
        $lab    = $ob_lab->id;
        session()->put('id',$lab);
    }

    public static function getTCPanel(){
        $tcpanel    = DB::table('tcpanel')->first();
        return $tcpanel;
    }
}