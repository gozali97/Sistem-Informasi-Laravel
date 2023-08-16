<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\Admvar;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Kota;
use App\Models\Pasien;
use App\Models\Provinsi;
use App\Models\UserMobile;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PendaftaranPasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read pendaftaran/pasien');
    }

    public function index()
    {
        $data = Pasien::query()
            ->join('admvars', 'admvars.var_kode', '=', 'pasiens.pasien_jenis')
            ->where('admvars.var_seri', 'JENISPAS')
            ->select('pasiens.*', 'admvars.var_nama')
            ->get();

        return view('pendaftaran.pasien.index', compact('data'));
        // return view('errors.505');
    }


    public function create()
    {
        $Gender = Admvar::where('var_seri', '=', 'GENDER')->get();
        $JenisPasien = Admvar::where('var_seri', '=', 'JENISPAS')->get();
        $StatusKwn = Admvar::where('var_seri', '=', 'KAWIN')->get();
        $Religion = Admvar::where('var_seri', '=', 'AGAMA')
            ->orderBy('var_kode')->get();
        $GolDarah = Admvar::where('var_seri', '=', 'DARAH')->get();
        $Pendidikan = Admvar::where('var_seri', '=', 'PENDIDIKAN')->get();
        $Pekerjaan = Admvar::where('var_seri', '=', 'PEKERJAAN')->get();
        $Family = Admvar::where('var_seri', '=', 'KELUARGA')->get();
        $provinsi = Provinsi::all();
        $kota = Kota::all();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $autoNumber = $this->createNewRegNumber();
        $Title = Admvar::where('var_seri', 'TITLE')->get();
        $user_mobile = UserMobile::all();

        return view(
            'pendaftaran.pasien.create',
            compact(
                'Gender',
                'provinsi',
                'JenisPasien',
                'StatusKwn',
                'Religion',
                'GolDarah',
                'Pendidikan',
                'Pekerjaan',
                'Family',
                'autoNumber',
                'Title',
                'kota',
                'kecamatan',
                'kelurahan',
                'user_mobile'
            )
        );
    }

    public function createNewRegNumber()
    {
        $now = new DateTime();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $lastPasien = Pasien::orderBy('pasien_nomor_rm', 'desc')->first();

        if ($lastPasien == null) {
            $newNumber = '001';
        } else {
            $lastLabNumber = $lastPasien->pasien_nomor_rm;
            $lastYear = substr($lastLabNumber, 3, 2);
            $lastMonth = substr($lastLabNumber, 5, 2);
            $lastDay = substr($lastLabNumber, 7, 2);
            $lastNumber = (int)substr($lastLabNumber, -4);

            if ($lastYear != $year || $lastMonth != $month || $lastDay != $day) {
                $newNumber = '001';
            } else {
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            }
        }
        return 'H' . $year . $month . $day . $newNumber;
    }


    public function store(Request $request)
    {
        try {

            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'panggilan' => 'required',
                'gender' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'alamat' => 'required',
                'provinsi' => 'required',
                'kota' => 'required',
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'telepon' => 'required|numeric',
                'HP' => 'required|numeric',
                'agama' => 'required',
                'darah' => 'required',
                'pekerjaan' => 'required',
                'subkerja' => 'required',
                'alamatkerja' => 'required',
                'namakeluarga' => 'required',
                'alamatkeluarga' => 'required',
                'hubungankel' => 'required',
                'telponkel' => 'required',
                'kontakdarurat' => 'required',
                'catatankhusus' => 'required',
                'tmplahir' => 'required',
                'tgllahir' => 'required|date',
                'darah' => 'required',
                'pendidikan' => 'required',
                'kawin' => 'required',
                'ktp' => 'required|numeric|min:16',
                'jenispasien' => 'required',
                'title' => 'required',
                'user_mobile_id' => 'required',
                'email' => 'required',
            ], [
                'nama.required' => 'Kolom Nama harus diisi.',
                'panggilan.required' => 'Kolom Nama Panggilan harus diisi.',
                'gender.required' => 'Kolom Jenis Kelamin harus diisi.',
                'rt.required' => 'Kolom RT harus diisi.',
                'rw.required' => 'Kolom RW harus diisi.',
                'alamat.required' => 'Kolom Alamat harus diisi.',
                'provinsi.required' => 'Kolom Provinsi harus diisi.',
                'kota.required' => 'Kolom Kota harus diisi.',
                'kecamatan.required' => 'Kolom Kecamatan harus diisi.',
                'kelurahan.required' => 'Kolom Kelurahan harus diisi.',
                'telepon.required' => 'Kolom No Telepon harus diisi.',
                'HP.required' => 'Kolom No HP harus diisi.',
                'agama.required' => 'Kolom Agama harus diisi.',
                'darah.required' => 'Kolom Golongan Darah harus diisi.',
                'pekerjaan.required' => 'Kolom Pekerjaan harus diisi.',
                'subkerja.required' => 'Kolom Nama Pekerjaan harus diisi.',
                'alamatkerja.required' => 'Kolom Alamat Pekerjaan harus diisi.',
                'namakeluarga.required' => 'Kolom Nama Keluarga harus diisi.',
                'alamatkeluarga.required' => 'Kolom ALamat Keluarga harus diisi.',
                'hubungankel.required' => 'Kolom Hubungan harus diisi.',
                'telponkel.required' => 'Kolom No Telp Keluarga harus diisi.',
                'kontakdarurat.required' => 'Kolom Kontak Darurat harus diisi.',
                'catatankhusus.required' => 'Kolom Catatan harus diisi.',
                'tmplahir.required' => 'Kolom Tempat Lahir harus diisi.',
                'tgllahir.required' => 'Kolom Tanggal Lahir harus diisi.',
                'darah.required' => 'Kolom Golongan darah harus diisi.',
                'pendidikan.required' => 'Kolom Pendidikan harus diisi.',
                'kawin.required' => 'Kolom Status Kawin harus diisi.',
                'ktp.required' => 'Kolom No KTP harus diisi.',
                'jenispasien.required' => 'Kolom Jenis Pasien harus diisi.',
                'title.required' => 'Kolom Title harus diisi.',
                'user_mobile_id.required' => 'Kolom Akun Mobile harus diisi.',
                'email.required' => 'Kolom email harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = new Pasien;

            $data->pasien_nomor_rm = $request->NomorRM;
            $data->pasien_nama = $request->nama;
            $data->pasien_gender = $request->jk;
            $data->pasien_panggilan = $request->panggilan;
            $data->pasien_prioritas = '';
            $data->pasien_alamat = $request->alamat;
            $data->pasien_kelurahan = $request->kelurahan;
            $data->pasien_kecamatan = $request->kecamatan;
            $data->pasien_kota = $request->kota;
            $data->pasien_prov = $request->provinsi;
            $data->pasien_rt = $request->rt;
            $data->pasien_rw = $request->rw;
            $data->pasien_wilayah = $request->provinsi;
            $data->pasien_telp = $request->telepon;
            $data->pasien_hp = $request->HP;
            $data->pasien_tmp_lahir = $request->tmplahir;
            $data->pasien_tgl_lahir = $request->tgllahir;
            $data->pasien_kerja = $request->subkerja;
            $data->pasien_agama = $request->agama;
            $data->pasien_kerja_kode = $request->pekerjaan;
            $data->pasien_kerja_alamat = $request->alamatkerja;
            $data->pasien_gol_darah = $request->darah;
            $data->pasien_pdk = $request->pendidikan;
            $data->pasien_status_kw = $request->kawin;
            $data->pasien_klg_nama = $request->namakeluarga;
            $data->pasien_klg_kerja = '';
            $data->pasien_klg_pdk = '';
            $data->pasien_klg_hub = $request->hubungankel;
            $data->pasien_klg_tlp = $request->telponkel;
            $data->pasien_email = $request->email;

            $data->pasien_kontak_darurat = $request->kontakdarurat;
            $data->pasien_catatan = $request->catatankhusus;

            $data->pasien_klg_alamat = $request->alamatkeluarga;
            $data->pasien_no_id = $request->ktp;
            $data->member_nomor = '';
            $data->pasien_jenis = $request->jenispasien;
            $data->pasien_tgl_input = date('Y-m-d H:i:s');
            $data->created_at = date('Y-m-d H:i:s');
            $data->user_id = Auth::User()->id;
            $data->user_mobile_id = $request->user_mobile_id;
            $data->pasien_gender = $request->gender;
            $data->pasien_title = $request->title;
            $data->id_client = Auth::User()->idlab;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil ditambahkan');
                return redirect()->route('pendaftaran.pasien.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating pendaftaranpasien: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Pasien::where('pasien_nomor_rm', $id)->first();
        $Gender = Admvar::where('var_seri', '=', 'GENDER')->get();
        $JenisPasien = Admvar::where('var_seri', '=', 'JENISPAS')->get();
        $StatusKwn = Admvar::where('var_seri', '=', 'KAWIN')->get();
        $Religion = Admvar::where('var_seri', '=', 'AGAMA')
            ->orderBy('var_kode')->get();
        $GolDarah = Admvar::where('var_seri', '=', 'DARAH')->get();
        $Pendidikan = Admvar::where('var_seri', '=', 'PENDIDIKAN')->get();
        $Pekerjaan = Admvar::where('var_seri', '=', 'PEKERJAAN')->get();
        $Family = Admvar::where('var_seri', '=', 'KELUARGA')->get();
        $provinsi = Provinsi::all();
        $kota = Kota::all();
        $kecamatan = Kecamatan::all();
        $kelurahan = Kelurahan::all();
        $Title = Admvar::where('var_seri', 'TITLE')->get();
        $user_mobile = UserMobile::all();

        return view(
            'pendaftaran.pasien.edit',
            compact(
                'Gender',
                'provinsi',
                'JenisPasien',
                'StatusKwn',
                'Religion',
                'GolDarah',
                'Pendidikan',
                'Pekerjaan',
                'Family',
                'data',
                'Title',
                'kota',
                'kecamatan',
                'kelurahan',
                'user_mobile'
            )
        );
    }


    public function update(Request $request, $id)
    {
        try {

            $validasi = Validator::make($request->all(), [
                'nama' => 'required',
                'panggilan' => 'required',
                'gender' => 'required',
                'rt' => 'required',
                'rw' => 'required',
                'alamat' => 'required',
                'provinsi' => 'required',
                'kota' => 'required',
                'kecamatan' => 'required',
                'kelurahan' => 'required',
                'telepon' => 'required|numeric',
                'HP' => 'required|numeric',
                'agama' => 'required',
                'darah' => 'required',
                'pekerjaan' => 'required',
                'subkerja' => 'required',
                'alamatkerja' => 'required',
                'namakeluarga' => 'required',
                'alamatkeluarga' => 'required',
                'hubungankel' => 'required',
                'telponkel' => 'required',
                'kontakdarurat' => 'required',
                'catatankhusus' => 'required',
                'tmplahir' => 'required',
                'tgllahir' => 'required|date',
                'darah' => 'required',
                'pendidikan' => 'required',
                'kawin' => 'required',
                'ktp' => 'required|numeric|min:16',
                'jenispasien' => 'required',
                'title' => 'required',
                'user_mobile_id' => 'required',
                'email' => 'required',
            ], [
                'nama.required' => 'Kolom Nama harus diisi.',
                'panggilan.required' => 'Kolom Nama Panggilan harus diisi.',
                'gender.required' => 'Kolom Jenis Kelamin harus diisi.',
                'rt.required' => 'Kolom RT harus diisi.',
                'rw.required' => 'Kolom RW harus diisi.',
                'alamat.required' => 'Kolom Alamat harus diisi.',
                'provinsi.required' => 'Kolom Provinsi harus diisi.',
                'kota.required' => 'Kolom Kota harus diisi.',
                'kecamatan.required' => 'Kolom Kecamatan harus diisi.',
                'kelurahan.required' => 'Kolom Kelurahan harus diisi.',
                'telepon.required' => 'Kolom No Telepon harus diisi.',
                'HP.required' => 'Kolom No HP harus diisi.',
                'agama.required' => 'Kolom Agama harus diisi.',
                'darah.required' => 'Kolom Golongan Darah harus diisi.',
                'pekerjaan.required' => 'Kolom Pekerjaan harus diisi.',
                'subkerja.required' => 'Kolom Nama Pekerjaan harus diisi.',
                'alamatkerja.required' => 'Kolom Alamat Pekerjaan harus diisi.',
                'namakeluarga.required' => 'Kolom Nama Keluarga harus diisi.',
                'alamatkeluarga.required' => 'Kolom ALamat Keluarga harus diisi.',
                'hubungankel.required' => 'Kolom Hubungan harus diisi.',
                'telponkel.required' => 'Kolom No Telp Keluarga harus diisi.',
                'kontakdarurat.required' => 'Kolom Kontak Darurat harus diisi.',
                'catatankhusus.required' => 'Kolom Catatan harus diisi.',
                'tmplahir.required' => 'Kolom Tempat Lahir harus diisi.',
                'tgllahir.required' => 'Kolom Tanggal Lahir harus diisi.',
                'darah.required' => 'Kolom Golongan darah harus diisi.',
                'pendidikan.required' => 'Kolom Pendidikan harus diisi.',
                'kawin.required' => 'Kolom Status Kawin harus diisi.',
                'ktp.required' => 'Kolom No KTP harus diisi.',
                'jenispasien.required' => 'Kolom Jenis Pasien harus diisi.',
                'title.required' => 'Kolom Title harus diisi.',
                'user_mobile_id.required' => 'Kolom Akun Mobile harus diisi.',
                'email.required' => 'Kolom email harus diisi.',
            ]);

            if ($validasi->fails()) {
                return back()->withErrors($validasi)->withInput();
            }

            $data = Pasien::where('pasien_nomor_rm', $id)->first();

            $data->pasien_nomor_rm = $request->NomorRM;
            $data->pasien_nama = $request->nama;
            $data->pasien_gender = $request->jk;
            $data->pasien_panggilan = $request->panggilan;
            $data->pasien_prioritas = '';
            $data->pasien_alamat = $request->alamat;
            $data->pasien_kelurahan = $request->kelurahan;
            $data->pasien_kecamatan = $request->kecamatan;
            $data->pasien_kota = $request->kota;
            $data->pasien_prov = $request->provinsi;
            $data->pasien_rt = $request->rt;
            $data->pasien_rw = $request->rw;
            $data->pasien_wilayah = $request->provinsi;
            $data->pasien_telp = $request->telepon;
            $data->pasien_hp = $request->HP;
            $data->pasien_tmp_lahir = $request->tmplahir;
            $data->pasien_tgl_lahir = $request->tgllahir;
            $data->pasien_kerja = $request->subkerja;
            $data->pasien_agama = $request->agama;
            $data->pasien_kerja_kode = $request->pekerjaan;
            $data->pasien_kerja_alamat = $request->alamatkerja;
            $data->pasien_gol_darah = $request->darah;
            $data->pasien_pdk = $request->pendidikan;
            $data->pasien_status_kw = $request->kawin;
            $data->pasien_klg_nama = $request->namakeluarga;
            $data->pasien_klg_kerja = '';
            $data->pasien_klg_pdk = '';
            $data->pasien_klg_hub = $request->hubungankel;
            $data->pasien_klg_tlp = $request->telponkel;
            $data->pasien_email = $request->email;

            $data->pasien_kontak_darurat = $request->kontakdarurat;
            $data->pasien_catatan = $request->catatankhusus;

            $data->pasien_klg_alamat = $request->alamatkeluarga;
            $data->pasien_no_id = $request->ktp;
            $data->member_nomor = '';
            $data->pasien_jenis = $request->jenispasien;
            $data->pasien_tgl_input = date('Y-m-d H:i:s');
            $data->created_at = date('Y-m-d H:i:s');
            $data->user_id = Auth::User()->id;
            $data->user_mobile_id = $request->user_mobile_id;
            $data->pasien_gender = $request->gender;
            $data->pasien_title = $request->title;
            $data->id_client = Auth::User()->idlab;

            if ($data->save()) {

                Session::flash('toast_success', 'Data berhasil diperbarui');
                return redirect()->route('pendaftaran.pasien.index');
            }
        } catch (\Exception $e) {
            Log::error('Error creating pendaftaranpasien: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $data = Pasien::where('pasien_nomor_rm', $id)->first();

        if (!$data) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        if ($data->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
    }

    public function getKota(Request $request)
    {

        $prov_id = $request->prov_id;

        $data = Kota::query()
            ->where('prov_id', '=', $prov_id)
            ->select('city_name', 'city_id')
            ->orderBy('city_id', 'ASC')
            ->get();

        return response()->json($data);
    }

    public function getKecamatan(Request $request)
    {

        $city_id = $request->city_id;

        $data = Kecamatan::select('dis_id', 'dis_name')
            ->where('city_id', $city_id)
            ->get();

        return response()->json($data);
    }

    public function getKelurahan(Request $request)
    {

        $dis_id = $request->dis_id;

        $data = Kelurahan::select('subdis_id', 'subdis_name')
            ->where('dis_id', $dis_id)
            ->get();

        return response()->json($data);
    }
}
