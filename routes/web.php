<?php

use App\Http\Controllers\Account\MasterUserController;
use App\Http\Controllers\Account\MasterUserMobileController;
use App\Http\Controllers\Pendaftaran\PendaftaranPasienController;
use App\Http\Controllers\Konfigurasi\RoleController;
use App\Http\Controllers\Konfigurasi\NavigationController;
use App\Http\Controllers\Konfigurasi\PermissionController;
use App\Http\Controllers\Master\MasterBarcodePemeriksaanController;
use App\Http\Controllers\Master\MasterHubTarifPaketController;
use App\Http\Controllers\Master\MasterHubTarifPemeriksaanController;
use App\Http\Controllers\Master\MasterLabReferenceController;
use App\Http\Controllers\Master\MasterPaketLabController;
use App\Http\Controllers\Master\MasterPemeriksaanController;
use App\Http\Controllers\Master\MasterTarifLabController;
use App\Http\Controllers\pendaftaran\AppointmentController;
use App\Http\Controllers\pendaftaran\InformasiPendaftaranController;
use App\Http\Controllers\pendaftaran\PendaftaranLabController;
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

    Route::controller(RoleController::class)->group(function () {
        Route::get('konfigurasi/roles', 'index')->name('roles.index');
        Route::get('konfigurasi/roles/create', 'create')->name('roles.create');
        Route::get('konfigurasi/roles/store', 'store')->name('roles.store');
        Route::get('konfigurasi/roles/view/{id}', 'view')->name('roles.view');
        Route::get('konfigurasi/roles/addPermission', 'addPermission')->name('roles.addPermission');
        Route::get('konfigurasi/roles/update/{id}', 'update')->name('roles.update');
        Route::get('konfigurasi/roles/destroy/{id}', 'destroy')->name('roles.destroy');
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
        Route::get('pendaftaran/pasien', 'index')->name('pasien.index');
        Route::get('pendaftaran/pasien/create', 'create')->name('pasien.create');
        Route::get('pendaftaran/pasien/store', 'store')->name('pasien.store');
        Route::get('pendaftaran/pasien/update/{id}', 'update')->name('pasien.update');
        Route::get('pendaftaran/pasien/destroy/{id}', 'destroy')->name('pasien.destroy');
    });

    Route::controller(PendaftaranLabController::class)->group(function () {
        Route::get('pendaftaran/lab', 'index')->name('lab.index');
        Route::get('pendaftaran/lab/create', 'create')->name('lab.create');
        Route::get('pendaftaran/lab/store', 'store')->name('lab.store');
        Route::get('pendaftaran/lab/update/{id}', 'update')->name('lab.update');
        Route::get('pendaftaran/lab/destroy/{id}', 'destroy')->name('lab.destroy');
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

    //master
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
    });

    Route::controller(MasterHubTarifPemeriksaanController::class)->group(function () {
        Route::get('/master/hubtarifpemeriksaan', 'index')->name('hubtarifpemeriksaan.index');
        Route::get('/master/hubtarifpemeriksaan/add', 'create')->name('hubtarifpemeriksaan.add');
        Route::post('/master/hubtarifpemeriksaan/store', 'store')->name('hubtarifpemeriksaan.store');
        Route::get('/master/hubtarifpemeriksaan/view/{id}', 'show')->name('hubtarifpemeriksaan.view');
        Route::get('/master/hubtarifpemeriksaan/edit/{id}', 'edit')->name('hubtarifpemeriksaan.edit');
        Route::post('/master/hubtarifpemeriksaan/update/{id}', 'update')->name('hubtarifpemeriksaan.update');
        Route::get('/master/hubtarifpemeriksaan/destroy/{id}', 'destroy')->name('hubtarifpemeriksaan.destroy');
    });

    Route::controller(MasterHubTarifPaketController::class)->group(function () {
        Route::get('/master/hubtarifpaket', 'index')->name('hubtarifpaket.index');
        Route::get('/master/hubtarifpaket/add', 'create')->name('hubtarifpaket.add');
        Route::post('/master/hubtarifpaket/store', 'store')->name('hubtarifpaket.store');
        Route::get('/master/hubtarifpaket/view/{id}', 'show')->name('hubtarifpaket.view');
        Route::get('/master/hubtarifpaket/edit/{id}', 'edit')->name('hubtarifpaket.edit');
        Route::post('/master/hubtarifpaket/update/{id}', 'update')->name('hubtarifpaket.update');
        Route::get('/master/hubtarifpaket/destroy/{id}', 'destroy')->name('hubtarifpaket.destroy');
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
