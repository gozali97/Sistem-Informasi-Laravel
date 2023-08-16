<?php
namespace App\Helpers;

use DB;
use Auth;

class accessList
{
    public static function checkMenuAccess($menukode, $userID)
    {
        $userAC     ='';
        $accessItem =array();

        $obj_userAC     = DB::table('users')
                            ->select('tacces_code')
                            ->where('id','=',$userID)
                            ->first();
        $userAC         = $obj_userAC->tacces_code;

        $obj_accessItem = DB::table('taccessitem')
                            ->where('accesscode','=',$userAC)
                            ->where('accessmenu','=',$menukode)
                            ->count();
        
        return $obj_accessItem;
    }

    public static function checkUserAccess($menukode, $menuitemkode, $userID)
    {
        $userAC     = '';
        $accessItem = array();

        $obj_userAC = DB::table('users')
                        ->select('tacces_code')
                        ->where('id','=',$userID)
                        ->first();

        $userAC     = $obj_userAC->tacces_code;

        $obj_accessItem = DB::table('taccessitem')
                            ->select('accesslist')
                            ->where('accesscode','=',$userAC)
                            ->where('accessmenu','=',$menukode)
                            ->first();
        $accessItem     = explode(';',$obj_accessItem->accesslist);
            // dd($accessItem);
        $bolAccess = in_array($menuitemkode, $accessItem);

        return $bolAccess;
    }

    public static function checkUserAccessMenu($menukode, $menuitemkode, $level)
    {
        $userAC='';
        $accessItem=array();

        $obj_accessItem = DB::table('taccessitem')
                            ->select('accesslist')
                            ->where('accesscode','=',$userAC)
                            ->where('accessmenu','=',$menukode)
                            ->first();
        if(is_null($obj_accessItem)){
            $bolAccess = false;
        }else{
            $accessItem = explode(';',$obj_accessItem->accesslist);
            $bolAccess  = in_array($menukode, $accessItem);
        }

        return $bolAccess;
    }

    public static function checkAccessMenu($menukode, $menuitemkode, $level){
        $userAC     ='';
        $accessItem = array();

        $obj_accessItem = DB::table('taccessitem')
                            ->select('accesslist')
                            ->where('accesscode','=',$userAC)
                            ->where('accessmenu','=', $menukode)
                            ->first();
        if(is_null($obj_accessItem)){
            $bolAccess = false;
        }else{
            $bolAccess = true;
        }
        return $bolAccess;
    }
}
