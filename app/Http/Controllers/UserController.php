<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
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
    // ========================== AMBIL DATA ADMIN DENGAN PAGINASI ==========================
    public function index_admin(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = User::whereHas('roles', function ($q) {
            $q->where('id', 1);
        })
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate($per);

        $startNo = ($data->currentPage() - 1) * $data->perPage() + 1;

        $items = $data->getCollection()->map(function ($item) use (&$startNo) {
            $item->no = $startNo++;
            return $item;
        });

        $data->setCollection($items);

        return response()->json($data);
    }

    // ========================== AMBIL DATA GURU DENGAN PAGINASI ==========================
    public function index_teacher(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = User::with('teacher')
            ->whereHas('roles', function ($q) {
                $q->where('id', 4);
            })
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate($per);

        // nomor urut manual
        $startNo = ($data->currentPage() - 1) * $data->perPage() + 1;

        $items = $data->getCollection()->map(function ($item) use (&$startNo) {
            $item->no = $startNo++;
            return $item;
        });

        $data->setCollection($items);

        return response()->json($data);
    }

    // ========================== AMBIL DATA SISWA DENGAN PAGINASI ==========================
    public function index_student(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = User::with('student')
            ->whereHas('roles', function ($q) {
                $q->where('id', 3);
            })
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate($per);

        // nomor urut manual
        $startNo = ($data->currentPage() - 1) * $data->perPage() + 1;

        $items = $data->getCollection()->map(function ($item) use (&$startNo) {
            $item->no = $startNo++;
            return $item;
        });

        $data->setCollection($items);

        return response()->json($data);
    }

    public function print($id)
    {
        try {
            // Ambil user berdasarkan ID
            $user = User::findOrFail($id);

            // Data tambahan jika kamu punya kolom lain
            // (misalnya kelas, jurusan, atau foto)
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nisn' => $user->nisn ?? null,
                'kelas' => $user->kelas ?? null,
                'jurusan' => $user->jurusan ?? null,
                'photo' => $user->photo ?? null,
                'created_at' => $user->created_at,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User tidak ditemukan.',
                'error' => $e->getMessage(),
            ], 404);
        }
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

    // ========================== AMBIL DATA UUID ==========================
    public function pprint($uuid)
    {
        // Ambil data user berdasarkan UUID
        $user = \App\Models\User::with([
            'siswa.kelas.jurusan', // pastikan relasi ini sesuai dengan model kamu
        ])->where('uuid', $uuid)->firstOrFail();

        // Ambil data siswa terkait (jika ada)
        $siswa = $user->siswa;
        $kelas = $siswa->kelas ?? null;
        $jurusan = $kelas->jurusan ?? null;

        // Ambil data setting sekolah
        $setting = \App\Models\Setting::first();

        // Nama & logo sekolah
        $schoolName = $setting->school ?? 'Nama Sekolah';
        $schoolLogo = $setting->logo_sekolah
            ? asset('storage/' . $setting->logo_sekolah)
            : asset('default-logo.png');

        // Ambil tanggal sekarang (untuk ditampilkan di cetakan)
        $tanggalCetak = now()->translatedFormat('d F Y');

        // Kirim semua data ke view cetak
        return view('master.users.print', [
            'user'         => $user,
            'siswa'        => $siswa,
            'kelas'        => $kelas,
            'jurusan'      => $jurusan,
            'schoolName'   => $schoolName,
            'schoolLogo'   => $schoolLogo,
            'tanggalCetak' => $tanggalCetak,
        ]);
    }
}
