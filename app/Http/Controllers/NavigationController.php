<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class NavigationController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read konfigurasi');
    }

    public function index(Request $request)
    {
        $data = Navigation::all();
        return view('konfigurasi.navigasi.index', compact('data'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'url' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            Navigation::create([
                'name' => $request->name,
                'url' => $request->url,
                'icon' => $request->icon,
                'main_menu' => $request->main_menu,
                'sort' => $request->sort,
            ]);

            Permission::create([
                'name' => 'read ' . $request->url,
                'guard_name' => 'web',
            ]);
            DB::commit();
            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('navigasi.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }
}
