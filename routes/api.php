<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/informasi', [TampilInformasi::class, 'index'])->name('api.info');
Route::post('/banner', [TampilInformasi::class, 'banner']);

//user
Route::post('/admin/login', [UserController::class, 'adminLogin'])->name('api.admin.login');
Route::post('/admin/register', [UserController::class, 'adminRegister'])->name('api.admin.register');


//user Mobile
Route::post('/login', [UserMobileController::class, 'login'])->name('api.user.login');
Route::post('/register', [UserMobileController::class, 'register'])->name('api.user.register');
Route::post('/user/profile', [UserMobileController::class, 'show'])->name('api.user.show');

//verifikasi email
Route::post('/email/sendCode', [EmailVerificationController::class, 'send'])->name('api.kirimKode');
Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend/{id}', [EmailVerificationController::class, 'resend'])->name('verification.resend');

//change password
Route::post('/forgot-password', [UserMobileController::class, 'forgotPassword'])->name('api.forgot_password');
Route::post('/change-password', [UserMobileController::class, 'changePassword'])->name('api.change-password');


//Pasien
Route::post('/pasien', [PasienController::class, 'index'])->name('api.pasien.index');
Route::post('/pasien/simpan', [PasienController::class, 'store'])->name('api.pasien.store');
Route::post('/pasien/show', [PasienController::class, 'show'])->name('api.pasien.show');
Route::post('/pasien/verifKtp', [PasienController::class, 'verifByKtp'])->name('api.pasien.verifKtp');
Route::post('/getkerja', [PasienController::class, 'getKerja'])->name('api.getKerja');
Route::post('/getprov', [PasienController::class, 'getProv'])->name('api.getProv');
Route::post('/getkota', [PasienController::class, 'getKota'])->name('api.getKota');
Route::post('/getkecamatan', [PasienController::class, 'getKecamatan'])->name('api.getKecamatan');
Route::post('/getkelurahan', [PasienController::class, 'getKelurahan'])->name('api.getKelurahan');
Route::post('/getpendidikan', [PasienController::class, 'getPendidikan'])->name('api.getPendidikan');
Route::post('/getdarah', [PasienController::class, 'getDarah'])->name('api.getDarah');
Route::post('/getagama', [PasienController::class, 'getAgama'])->name('api.getAgama');
Route::post('/getjenispas', [PasienController::class, 'getJenis'])->name('api.getJenis');
Route::post('/getstatus', [PasienController::class, 'getStatusKw'])->name('api.getStatusKw');
Route::post('/gethub', [PasienController::class, 'getHubKel'])->name('api.getHubKel');
Route::post('/get-title', [PasienController::class, 'getTitle'])->name('api.getTitle');
Route::post('/pasien/list', [PasienController::class, 'view'])->name('api.pasien.list');


//Tentang kami
Route::post('/tentangKami', [TentangKamiController::class, 'index'])->name('api.tentangkami.index');


//layanan
Route::post('/tampilMasterLayanan', [LayananController::class, 'index'])->name('api.tampilMasterLayanan');
Route::post('/tampilLayananTes', [LayananController::class, 'search'])->name('api.tampilLayananTes');
Route::post('/tampilLayananTesDetail', [LayananController::class, 'detail'])->name('api.tampilLayananTesDetai');

//Paket Group layanan
Route::post('/tampilGroupPaket', [GroupPaketController::class, 'index'])->name('api.tampilGroupPaket');
Route::post('/tampilLayananPaket', [GroupPaketController::class, 'itemLayanan'])->name('api.tampilLayananPaket');
Route::post('/tampilGroupDetail', [GroupPaketController::class, 'detail'])->name('api.tampilGroupDetail');
Route::post('/searchByFilter', [GroupPaketController::class, 'filter'])->name('api.searchByFilter');

