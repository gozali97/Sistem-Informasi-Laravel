<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read konfigurasi/roles');
    }

    public function index(Request $request)
    {
        $data = Role::all();
        return view('konfigurasi.roles.index', compact('data'));
    }


    public function create()
    {
        return 'create role';
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'guard_name' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);

            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }


    public function view($id)
    {
        $data = Role::query()
            ->join('users', 'users.role_id', 'roles.id')
            ->join('role_has_permissions', 'role_has_permissions.role_id', 'roles.id')
            ->join('permissions', 'permissions.id', 'role_has_permissions.permission_id')
            ->where('roles.id', $id)->get();

        $role = Role::find($id);


        $permission = Permission::all();

        return view('konfigurasi.roles.view', compact('data', 'permission', 'role'));
    }


    public function addPermission(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'role_id' => 'required',
                'permission' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            RolePermission::create([
                'permission_id' => $request->permission,
                'role_id' => $request->role_id,
            ]);

            Session::flash('toast_success', 'Data permission berhasil ditambah');
            return redirect()->route('roles.view', $request->role_id);
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'guard_name' => 'required|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }
            $data = Role::find($id);

            $data->name = $request->name;
            $data->guard_name = $request->guard_name;
            $data->save();

            Session::flash('toast_success', 'Data berhasil diubah');
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $data = Role::find($id);

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
}
