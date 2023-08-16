<?php

namespace App\Http\Controllers\Kasir;

use App\Helpers\autoNumberTrans;
use App\Http\Controllers\Controller;
use App\Models\Admvar;
use App\Models\Card;
use App\Models\Dokter;
use App\Models\KasirJalan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\RawatJalan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class KasirLabController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read kasir/pembayaran');
    }

    public function index(Request $request)
    {
        $data = Transaksi::query()
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'transaksis.pasien_nomor_rm')
            ->leftJoin('kasir_jalans', 'kasir_jalans.kasir_no_trans', 'transaksis.lab_nomor')
            ->select('transaksis.*', 'kasir_jalans.kasir_nomor', 'kasir_jalans.trans_tanggal', 'pasiens.pasien_nama')
            ->get();

        return view('kasir.pembayaran.index', compact('data'));
    }

    public function createNewTransNumber()
    {
        $now = new \DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastTransaksi = KasirJalan::orderBy('kasir_nomor', 'desc')->first();

        if ($lastTransaksi == null) {
            $newNumber = '0001';
        } else {
            $lastLabNumber = $lastTransaksi->kasir_nomor;
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

        return 'KWJ-' . $year . $month . $day . '-' . $newNumber;
    }

    public function pay($id)
    {
        $autoNumbers = $this->createNewTransNumber();

        $data = Transaksi::query()
            ->join('rawat_jalans', 'rawat_jalans.jalan_no_reg', 'transaksis.lab_no_reg')
            ->join('pasiens', 'pasiens.pasien_nomor_rm', 'transaksis.pasien_nomor_rm')
            ->join('perusahaans', 'perusahaans.prsh_kode', 'transaksis.prsh_kode')
            ->where('transaksis.transaksi_id', $id)
            ->select('rawat_jalans.*', 'pasiens.*', 'transaksis.lab_jumlah', 'transaksis.lab_nomor', 'transaksis.lab_asuransi', 'perusahaans.prsh_kode', 'perusahaans.prsh_nama')
            ->first();

        $detail = TransaksiDetail::query()
            ->where('transaksi_details.lab_nomor', $data->lab_nomor)
            ->get();

        return view('kasir.pembayaran.pay', compact('data', 'autoNumbers', 'detail'));
    }

    public function storePay(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'totbayar' => 'required',
            ],
                [
                    'totbayar.required' => 'Jumlah pembayaran harus diisi.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $labno = $request->notrans;

            $nowDate = date('Y-m-d H:i:s');
            $pasien = Pasien::where('pasien_nomor_rm', $request->pasiennorm)->first();
            $kota = Kota::where('city_id', $pasien->pasien_kota)->first();


            $data = new KasirJalan;
            $data->kasir_nomor = $labno;
            $data->user_mobile_id = $pasien->user_mobile_id;
            $data->kasir_no_reg = $request->nomorreg;
            $data->kasir_no_trans = $request->labnomor;
            $data->kasir_tanggal = $nowDate;
            $data->trans_tanggal = $nowDate;
            $data->pasien_nomor_rm = $request->pasiennorm;
            $data->pasien_nama = $request->nama;
            $data->pasien_alamat = $request->alamat;
            $data->pasien_kota = $kota->city_name;
            $data->unit_kode = 'LJ';
            $data->kasir_biaya = $request->jml_total;
            $data->kasir_potongan = $request->potonganVal;
            $data->kasir_asuransi = $request->asuransi;
            $data->kasir_jumlah = $request->totbayar;
            $data->kasir_tunai = $request->tunai;
            $data->kasir_kartu = $request->kodekartu;
            $data->kasir_pribadi = $request->piutangpribadi;
            $data->kasir_keterangan = 'Laboraturium a/n ' . $request->nama;
            $data->prsh_kode = $request->prshkode;
            $data->kasir_pot_kode = $request->potongan;
            $data->kasir_pot_ket = $request->potonganVal;
            $data->kasir_kartu_kode = $request->kodekartu;
            $data->kasir_kartu_nama = $request->namakartu;
            $data->kasir_kartu_nomor = $request->cardnomor;
            $data->kasir_atas_nama = $request->cardatasnama;
            $data->kasir_status = '1';
            $data->user_id = Auth::User()->username;
            $data->user_date = $nowDate;
            $data->id_client = Auth::User()->idlab;

            if ($data->save()) {

                $rawatjalan = RawatJalan::where('jalan_no_reg', '=', $request->nomorreg)->first();

                $rawatjalan->jalan_status = 'P';
                $rawatjalan->jalan_potongan = $request->potonganVal;
                $rawatjalan->jalan_pribadi = $request->totbayar;
                $rawatjalan->user_id_2 = Auth::User()->username;
                $rawatjalan->user_date_2 = $nowDate;

                if ($rawatjalan->save()) {

                    $transaksi = Transaksi::query()->where('lab_nomor', $request->labnomor)->first();
                    $transaksi->lab_byr_jenis = 1;
                    $transaksi->lab_byr_ket = 'Lunas';
                    $transaksi->lab_byr_tgl = $nowDate;
                    $transaksi->lab_byr_nomor = $labno;

                    if ($transaksi->save()) {
                        DB::commit();
                        Session::flash('toast_success', 'Transaksi berhasil diproses');
                        return redirect()->route('pembayaran.index');
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating daftarlab: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function print($id)
    {

        $data = Transaksi::query()
            ->where('transaksis.lab_nomor', $id)
            ->first();


        $listbarcode = TransaksiDetail::query()
            ->leftJoin('tarif_labs', 'tarif_labs.tarif_kode', 'transaksi_details.lab_kode_detail')
            ->leftJoin('barcode_pemeriksaan', 'barcode_pemeriksaan.kode', 'tarif_labs.tarif_kelompok')
            ->select('transaksi_details.*', 'tarif_labs.*', 'barcode_pemeriksaan.*')
            ->WHERE('transaksi_details.lab_nomor', $id)
            ->get();

        $pdf = Pdf::loadView('kasir.pembayaran.print', compact('data', 'listbarcode'));

        return Response::make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="laporan-data-aset.pdf"'
        ]);
    }

    public function getDiskon()
    {
        $data = Admvar::where('var_seri', '=', 'JENISDISC')->get();;

        return response()->json($data);
    }

    public function getKartu()
    {
        $data = Card::all();

        return response()->json($data);
    }
}