//Rujukan
Route::get('/rujukan', [RujukanController::class, 'index'])->name('api.rujukan');
Route::post('/inputRujukan', [RujukanController::class, 'store'])->name('api.inputRujukan');
Route::post('/updateRujukan', [RujukanController::class, 'update'])->name('api.updateRujukan');
Route::post('/tampilRujukan', [RujukanController::class, 'show'])->name('api.rujukan.show');

//pengirim
// Route::post('/tampilMasterDataPengirim', [PengirimController::class, 'show'])->name('api.masterDataPengirim');

//DOkter
Route::post('/tampilDokter', [DokterController::class, 'index'])->name('api.tampilDokter');

//pemeriksaan
Route::post('/pilihPemeriksaan', [PemeriksaanController::class, 'index'])->name('api.pemeriksaan');
Route::post('/pilihPerusahaan', [PemeriksaanController::class, 'pilihPrsh'])->name('api.tampilPerusahaan');
Route::post('/pilihRs', [PemeriksaanController::class, 'pilihRs'])->name('api.tampilRs');
Route::post('/pilihDokter', [PemeriksaanController::class, 'pilihDokter'])->name('api.tampilDokter');
Route::post('/pilihLembaga', [PemeriksaanController::class, 'pilihLembaga'])->name('api.tampilLembaga');
Route::post('/tampilHasilPemeriksaan', [PemeriksaanController::class, 'hasil'])->name('api.hasilPemeriksaan');
Route::post('/tampilItemPromo', [PemeriksaanController::class, 'promo'])->name('api.promo');

//RawatJalan
Route::post('/simpanTransaksiRawatJalan', [RawatJalanCotroller::class, 'store'])->name('api.simpanTransaksiRawatJalan');
Route::post('/tampilTransaksiRawatJalan', [RawatJalanCotroller::class, 'show'])->name('api.tampilRawatJalan');

//Penjamin
Route::post('/tampilMasterDataPenjamin', [PenjaminController::class, 'index'])->name('api.tampilMasterDataPenjamin');

//Transaksi

Route::post('/simpanTransaksi', [TransaksiController::class, 'store'])->name('api.simpanTransaksi');
Route::post('/viewTransaksi', [TransaksiController::class, 'viewTransaksi'])->name('api.viewTransaksi');
Route::post('/tampilPesanKembali', [TransaksiController::class, 'pesanKembali'])->name('api.tampilPesanKembali');
Route::post('/simpanPembayaran', [TransaksiController::class, 'bayar'])->name('api.simpanPembayaran');
Route::post('/statusPembayaran', [TransaksiController::class, 'status'])->name('api.statusPembayaran');
Route::post('/notifPembayaran', [TransaksiController::class, 'notifikasi'])->name('api.notifPembayaran');
Route::post('/historyTransaksi', [TransaksiController::class, 'history'])->name('api.historyTransaksi');
Route::get('/banking/v3/corporates/{CorporateID}/accounts/{AccountNumber1}');

//Alamat
Route::post('/alamatTersimpan', [AlamatController::class, 'index'])->name('api.alamatTersimpan');
Route::post('/simpanAlamat', [AlamatController::class, 'store'])->name('api.simpanAlamat');

//Syarat dan Ketentuan
Route::post('/tampilSnK/daftar', [SyaratKetentuanController::class, 'index'])->name('api.daftarSnK');
Route::post('/tampilSnK/bayar', [SyaratKetentuanController::class, 'bayar'])->name('api.bayarSnK');
Route::post('/tampilSnK/profile', [SyaratKetentuanController::class, 'profil'])->name('api.profilSnK');

//Location
Route::post('/tampilLokasiHilab', [LocationController::class, 'nearest'])->name('api.tampilLokasiHilab');

//BCA
Route::post('openapi/v1.0/access-token/b2b', [AccessTokenController::class, 'generate']);
Route::get('/getBcaTransaction', [BankController::class, 'getBcaTransaction']);
Route::get('/bca/va-status/{corpID}/{account}/{date}', [BcaController::class, 'getVirtualAccountStatus']);
