<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Helpers\autoNumberTrans;
use App\Http\Controllers\Controller;
use App\Models\Admvar;
use App\Models\Dokter;
use App\Models\Group;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Perusahaan;
use App\Models\Provinsi;
use App\Models\RawatJalan;
use App\Models\TarifVar;
use App\Models\Transaksi;
use App\Models\Unit;
use App\Models\UserMobile;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PendaftaranLabController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read pendaftaran/lab');
    }

    public function index()
    {
        date_default_timezone_set('asia/bangkok');

        $admvars = Admvar::all();
        $prsh = Perusahaan::all();
        $tarifvars = TarifVar::all();
        $tgl = date('y') . date('m') . date('d');
        $provinsi = Provinsi::all();
        $pengirim = Group::where('grup_jenis', '=', 'PRSH')->orderBy('grup_kode', 'asc')->distinct()->get()->unique('grup_kode');
        $dokter = Dokter::where('unit_kode', '32')->get();
        $unit = Unit::where('unit_kode', '32')->get();
        $user_mobile = UserMobile::all();


        $autoNumbers = $this->createNewRegNumber();
        $tempNoRM = '';
        return view('pendaftaran.lab.create', compact(
            'autoNumbers',
            'unit',
            'admvars',
            'prsh',
            'tarifvars',
            'tgl',
            'provinsi',
            'tempNoRM',
            'pengirim',
            'dokter',
            'user_mobile'
        ));
    }

    public function createNewRegNumber()
    {
        $now = new DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = RawatJalan::orderBy('jalan_no_reg', 'desc')->first();

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->jalan_no_reg;
            // dd($lastLabNumber);
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $lastNumber = 0;
            }
            // $lastNumber += $i;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }
        return 'RP-' . $year . $month . $day . '-' . $newNumber;
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $validator = Validator::make(
                $request->all(),
                [
                    'pengirim' => 'required',
                    'namapengirim' => 'required',
                    'dokter' => 'required',
                ],
                [
                    'pengirim.required' => 'Kolom Pengirim harus diisi.',
                    'namapengirim.required' => 'Kolom Nama Instansi Panggilan harus diisi.',
                    'dokter.required' => 'Kolom Dokter harus diisi.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $data = new RawatJalan;

            $pasien = Pasien::where('pasien_nomor_rm', '=', $request->pasien_rm)->first();

            $kota = Kota::where('city_id', $pasien->pasien_kota)->first();

            $nowDate = date('Y-m-d H:i:s');

            $pasbaru = 'B';
            $exists = RawatJalan::where('pasien_nomor_rm', $request->pasien_rm)->exists();

            if ($exists) {
                $pasbaru = 'L';
            }


            $data->jalan_no_reg = $request->notrans;
            $data->user_mobile_id = $pasien->user_mobile_id;
            $data->pasien_nama = $pasien->pasien_nama;
            $data->pasien_gender = $pasien->pasien_gender;
            $data->pasien_alamat = $pasien->pasien_alamat;
            $data->pasien_kota = $kota->city_name;
            $data->pasien_nomor_rm = $request->pasiennorm;
            $data->pasien_umur_thn = $request->pasienumurthn;
            $data->pasien_umur_bln = $request->pasienumurbln;
            $data->pasien_umur_hr = $request->pasienumurhari;
            $data->jalan_tanggal = date_format(new DateTime($nowDate), 'Y-m-d H:i:s');
            $data->unit_kode = $request->unit;
            $data->dokter_kode = $request->dokter;
            $data->jalan_no_urut = substr($request->notrans, -2);
            $data->jalan_asal_pasien = '7';
            $data->jalan_pas_baru = $pasbaru;
            $data->jalan_cara_daftar = '1';
            $data->jalan_ket_sumber = '';
            $data->jalan_diag_poli = '';
            $data->jalan_kasus_poli = '';
            $data->jalan_status = 'D';
            $data->jalan_jenis_bayar = 'S';
            $data->jalan_daftar = (int)str_replace(',', '', 0);
            $data->jalan_kartu = 0.00;
            $data->jalan_periksa = 0.00;
            $data->jalan_jumlah = 0.00;
            $data->jalan_potongan = 0.00;
            $data->jalan_byr_jenis = '0';
            $data->prsh_kode = isset($request->penjamin) ? $request->penjamin : 'Pribadi';
            $data->user_id_1 = (int)Auth::User()->username;
            $data->user_id_2 = (int)Auth::User()->username;
            $data->user_date_1 = date('Y-m-d H:i:s');
            $data->user_date_2 = date('Y-m-d H:i:s');
            $data->pngrm_kode = $request->pengirim;
            $data->id_client = Auth::User()->idlab;


            if ($data->save()) {
                Session::flash('toast_success', 'Data berhasil ditambah');
                return redirect()->route('transaksi.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating daftarlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPasien()
    {

        $data = DB::table('pasiens')
            ->join('admvars as gender', function ($join) {
                $join->on('gender.var_kode', '=', 'pasiens.pasien_gender')
                    ->where('gender.var_seri', '=', 'GENDER');
            })
            ->join('admvars as pndk', function ($join) {
                $join->on('pndk.var_kode', '=', 'pasiens.pasien_pdk')
                    ->where('pndk.var_seri', '=', 'PENDIDIKAN');
            })
            ->join('admvars as jenis', function ($join) {
                $join->on('jenis.var_kode', '=', 'pasiens.pasien_jenis')
                    ->where('jenis.var_seri', '=', 'JENISPAS');
            })
            ->join('admvars as agama', function ($join) {
                $join->on('agama.var_kode', '=', 'pasiens.pasien_agama')
                    ->where('agama.var_seri', '=', 'AGAMA');
            })
            ->join('provinsi', 'provinsi.prov_id', 'pasiens.pasien_prov')
            ->join('kota', 'kota.city_id', 'pasiens.pasien_kota')
            ->join('user_mobiles', 'user_mobiles.id', 'pasiens.user_mobile_id')
            ->join('kecamatan', 'kecamatan.dis_id', 'pasiens.pasien_kecamatan')
            ->join('kelurahan', 'kelurahan.subdis_id', 'pasiens.pasien_kelurahan')
            ->select('pasiens.*', 'gender.var_nama as gender', 'pndk.var_nama as pndk', 'jenis.var_nama as jenis', 'agama.var_nama as agama', 'provinsi.prov_name', 'kota.city_name', 'kecamatan.dis_name', 'kelurahan.subdis_name', 'user_mobiles.nama_lengkap')
            ->orderBy('pasien_nomor_rm', 'ASC')
            ->get();
        return response()->json($data);
    }

    public function getPengirim(Request $request)
    {

        $kdpengirim = $request->kdpengirim;

        $data = Perusahaan::query()
            ->where(DB::raw('substring(prsh_kode,1,1)'), '=', $kdpengirim)
            ->where('prsh_status', 'A')
            ->orWhere('prsh_kode', $kdpengirim)
            ->orderBy('prsh_nama', 'asc')
            ->get();

        return response()->json($data);
    }

    public function getPenjamin(Request $request)
    {

        $kdpengirim = $request->kdpengirim;

        $data = Perusahaan::query()
            ->where(DB::raw('substring(prsh_kode,1,1)'), '=', $kdpengirim)
            ->where('prsh_status', 'A')
            ->orWhere('prsh_kode', '0-0000')
            ->orderBy('prsh_kode', 'asc')
            ->get();

        return response()->json($data);
    }
}
