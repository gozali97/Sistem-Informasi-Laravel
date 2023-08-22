<?php

use App\Http\Controllers\Account\MasterUserController;
use App\Http\Controllers\Account\MasterUserMobileController;
use App\Http\Controllers\Kasir\TransaksiLabController;
use App\Http\Controllers\Kasir\RujukanLabController;
use App\Http\Controllers\Kasir\KasirLabController;
use App\Http\Controllers\Konfigurasi\RoleController;
use App\Http\Controllers\Konfigurasi\NavigationController;
use App\Http\Controllers\Konfigurasi\PermissionController;
use App\Http\Controllers\Laporan\LaporanRegistrasiLabController;
use App\Http\Controllers\Laporan\LaporanKunjunganPasienController;
use App\Http\Controllers\Laporan\LaporanPendapatanLabController;
use App\Http\Controllers\Laboratorium\InputHasilLabController;
use App\Http\Controllers\Master\MasterBannerController;
use App\Http\Controllers\Master\MasterBarcodePemeriksaanController;
use App\Http\Controllers\Master\MasterGroupPaketController;
use App\Http\Controllers\Master\MasterHubTarifPaketController;
use App\Http\Controllers\Master\MasterHubTarifPemeriksaanController;
use App\Http\Controllers\Master\MasterInformasiController;
use App\Http\Controllers\Master\MasterLabReferenceController;
use App\Http\Controllers\Master\MasterLayananController;
use App\Http\Controllers\Master\MasterLayananLabController;
use App\Http\Controllers\Master\MasterLayananPromoController;
use App\Http\Controllers\Master\MasterLayananTestDetailController;
use App\Http\Controllers\Master\MasterLokasiHilabController;
use App\Http\Controllers\Master\MasterPaketLabController;
use App\Http\Controllers\Master\MasterPemeriksaanController;
use App\Http\Controllers\Master\MasterPenjaminController;
use App\Http\Controllers\Master\MasterPerusahaanController;
use App\Http\Controllers\Master\MasterSyaratKetentuanController;
use App\Http\Controllers\Master\MasterTarifLabController;
use App\Http\Controllers\Master\MasterTentangKamiController;
use App\Http\Controllers\Master\MasterTtdDokterController;
use App\Http\Controllers\Master\DokterHilabController;
use App\Http\Controllers\Pendaftaran\AppointmentController;
use App\Http\Controllers\Pendaftaran\InformasiPendaftaranController;
use App\Http\Controllers\Pendaftaran\PendaftaranLabController;
use App\Http\Controllers\Pendaftaran\PendaftaranPasienController;
use App\Http\Controllers\TabelData\TabelTarifLabController;
use App\Http\Controllers\TabelData\TabelPemeriksaanController;
use App\Http\Controllers\TabelData\TabelHubTarifPemeriksaanController;
use App\Http\Controllers\TabelData\TabelPaketLabController;
use App\Http\Controllers\TabelData\TabelHubTarifPaketController;
use App\Http\Controllers\TabelData\TabelBarcodePemeriksaanController;
use App\Http\Controllers\TabelData\TabelLabReferenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('user/profile');
    })->name('profile');


    //konfigurasi
    Route::controller(RoleController::class)->group(function () {
        Route::get('konfigurasi/roles', 'index')->name('roles.index');
        Route::get('konfigurasi/roles/create', 'create')->name('roles.create');
        Route::get('konfigurasi/roles/store', 'store')->name('roles.store');
        Route::get('konfigurasi/roles/view/{id}', 'view')->name('roles.view');
        Route::get('konfigurasi/roles/addPermission', 'addPermission')->name('roles.addPermission');
        Route::get('konfigurasi/roles/update/{id}', 'update')->name('roles.update');
        Route::get('konfigurasi/roles/destroy/{id}', 'destroy')->name('roles.destroy');
        Route::get('konfigurasi/roles/deletePermission/{id}', 'deletePermission')->name('roles.deletePermission');
    });

    Route::controller(NavigationController::class)->group(function () {
        Route::get('konfigurasi/navigasi', 'index')->name('navigasi.index');
        Route::get('konfigurasi/navigasi/create', 'create')->name('navigasi.create');
        Route::get('konfigurasi/navigasi/store', 'store')->name('navigasi.store');
        Route::get('konfigurasi/navigasi/update/{id}', 'update')->name('navigasi.update');
        Route::get('konfigurasi/navigasi/destroy/{id}', 'destroy')->name('navigasi.destroy');
    });

    Route::controller(PermissionController::class)->group(function () {
        Route::get('konfigurasi/permission', 'index')->name('permission.index');
        Route::get('konfigurasi/permission/create', 'create')->name('permission.create');
        Route::get('konfigurasi/permission/store', 'store')->name('permission.store');
        Route::get('konfigurasi/permission/update/{id}', 'update')->name('permission.update');
        Route::get('konfigurasi/permission/destroy/{id}', 'destroy')->name('permission.destroy');
    });

    Route::controller(PendaftaranPasienController::class)->group(function () {
        Route::get('pendaftaran/pasien', 'index')->name('pendaftaran.pasien.index');
        Route::get('pendaftaran/pasien/create', 'create')->name('pendaftaran.pasien.create');
        Route::get('pendaftaran/pasien/add', 'create')->name('pendaftaran.pasien.add');
        Route::post('pendaftaran/pasien/store', 'store')->name('pendaftaran.pasien.store');
        Route::get('pendaftaran/pasien/edit/{id}', 'edit')->name('pendaftaran.pasien.edit');
        Route::post('pendaftaran/pasien/update/{id}', 'update')->name('pendaftaran.pasien.update');
        Route::get('pendaftaran/pasien/destroy/{id}', 'destroy')->name('pendaftaran.pasien.destroy');

        Route::post('/pendaftaran/pasien/getKota', 'getKota')->name('pendaftaran.pasien.getKota');
        Route::post('/pendaftaran/pasien/getKecamatan', 'getKecamatan')->name('pendaftaran.pasien.getKecamatan');
        Route::post('/pendaftaran/pasien/getKelurahan', 'getKelurahan')->name('pendaftaran.pasien.getKelurahan');
    });

    Route::controller(PendaftaranLabController::class)->group(function () {
        Route::get('pendaftaran/lab', 'index')->name('lab.index');
        Route::get('pendaftaran/lab/create', 'create')->name('lab.create');
        Route::post('pendaftaran/lab/store', 'store')->name('lab.store');
        Route::post('pendaftaran/lab/update/{id}', 'update')->name('lab.update');
        Route::get('pendaftaran/lab/destroy/{id}', 'destroy')->name('lab.destroy');

        Route::get('/pendaftaran/lab/getPasien', 'getPasien')->name('lab.getPasien');
        Route::post('/pendaftaran/lab/getPengirim', 'getPengirim')->name('lab.getPengirim');
        Route::post('/pendaftaran/lab/getPenjamin', 'getPenjamin')->name('lab.getPenjamin');
    });

    Route::controller(InformasiPendaftaranController::class)->group(function () {
        Route::get('pendaftaran/informasi', 'index')->name('informasi.index');
        Route::get('pendaftaran/informasi/create', 'create')->name('informasi.create');
        Route::get('pendaftaran/informasi/store', 'store')->name('informasi.store');
        Route::get('pendaftaran/informasi/update/{id}', 'update')->name('informasi.update');
        Route::get('pendaftaran/informasi/destroy/{id}', 'destroy')->name('informasi.destroy');
    });

    Route::controller(AppointmentController::class)->group(function () {
        Route::get('pendaftaran/point', 'index')->name('point.index');
        Route::get('pendaftaran/point/create', 'create')->name('point.create');
        Route::get('pendaftaran/point/store', 'store')->name('point.store');
        Route::get('pendaftaran/point/update/{id}', 'update')->name('point.update');
        Route::get('pendaftaran/point/destroy/{id}', 'destroy')->name('point.destroy');
    });

    //kasir
    Route::controller(TransaksiLabController::class)->group(function () {
        Route::get('/kasir/transaksi', 'index')->name('transaksi.index');
        Route::get('/kasir/transaksi/add', 'create')->name('transaksi.add');
        Route::post('/kasir/transaksi/store', 'store')->name('transaksi.store');
        Route::get('/kasir/transaksi/edit/{id}', 'edit')->name('transaksi.edit');
        Route::post('/kasir/transaksi/update/{id}', 'update')->name('transaksi.update');
        Route::get('/kasir/transaksi/destroy/{id}', 'destroy')->name('transaksi.destroy');

        Route::get('/kasir/transaksi/getRawatJalan', 'getRawatJalan')->name('lab.getRawatJalan');
        Route::get('/kasir/transaksi/getPemeriksaan', 'getPemeriksaan')->name('lab.getPemeriksaan');
        Route::get('/kasir/transaksi/getPaket', 'getPaket')->name('lab.getPaket');
    });

    Route::controller(RujukanLabController::class)->group(function () {
        Route::get('/kasir/rujukan', 'index')->name('rujukan.index');
        Route::get('/kasir/rujukan/add', 'create')->name('rujukan.add');
        Route::post('/kasir/rujukan/store', 'store')->name('rujukan.store');
        Route::get('/kasir/rujukan/edit/{id}', 'edit')->name('rujukan.edit');
        Route::post('/kasir/rujukan/update/{id}', 'update')->name('rujukan.update');
        Route::get('/kasir/rujukan/destroy/{id}', 'destroy')->name('rujukan.destroy');

        Route::get('/kasir/rujukan/getPasien', 'getPasien')->name('rujukan.getPasien');
        Route::get('/kasir/rujukan/getPemeriksaan', 'getPemeriksaan')->name('rujukan.getPemeriksaan');
        Route::get('/kasir/rujukan/getPaket', 'getPaket')->name('rujukan.getPaket');
    });

    Route::controller(KasirLabController::class)->group(function () {
        Route::get('/kasir/pembayaran', 'index')->name('pembayaran.index');
        Route::get('/kasir/pembayaran/add', 'create')->name('pembayaran.add');
        Route::post('/kasir/pembayaran/store', 'store')->name('pembayaran.store');
        Route::post('/kasir/pembayaran/storepay', 'storePay')->name('pembayaran.storePay');
        Route::get('/kasir/pembayaran/edit/{id}', 'edit')->name('pembayaran.edit');
        Route::get('/kasir/pembayaran/pay/{id}', 'pay')->name('pembayaran.pay');
        Route::get('/kasir/pembayaran/print/{id}', 'print')->name('pembayaran.print');
        Route::get('/kasir/pembayaran/destroy/{id}', 'destroy')->name('pembayaran.destroy');

        Route::get('/kasir/pembayaran/getDiskon', 'getDiskon')->name('lab.getDiskon');
        Route::get('/kasir/pembayaran/getKartu', 'getKartu')->name('lab.getKartu');
    });

    //laboratorium
    Route::controller(InputHasilLabController::class)->group(function () {
        Route::get('laboratorium/inputhasillab', 'index')->name('inputhasillab.index');
        Route::get('laboratorium/inputhasillab/getData', 'getData')->name('inputhasillab.getData');
        Route::get('laboratorium/inputhasillab/add/{id}', 'create')->name('inputhasillab.add');
        Route::post('laboratorium/inputhasillab/store', 'store')->name('inputhasillab.store');
        Route::get('laboratorium/inputhasillab/edit/{id}', 'edit')->name('inputhasillab.edit');
        Route::post('laboratorium/inputhasillab/update/{id}', 'update')->name('inputhasillab.update');
        Route::get('laboratorium/inputhasillab/destroy/{id}', 'destroy')->name('inputhasillab.destroy');

        Route::get('laboratorium/inputhasillab/printhasilperiksa/{id}', 'printHasilPeriksa')->name('inputhasillab.print');
        Route::get('laboratorium/inputhasillab/sendhasilperiksa/{id}', 'sendHasilPeriksa')->name('inputhasillab.sendEmail');
        Route::get('laboratorium/inputhasillab/getRawatJalan', 'getRawatJalan')->name('inputhasillab.getRawatJalan');
        Route::get('laboratorium/inputhasillab/getPemeriksaan', 'getPemeriksaan')->name('inputhasillab.getPemeriksaan');
        Route::get('laboratorium/inputhasillab/getPaket', 'getPaket')->name('inputhasillab.getPaket');
    });

    //laporan

    Route::controller(LaporanRegistrasiLabController::class)->group(function () {
        Route::get('/laporan/registrasilab', 'index')->name('registrasilab.index');
        Route::get('/laporan/registrasilab/getData', 'getData')->name('registrasilab.getData');
    });

    Route::controller(LaporanKunjunganPasienController::class)->group(function () {
        Route::get('/laporan/rekapkunjunganlab', 'index')->name('rekapkunjunganlab.index');
        Route::get('/laporan/rekapkunjunganlab/getData', 'getData')->name('rekapkunjunganlab.getData');
    });
    Route::controller(LaporanPendapatanLabController::class)->group(function () {
        Route::get('/laporan/rekappendapatanlab', 'index')->name('rekappendapatanlab.index');
        Route::get('/laporan/rekappendapatanlab/getData', 'getData')->name('rekappendapatanlab.getData');
    });

    //tabel
    Route::controller(TabelTarifLabController::class)->group(function () {
        Route::get('/tabel/tariflab', 'index')->name('tabel.tariflab.index');
        Route::get('/tabel/tariflab/view/{id}', 'view')->name('tabel.tariflab.view');
    });

    Route::controller(TabelPemeriksaanController::class)->group(function () {
        Route::get('tabel/pemeriksaan', 'index')->name('tabel.pemeriksaan.index');
        Route::get('tabel/pemeriksaan/view/{id}', 'view')->name('tabel.pemeriksaan.view');
    });

    Route::controller(TabelHubTarifPemeriksaanController::class)->group(function () {
        Route::get('tabel/hubtarifpemeriksaan', 'index')->name('tabel.hubtarifpemeriksaan.index');
        Route::get('tabel/hubtarifpemeriksaan/data', 'getData')->name('tabel.hubtarifpemeriksaan.getData');
        Route::get('tabel/hubtarifpemeriksaan/view/{id}', 'show')->name('tabel.hubtarifpemeriksaan.view');
    });

    Route::controller(TabelPaketLabController::class)->group(function () {
        Route::get('tabel/paketlab', 'index')->name('tabel.paketlab.index');
        Route::get('tabel/paketlab/data', 'getData')->name('tabel.paketlab.getData');
        Route::get('tabel/paketlab/view/{id}', 'view')->name('tabel.paketlab.view');
    });
    Route::controller(TabelBarcodePemeriksaanController::class)->group(function () {
        Route::get('tabel/barcodepemeriksaan', 'index')->name('tabel.barcodepemeriksaan.index');
        Route::get('tabel/barcodepemeriksaan/data', 'getData')->name('tabel.barcodepemeriksaan.getData');
        Route::get('tabel/barcodepemeriksaan/view/{id}', 'view')->name('tabel.barcodepemeriksaan.view');
    });


    Route::controller(TabelHubTarifPaketController::class)->group(function () {
        Route::get('tabel/hubpakettarif', 'index')->name('tabel.hubpakettarif.index');
        Route::get('tabel/hubpakettarif/data', 'getData')->name('tabel.hubpakettarif.getData');
        Route::get('tabel/hubpakettarif/view/{id}', 'show')->name('tabel.hubpakettarif.view');
    });
    Route::controller(TabelLabReferenceController::class)->group(function () {
        Route::get('tabel/labreference', 'index')->name('tabel.labreference.index');
        Route::get('tabel/labreference/data', 'getData')->name('tabel.labreference.getData');
        Route::get('tabel/labreference/view/{id}', 'view')->name('tabel.labreference.view');
        Route::get('tabel/labreference/getreference/{lab_kode}', 'getReference')->name('tabel.labreference.getreference');
    });

    //master
    Route::controller(DokterHilabController::class)->group(function () {
        Route::get('/master/dokterhilab', 'index')->name('dokterhilab.index');
        Route::get('/master/dokterhilab/add', 'create')->name('dokterhilab.add');
        Route::post('/master/dokterhilab/store', 'store')->name('dokterhilab.store');
        Route::get('/master/dokterhilab/edit/{id}', 'edit')->name('dokterhilab.edit');
        Route::post('/master/dokterhilab/update/{id}', 'update')->name('dokterhilab.update');
        Route::get('/master/dokterhilab/destroy/{id}', 'destroy')->name('dokterhilab.destroy');
    });

    Route::controller(MasterPaketLabController::class)->group(function () {
        Route::get('/master/paketlab', 'index')->name('paketlab.index');
        Route::get('/master/paketlab/add', 'create')->name('paketlab.add');
        Route::post('/master/paketlab/store', 'store')->name('paketlab.store');
        Route::get('/master/paketlab/edit/{id}', 'edit')->name('paketlab.edit');
        Route::post('/master/paketlab/update/{id}', 'update')->name('paketlab.update');
        Route::get('/master/paketlab/destroy/{id}', 'destroy')->name('paketlab.destroy');
    });

    Route::controller(MasterPemeriksaanController::class)->group(function () {
        Route::get('/master/pemeriksaan', 'index')->name('pemeriksaan.index');
        Route::get('/master/pemeriksaan/add', 'create')->name('pemeriksaan.add');
        Route::post('/master/pemeriksaan/store', 'store')->name('pemeriksaan.store');
        Route::get('/master/pemeriksaan/edit/{id}', 'edit')->name('pemeriksaan.edit');
        Route::post('/master/pemeriksaan/update/{id}', 'update')->name('pemeriksaan.update');
        Route::get('/master/pemeriksaan/destroy/{id}', 'destroy')->name('pemeriksaan.destroy');
    });

    Route::controller(MasterTarifLabController::class)->group(function () {
        Route::get('/master/tariflab', 'index')->name('tariflab.index');
        Route::get('/master/tariflab/add', 'create')->name('tariflab.add');
        Route::post('/master/tariflab/store', 'store')->name('tariflab.store');
        Route::get('/master/tariflab/edit/{id}', 'edit')->name('tariflab.edit');
        Route::post('/master/tariflab/update/{id}', 'update')->name('tariflab.update');
        Route::get('/master/tariflab/destroy/{id}', 'destroy')->name('tariflab.destroy');

        Route::get('/master/tariflab/getData', 'getData')->name('tariflab.getData');
    });

    Route::controller(MasterHubTarifPemeriksaanController::class)->group(function () {
        Route::get('/master/hubtarifpemeriksaan', 'index')->name('hubtarifpemeriksaan.index');
        Route::get('/master/hubtarifpemeriksaan/add', 'create')->name('hubtarifpemeriksaan.add');
        Route::post('/master/hubtarifpemeriksaan/store', 'store')->name('hubtarifpemeriksaan.store');
        Route::get('/master/hubtarifpemeriksaan/view/{id}', 'show')->name('hubtarifpemeriksaan.view');
        Route::get('/master/hubtarifpemeriksaan/edit/{id}', 'edit')->name('hubtarifpemeriksaan.edit');
        Route::post('/master/hubtarifpemeriksaan/update/{id}', 'update')->name('hubtarifpemeriksaan.update');
        Route::get('/master/hubtarifpemeriksaan/destroy/{id}', 'destroy')->name('hubtarifpemeriksaan.destroy');

        Route::get('/master/hubtarifpemeriksaan/getData', 'getData')->name('hubtarifpemeriksaan.getData');
    });

    //    Route::resource('/master/hubtarifpaket', MasterHubTarifPaketController::class);
    Route::controller(MasterHubTarifPaketController::class)->group(function () {
        Route::get('/master/hubtarifpaket', 'index')->name('hubtarifpaket.index');
        Route::get('/master/hubtarifpaket/add', 'create')->name('hubtarifpaket.add');
        Route::post('/master/hubtarifpaket/store', 'store')->name('hubtarifpaket.store');
        Route::get('/master/hubtarifpaket/view/{id}', 'show')->name('hubtarifpaket.view');
        Route::get('/master/hubtarifpaket/edit/{id}', 'edit')->name('hubtarifpaket.edit');
        Route::post('/master/hubtarifpaket/update/{id}', 'update')->name('hubtarifpaket.update');
        Route::get('/master/hubtarifpaket/destroy/{id}', 'destroy')->name('hubtarifpaket.destroy');

        Route::get('/master/hubtarifpaket/getData', 'getData')->name('hubtarifpaket.getData');
    });

    Route::controller(MasterLabReferenceController::class)->group(function () {
        Route::get('/master/labreference', 'index')->name('labreference.index');
        Route::get('/master/labreference/add', 'create')->name('labreference.add');
        Route::post('/master/labreference/store', 'store')->name('labreference.store');
        Route::get('/master/labreference/view/{id}', 'show')->name('labreference.view');
        Route::get('/master/labreference/edit/{id}', 'edit')->name('labreference.edit');
        Route::post('/master/labreference/update/{id}', 'update')->name('labreference.update');
        Route::get('/master/labreference/destroy/{id}', 'destroy')->name('labreference.destroy');

        Route::get('/master/labreference/getreference/{lab_kode}', 'getReference')->name('labreference.getreference');
        Route::post('/master/labreference/updatereference', 'updateReference')->name('labreference.updatereference');
    });

    Route::controller(MasterBarcodePemeriksaanController::class)->group(function () {
        Route::get('/master/barcodepemeriksaan', 'index')->name('barcodepemeriksaan.index');
        Route::get('/master/barcodepemeriksaan/add', 'create')->name('barcodepemeriksaan.add');
        Route::post('/master/barcodepemeriksaan/store', 'store')->name('barcodepemeriksaan.store');
        Route::get('/master/barcodepemeriksaan/view/{id}', 'show')->name('barcodepemeriksaan.view');
        Route::get('/master/barcodepemeriksaan/edit/{id}', 'edit')->name('barcodepemeriksaan.edit');
        Route::post('/master/barcodepemeriksaan/update/{id}', 'update')->name('barcodepemeriksaan.update');
        Route::get('/master/barcodepemeriksaan/destroy/{id}', 'destroy')->name('barcodepemeriksaan.destroy');
    });

    Route::controller(MasterTentangKamiController::class)->group(function () {
        Route::get('/master/tentangkami', 'index')->name('tentangkami.index');
        Route::get('/master/tentangkami/add', 'create')->name('tentangkami.add');
        Route::post('/master/tentangkami/store', 'store')->name('tentangkami.store');
        Route::get('/master/tentangkami/view/{id}', 'show')->name('tentangkami.view');
        Route::get('/master/tentangkami/edit/{id}', 'edit')->name('tentangkami.edit');
        Route::post('/master/tentangkami/update/{id}', 'update')->name('tentangkami.update');
        Route::get('/master/tentangkami/destroy/{id}', 'destroy')->name('tentangkami.destroy');
    });

    Route::controller(MasterBannerController::class)->group(function () {
        Route::get('/master/banner', 'index')->name('banner.index');
        Route::get('/master/banner/add', 'create')->name('banner.add');
        Route::post('/master/banner/store', 'store')->name('banner.store');
        Route::get('/master/banner/view/{id}', 'show')->name('banner.view');
        Route::get('/master/banner/edit/{id}', 'edit')->name('banner.edit');
        Route::post('/master/banner/update/{id}', 'update')->name('banner.update');
        Route::get('/master/banner/destroy/{id}', 'destroy')->name('banner.destroy');
    });

    Route::controller(MasterInformasiController::class)->group(function () {
        Route::get('/master/informasi', 'index')->name('informasi.index');
        Route::get('/master/informasi/add', 'create')->name('informasi.add');
        Route::post('/master/informasi/store', 'store')->name('informasi.store');
        Route::get('/master/informasi/view/{id}', 'show')->name('informasi.view');
        Route::get('/master/informasi/edit/{id}', 'edit')->name('informasi.edit');
        Route::post('/master/informasi/update/{id}', 'update')->name('informasi.update');
        Route::get('/master/informasi/destroy/{id}', 'destroy')->name('informasi.destroy');
    });

    Route::controller(MasterSyaratKetentuanController::class)->group(function () {
        Route::get('/master/syaratketentuan', 'index')->name('syaratketentuan.index');
        Route::get('/master/syaratketentuan/add', 'create')->name('syaratketentuan.add');
        Route::post('/master/syaratketentuan/store', 'store')->name('syaratketentuan.store');
        Route::get('/master/syaratketentuan/view/{id}', 'show')->name('syaratketentuan.view');
        Route::get('/master/syaratketentuan/edit/{id}', 'edit')->name('syaratketentuan.edit');
        Route::post('/master/syaratketentuan/update/{id}', 'update')->name('syaratketentuan.update');
        Route::get('/master/syaratketentuan/destroy/{id}', 'destroy')->name('syaratketentuan.destroy');
    });

    Route::controller(MasterLayananController::class)->group(function () {
        Route::get('/master/layananhilab', 'index')->name('layananhilab.index');
        Route::get('/master/layananhilab/add', 'create')->name('layananhilab.add');
        Route::post('/master/layananhilab/store', 'store')->name('layananhilab.store');
        Route::get('/master/layananhilab/view/{id}', 'show')->name('layananhilab.view');
        Route::get('/master/layananhilab/edit/{id}', 'edit')->name('layananhilab.edit');
        Route::post('/master/layananhilab/update/{id}', 'update')->name('layananhilab.update');
        Route::get('/master/layananhilab/destroy/{id}', 'destroy')->name('layananhilab.destroy');
    });

    Route::controller(MasterLayananLabController::class)->group(function () {
        Route::get('/master/layananlab', 'index')->name('layananlab.index');
        Route::get('/master/layananlab/add', 'create')->name('layananlab.add');
        Route::post('/master/layananlab/store', 'store')->name('layananlab.store');
        Route::get('/master/layananlab/view/{id}', 'show')->name('layananlab.view');
        Route::get('/master/layananlab/edit/{id}', 'edit')->name('layananlab.edit');
        Route::post('/master/layananlab/update/{id}', 'update')->name('layananlab.update');
        Route::get('/master/layananlab/destroy/{id}', 'destroy')->name('layananlab.destroy');
    });

    Route::controller(MasterLayananTestDetailController::class)->group(function () {
        Route::get('/master/layanantestdetail', 'index')->name('layanantestdetail.index');
        Route::get('/master/layanantestdetail/add', 'create')->name('layanantestdetail.add');
        Route::post('/master/layanantestdetail/store', 'store')->name('layanantestdetail.store');
        Route::get('/master/layanantestdetail/view/{id}', 'show')->name('layanantestdetail.view');
        Route::get('/master/layanantestdetail/edit/{id}', 'edit')->name('layanantestdetail.edit');
        Route::post('/master/layanantestdetail/update/{id}', 'update')->name('layanantestdetail.update');
        Route::get('/master/layanantestdetail/destroy/{id}', 'destroy')->name('layanantestdetail.destroy');
    });

    Route::controller(MasterLayananPromoController::class)->group(function () {
        Route::get('/master/layananpromo', 'index')->name('layananpromo.index');
        Route::get('/master/layananpromo/add', 'create')->name('layananpromo.add');
        Route::post('/master/layananpromo/store', 'store')->name('layananpromo.store');
        Route::get('/master/layananpromo/view/{id}', 'show')->name('layananpromo.view');
        Route::get('/master/layananpromo/edit/{id}', 'edit')->name('layananpromo.edit');
        Route::post('/master/layananpromo/update/{id}', 'update')->name('layananpromo.update');
        Route::get('/master/layananpromo/destroy/{id}', 'destroy')->name('layananpromo.destroy');


        Route::post('/master/layananpromo/get-data', 'getData')->name('layananpromo.getData');
        Route::get('/master/layananpromo/get-data-by-kode/{tarifKode}', 'getDataByKode')->name('layananpromo.getDataByKode');
    });

    Route::controller(MasterGroupPaketController::class)->group(function () {
        Route::get('/master/grouppaket', 'index')->name('grouppaket.index');
        Route::get('/master/grouppaket/add', 'create')->name('grouppaket.add');
        Route::post('/master/grouppaket/store', 'store')->name('grouppaket.store');
        Route::get('/master/grouppaket/view/{id}', 'show')->name('grouppaket.view');
        Route::get('/master/grouppaket/edit/{id}', 'edit')->name('grouppaket.edit');
        Route::post('/master/grouppaket/update/{id}', 'update')->name('grouppaket.update');
        Route::get('/master/grouppaket/destroy/{id}', 'destroy')->name('grouppaket.destroy');
    });

    Route::controller(MasterPerusahaanController::class)->group(function () {
        Route::get('/master/perusahaan', 'index')->name('perusahaan.index');
        Route::get('/master/perusahaan/add', 'create')->name('perusahaan.add');
        Route::post('/master/perusahaan/store', 'store')->name('perusahaan.store');
        Route::get('/master/perusahaan/view/{id}', 'show')->name('perusahaan.view');
        Route::get('/master/perusahaan/edit/{id}', 'edit')->name('perusahaan.edit');
        Route::post('/master/perusahaan/update/{id}', 'update')->name('perusahaan.update');
        Route::get('/master/perusahaan/destroy/{id}', 'destroy')->name('perusahaan.destroy');
    });

    Route::controller(MasterPenjaminController::class)->group(function () {
        Route::get('/master/penjamin', 'index')->name('penjamin.index');
        Route::get('/master/penjamin/add', 'create')->name('penjamin.add');
        Route::post('/master/penjamin/store', 'store')->name('penjamin.store');
        Route::get('/master/penjamin/view/{id}', 'show')->name('penjamin.view');
        Route::get('/master/penjamin/edit/{id}', 'edit')->name('penjamin.edit');
        Route::post('/master/penjamin/update/{id}', 'update')->name('penjamin.update');
        Route::get('/master/penjamin/destroy/{id}', 'destroy')->name('penjamin.destroy');
    });

    Route::controller(MasterTtdDokterController::class)->group(function () {
        Route::get('/master/ttddokter', 'index')->name('ttddokter.index');
        Route::get('/master/ttddokter/add', 'create')->name('ttddokter.add');
        Route::post('/master/ttddokter/store', 'store')->name('ttddokter.store');
        Route::get('/master/ttddokter/view/{id}', 'show')->name('ttddokter.view');
        Route::get('/master/ttddokter/edit/{id}', 'edit')->name('ttddokter.edit');
        Route::post('/master/ttddokter/update/{id}', 'update')->name('ttddokter.update');
        Route::get('/master/ttddokter/destroy/{id}', 'destroy')->name('ttddokter.destroy');
    });

    Route::controller(MasterLokasiHilabController::class)->group(function () {
        Route::get('/master/lokasihilab', 'index')->name('lokasihilab.index');
        Route::get('/master/lokasihilab/add', 'create')->name('lokasihilab.add');
        Route::post('/master/lokasihilab/store', 'store')->name('lokasihilab.store');
        Route::get('/master/lokasihilab/view/{id}', 'show')->name('lokasihilab.view');
        Route::get('/master/lokasihilab/edit/{id}', 'edit')->name('lokasihilab.edit');
        Route::post('/master/lokasihilab/update/{id}', 'update')->name('lokasihilab.update');
        Route::get('/master/lokasihilab/destroy/{id}', 'destroy')->name('lokasihilab.destroy');

        Route::post('/master/lokasihilab/getKota', 'getKota')->name('lokasihilab.getKota');
    });

    //account
    Route::controller(MasterUserController::class)->group(function () {
        Route::get('/account/user', 'index')->name('account.user.index');
        Route::get('/account/user/add', 'create')->name('account.user.add');
        Route::post('/account/user/store', 'store')->name('account.user.store');
        Route::get('/account/user/edit/{id}', 'edit')->name('account.user.edit');
        Route::post('/account/user/update/{id}', 'update')->name('account.user.update');
        Route::get('/account/user/destroy/{id}', 'destroy')->name('account.user.destroy');
    });

    Route::controller(MasterUserMobileController::class)->group(function () {
        Route::get('/account/usermobile', 'index')->name('account.usermobile.index');
        Route::get('/account/usermobile/add', 'create')->name('account.usermobile.add');
        Route::post('/account/usermobile/store', 'store')->name('account.usermobile.store');
        Route::get('/account/usermobile/edit/{id}', 'edit')->name('account.usermobile.edit');
        Route::post('/account/usermobile/update/{id}', 'update')->name('account.usermobile.update');
        Route::get('/account/usermobile/destroy/{id}', 'destroy')->name('account.usermobile.destroy');
    });
});


require __DIR__ . '/auth.php';
