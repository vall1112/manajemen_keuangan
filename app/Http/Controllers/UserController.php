<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // ========================== AMBIL SEMUA DATA USER (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => User::when($request->role_id, function (Builder $query, string $role_id) {
                $query->role($role_id);
            })->get()
        ]);
    }

    // ========================== AMBIL DATA USER DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = User::with(['student:id,nama']) // hanya ambil id dan nama dari relasi student
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhereHas('student', function ($q2) use ($search) {
                            $q2->where('nama', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA USER BARU ==========================
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('photo')) {
            $validatedData['photo'] = $request->file('photo')->store('photo', 'public');
        }

        $user = User::create($validatedData);

        $role = Role::findById($validatedData['role_id']);
        $user->assignRole($role);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    // ========================== TAMPILKAN DETAIL USER ==========================
    public function show(User $user)
    {
        $user['role_id'] = $user?->role?->id;
        return response()->json([
            'user' => $user
        ]);
    }

    // ========================== UPDATE DATA USER ==========================
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        if (!isset($validatedData['status']) || $validatedData['status'] === null) {
            $validatedData['status'] = 'Aktif';
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $validatedData['photo'] = $request->file('photo')->store('photo', 'public');
        } else {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
                $validatedData['photo'] = null;
            }
        }

        $user->update($validatedData);

        $role = Role::findById($validatedData['role_id']);
        $user->syncRoles($role);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    // ========================== HAPUS DATA USER ==========================
    public function destroy(User $user)
    {
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // ========================== USER STATUS PENDING ==========================
    public function userPending(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = User::with(['student:id,nama'])
            ->where('status', 'Pending')
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('username', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhereHas('student', function ($q2) use ($search) {
                            $q2->where('nama', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== MEMBUAT KARTU LOGIN ==========================
    public function card(User $user)
    {
        $setting = Setting::firstOrFail();

        return view('users.card', [
            'user' => $user,
            'setting' => $setting,
        ]);
    }
}