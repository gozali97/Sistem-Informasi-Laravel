<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AlamatHilab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function nearest(Request $request)
    {

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        if (!$latitude && !$longitude) {
            $result = AlamatHilab::all();
            return $this->success($result);
        } elseif ($latitude && $longitude) {

            $locations = AlamatHilab::query()
            ->selectRaw("nama_lokasi, alamat, kota, provinsi,
                    ( 6371 * acos( cos( radians(cast(? as double precision)) ) *
                    cos( radians(cast(latitude as double precision)) )
                    * cos( radians(cast(longitude as double precision)) - radians(cast(? as double precision))
                    ) + sin( radians(cast(? as double precision)) ) *
                    sin( radians(cast(latitude as double precision)) ) )
                    ) AS Jarak", [$latitude, $longitude, $latitude])
                ->orderByRaw("Jarak ASC")
                ->first();

            if ($locations->count() > 0) {
                return $this->success($locations);
            } else {
                return $this->error("Lokasi tidak ditemukan");
            }
        } else {
            return $this->error("Error");
        }

    }


    public function success($data, $message = "Berhasil")
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($message)
    {
        return response()->json([
            'status' => 'failed',
            'code' => 400,
            'message' => $message
        ], 400);
    }
}
