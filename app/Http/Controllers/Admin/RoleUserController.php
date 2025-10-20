<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;

class RoleUserController extends Controller
{
    public function index()
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $usersWithRoles = User::with(['roleUsers.role'])
            ->orderBy('iduser', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'iduser' => $user->iduser,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'roles' => $user->roleUsers->map(function ($roleUser) {
                        return [
                            'idrole' => $roleUser->idrole,
                            'nama_role' => $roleUser->role->nama_role ?? 'Unknown',
                            'status_aktif' => $roleUser->status_aktif,
                        ];
                    }),
                ];
            });

        return view('admin.role-user.index', compact('usersWithRoles'));
    }

    public function assign($iduser)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($iduser);
        $allRoles = Role::orderBy('nama_role')->get();

        $userRoles = RoleUser::where('iduser', $iduser)
            ->with('role')
            ->get();

        $userRoleIds = $userRoles->pluck('idrole')->toArray();

        $assignedRoles = $allRoles->filter(function ($role) use ($userRoleIds, $userRoles) {
            if (in_array($role->idrole, $userRoleIds)) {
                $roleUser = $userRoles->firstWhere('idrole', $role->idrole);
                $role->status_aktif = $roleUser->status_aktif;
                return true;
            }
            return false;
        });

        $unassignedRoles = $allRoles->filter(function ($role) use ($userRoleIds) {
            return !in_array($role->idrole, $userRoleIds);
        });

        return view('admin.role-user.assign', compact('user', 'assignedRoles', 'unassignedRoles'));
    }

    public function update(Request $request, $iduser)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($iduser);

        $action = $request->input('action');

        if (preg_match('/^add_(\d+)$/', $action, $matches)) {
            $idrole = $matches[1];
            
            $existing = RoleUser::where('iduser', $iduser)
                ->where('idrole', $idrole)
                ->first();

            if (!$existing) {
                RoleUser::create([
                    'iduser' => $iduser,
                    'idrole' => $idrole,
                    'status_aktif' => 1,
                ]);
            }

            return redirect()->route('admin.role-user.assign', $iduser)
                ->with('success', 'Role berhasil ditambahkan');
        }

        if (preg_match('/^remove_(\d+)$/', $action, $matches)) {
            $idrole = $matches[1];
            
            RoleUser::where('iduser', $iduser)
                ->where('idrole', $idrole)
                ->delete();

            return redirect()->route('admin.role-user.assign', $iduser)
                ->with('success', 'Role berhasil dihapus');
        }

        if ($action === 'save') {
            $roleStatuses = $request->input('role_status', []);

            $currentRoles = RoleUser::where('iduser', $iduser)->get();

            foreach ($currentRoles as $roleUser) {
                $newStatus = isset($roleStatuses[$roleUser->idrole]) && $roleStatuses[$roleUser->idrole] == '1' ? 1 : 0;
                
                $roleUser->update([
                    'status_aktif' => $newStatus,
                ]);
            }

            return redirect()->route('admin.role-user.index')
                ->with('success', 'Status role berhasil diupdate');
        }

        return redirect()->route('admin.role-user.assign', $iduser)
            ->with('error', 'Aksi tidak valid');
    }
}
