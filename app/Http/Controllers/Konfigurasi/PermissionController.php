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
        $data = ModelHasRole::query()
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->join('users', 'users.id', 'model_has_roles.model_id')
            ->get();
        $roles = Role::all();
        $usersWithoutRole = User::whereDoesntHave('roles')->get();
        $users = User::query()
            ->join('model_has_roles', 'model_has_roles.model_id', 'users.id')
            ->join('roles', 'roles.id', 'model_has_roles.role_id')
            ->get();

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
}
