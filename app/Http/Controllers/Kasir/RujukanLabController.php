<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Kota;
use App\Models\PaketLab;
use App\Models\Pasien;
use App\Models\RawatJalan;
use App\Models\Rujukan;
use App\Models\RujukanDetail;
use App\Models\TarifLab;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\UserMobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RujukanLabController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:read kasir/rujukan');
    }

    public function index(Request $request)
    {
        $data = Rujukan::all();

        $rujukanId = $data->pluck('rujukan_id');

        $detail = RujukanDetail::query()
            ->join('rujukans', 'rujukans.rujukan_id', 'rujukan_details.rujukan_id')
            ->whereIn('rujukan_details.rujukan_id', $rujukanId)
            ->get();

        $data->each(function ($item) use ($detail) {
            $item->detail = $detail->where('rujukan_id', $item->rujukan_id);
        });

        return view('kasir.rujukan.index', compact('data'));
    }


    public function createNewRujNumber()
    {
        $now = new \DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = Rujukan::orderBy('rujukan_id', 'desc')->first();

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->rujukan_no;
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $newNumber = '0001';
            } else {
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        }
        return 'RJ-' . $year . $month . $day . '-' . $newNumber;
    }

    public function create()
    {

        $user_mobile = UserMobile::all();

        $autoNumbers = $this->createNewRujNumber();


        return view('kasir.rujukan.create', compact('autoNumbers', 'user_mobile'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'rumah_sakit' => 'required',
                'nama_dokter' => 'required',
                'pasien_rm' => 'required',
                'kodeTarif' => 'required',
                'file_rujukan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
                [
                    'nama_dokter.required' => 'Kolom nama dokter harus diisi.',
                    'rumah_sakit.required' => 'Kolom rumah sakit harus diisi.',
                    'pasien_rm.required' => 'Data pasien kosong.',
                    'kodeTarif.required' => 'Data layanan kosong.',
                    'file_rujukan.required' => 'Kolom upload file rujukan harus diisi.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $imageName = '/rujukan/' . $request->pasien_rm . '-' . time() . 'rujukan' . '.' . $request->file_rujukan->extension();
            $path = $request->file('file_rujukan')->move('rujukan/', $imageName);

            $pasien = Pasien::where('pasien_nomor_rm', $request->pasiennorm)->first();


            $data = new Rujukan;
            $data->pasien_nomor_rm = $request->pasien_rm;
            $data->user_mobile_id = $pasien->user_mobile_id;
            $data->rujukan_no = $this->createNewRujNumber();
            $data->nama_pasien = $request->nama;
            $data->file_rujukan = $imageName;
            $data->nama_dokter = $request->nama_dokter;
            $data->rumah_sakit = $request->rumah_sakit;
            $data->tanggal_transaksi = date('Y-m-d H:i:s');
            $data->status = 1;
            $data->create_date = date('Y-m-d H:i:s');
            $data->create_by = Auth::User()->username;

            if ($data->save()) {

                $labDetails = [];

                foreach ($request->kodeTarif as $list => $labDetail) {

                    $detail = [
                        'rujukan_id' => $data->rujukan_id,
                        'item_id' => $labDetail,
                        'lab_nama' => $request->namaTarif[$list],
                        'lab_banyak' => 1,
                        'lab_tarif' => $request->tarifPeriksa[$list],
                        'status' => "A",
                    ];

                    $labDetails[] = $detail;

                }

                RujukanDetail::insert($labDetails);

                DB::commit();

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('rujukan.index');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating rujukan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function edit($id)
    {
        $data = Rujukan::query()
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'rujukans.pasien_nomor_rm')
            ->where('rujukan_id', $id)
            ->first();

        return view('kasir.rujukan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'kodeTarif' => 'required',
            ],
                [
                    'kodeTarif.required' => 'Data layanan kosong.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $data = Rujukan::where('rujukan_id', $id)->first();

            $labDetails = [];

            foreach ($request->kodeTarif as $list => $labDetail) {

                $detail = [
                    'rujukan_id' => $data->rujukan_id,
                    'item_id' => $labDetail,
                    'lab_nama' => $request->namaTarif[$list],
                    'lab_banyak' => 1,
                    'lab_tarif' => $request->tarifPeriksa[$list],
                    'status' => "A",
                ];

                $labDetails[] = $detail;

            }

            RujukanDetail::insert($labDetails);

            $data->total_harga = $request->totalLab;

            if ($data->save()) {
                DB::commit();

                Session::flash('toast_success', 'Data berhasil diubah');
                return redirect()->route('rujukan.index');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating rujukan: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $data = Rujukan::query()->where('rujukan_id', $id)->first();

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        $details = RujukanDetail::where('rujukan_id', $id)->get();

        foreach ($details as $detail) {
            $detail->delete();
        }

        if ($data->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
    }

    public function getPasien()
    {
        $data = Pasien::all();

        return response()->json($data);
    }

    public function getPemeriksaan()
    {
        $data = TarifLab::query()
            ->where('tarif_status', 'A')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($data);
    }

    public function getPaket()
    {
        $data = PaketLab::query()
            ->where('paket_status', 'A')
            ->orderBy('paket_kode', 'desc')
            ->get();

        return response()->json($data);

    }
}
