<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class NavigationController extends Controller
{
    public function __construct()
    {

        $this->middleware('can:read konfigurasi/navigasi');
    }

    public function index(Request $request)
    {
        $data = Navigation::query()->select('navigations.*')->orderBy('id', 'desc')->get();

        // $menu = Navigation::query()
        //     ->orWhereNull('main_menu')
        //     -s
        //     ->get();

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

            $menu = new Navigation;
            $menu->name = $request->name;
            $menu->url = $request->url;
            $menu->icon = $request->icon;
            $menu->main_menu = $request->main_menu;
            $menu->sort = $request->sort;

            if ($menu->save()) {
                Permission::create([
                    'name' => 'read ' . $request->url,
                    'guard_name' => 'web',
                    'navigation_id' => $menu->id,
                ]);
            }

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

    public function update(Request $request, $id)
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
            $data = Navigation::find($id);

            $data->name = $request->name;
            $data->url = $request->url;
            $data->icon = $request->icon;
            $data->main_menu = $request->main_menu;
            $data->sort = $request->sort;
            if ($data->save()) {

                $permission = Permission::where('navigation_id', $id)->first();
                $permission->name = 'read ' . $request->url;
                $permission->guard_name = 'web';
                $permission->navigation_id = $id;
                $permission->save();
            }
            DB::commit();
            Session::flash('toast_success', 'Data berhasil diubah');
            return redirect()->route('navigasi.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $data = Navigation::find($id);
        $detail = Permission::where('navigation_id', $id)->first();

        if (!$data && !$detail) {
            Session::flash('toast_failed', 'Data tidak ditemukan');
            return redirect()->back();
        }

        if ($data->delete() && $detail->delete()) {
            Session::flash('toast_success', 'Data berhasil dihapus');
        } else {
            Session::flash('toast_failed', 'Data gagal dihapus');
        }
        return redirect()->back();
    }
}
