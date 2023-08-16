<?php

namespace App\Http\Controllers\Konfigurasi;

use App\Http\Controllers\Controller;
use App\Models\ModelHasRole;
use App\Models\Navigation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:read konfigurasi/permission');
    }

    public function index(Request $request)
    {
        $data = User::query()
            ->join('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->select('model_has_roles.*', 'users.username', 'roles.name', 'model_has_roles.id as has_role_id')
            ->get();
        $roles = Role::all();
        $usersWithoutRole = User::whereDoesntHave('roles')->get();
        $users = User::all();

        return view('konfigurasi.permission.index', compact('data', 'roles', 'users', 'usersWithoutRole'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'role_id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $already = ModelHasRole::query()
                ->where('role_id', $request->role_id)
                ->where('model_id', $request->user_id)
                ->first();

            if ($already) {
                Session::flash('toast_failed', 'Data sudah tersedia');
                return redirect()->back();
            }


            $user = User::where('id', $request->user_id)->first();
            $user->role_id = $request->role_id;

            if ($user->save()) {
                $permission = new ModelHasRole;
                $permission->role_id = $request->role_id;
                $permission->model_type = 'App\Models\User';
                $permission->model_id = $request->user_id;
                $permission->save();
            }

            Session::flash('toast_success', 'Data berhasil ditambah');
            return redirect()->route('permission.index');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal menambahkan data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'role_id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator);
            }

            $already = ModelHasRole::query()
                ->where('role_id', $request->role_id)
                ->where('model_id', $request->user_id)
                ->first();

            if ($already) {
                Session::flash('toast_failed', 'Data sudah tersedia');
                return redirect()->back();
            }

            $data = ModelHasRole::find($id);
            $data->role_id = $request->role_id;
            $data->model_type = 'App\Models\User';
            $data->model_id = $request->user_id;
            $data->save();

            Session::flash('toast_success', 'Data berhasil diubah');
            return redirect()->route('permission.index');
        } catch (\Exception $e) {
            Log::error('Error creating permission: ' . $e->getMessage());
            Session::flash('toast_failed', 'Gagal mengubah data. Silakan coba lagi.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $data = ModelHasRole::find($id);

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
