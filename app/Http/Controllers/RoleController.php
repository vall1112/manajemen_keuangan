<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // ========================== AMBIL SEMUA DATA ROLE (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Role::all()
        ]);
    }

    // ========================== AMBIL DATA ROLE DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Role::when($request->search, function (Builder $query, string $search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('full_name', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA ROLE BARU ==========================
    public function store(RoleRequest $request)
    {
        $validatedData = $request->validated();
        $role = Role::create([
            'name' => $validatedData['name'],
            'guard_name' => 'api',
            'full_name' => $validatedData['full_name']
        ]);
        $role->syncPermissions($validatedData['permissions']);
        return response()->json([
            'success' => true,
            'role' => $role,
        ]);
    }

    // ========================== TAMPILKAN DETAIL ROLE ==========================
    public function show(Role $role)
    {
        return response()->json([
            'role' => [
                'name' => $role->name,
                'full_name' => $role->full_name,
                'permissions' => $role->permissions->pluck('name')
            ]
        ]);
    }

    // ========================== UPDATE DATA ROLE ==========================
    public function update(RoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();

        $role->update([
            'name' => $validatedData['name'],
            'guard_name' => 'api',
            'full_name' => $validatedData['full_name']
        ]);

        $role->syncPermissions($validatedData['permissions']);

        return response()->json([
            'success' => true,
            'role' => $role
        ]);
    }

    // ========================== HAPUS DATA ROLE ==========================
    public function destroy(Role $role)
    {
        $role->revokePermissionTo($role->permissions);
        $role->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
