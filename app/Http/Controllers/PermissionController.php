<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read konfigurasi');
    }

    public function index(Request $request)
    {
        $data = Permission::all();
        $menu = Navigation::all();
        return view('konfigurasi.permission.index', compact('data', 'menu'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $menu = Navigation::find($request->id);

            Permission::create([
                'name' => 'read ' . $menu->url,
                'guard_name' => 'web',
            ]);

            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('permission.index');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }
}
