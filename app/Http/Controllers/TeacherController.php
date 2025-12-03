<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    // ========================== AMBIL SEMUA DATA TEACHER (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Teacher::all()
        ]);
    }

    // ========================== AMBIL DATA TEACHER DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Teacher::with('user')
            ->when($request->search, function (Builder $query, string $search) {
                $query->where('nama', 'like', "%$search%")
                    ->orWhere('nip', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        // Tambahkan username di setiap item
        $data->getCollection()->transform(function ($item) {
            $item->username = $item->user->username ?? '-';
            return $item;
        });

        return response()->json($data);
    }

    // ========================== SIMPAN DATA TEACHER BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nip' => 'nullable|string|unique:teachers,nip',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:teachers,email',
            'alamat' => 'required|string',
            'level' => 'required|string',
            'mata_pelajaran' => 'nullable|string',
            'status' => 'in:Aktif,Tidak Aktif,Cuti',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        $teacher = Teacher::create($validatedData);

        return response()->json([
            'success' => true,
            'teacher' => $teacher
        ]);
    }

    // ========================== TAMPILKAN DETAIL TEACHER ==========================
    public function show(Teacher $teacher)
    {
        $user = User::where('teacher_id', $teacher->id)->first();

        return response()->json([
            'teacher' => $teacher,
            'user' => $user, // bisa null
            'has_user' => $user ? true : false
        ]);
    }

    // ========================== UPDATE DATA TEACHER ==========================
    public function update(Request $request, Teacher $teacher)
    {
        $validatedData = $request->validate([
            'nip' => 'nullable|string|unique:teachers,nip,' . $teacher->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'alamat' => 'required|string',
            'level' => 'required|string',
            'mata_pelajaran' => 'nullable|string',
            'status' => 'in:Aktif,Tidak Aktif,Cuti',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($teacher->foto) {
                Storage::disk('public')->delete($teacher->foto);
            }
            $validatedData['foto'] = $request->file('foto')->store('teachers', 'public');
        }

        $teacher->update($validatedData);

        return response()->json([
            'success' => true,
            'teacher' => $teacher
        ]);
    }

    // ========================== HAPUS DATA TEACHER ==========================
    public function destroy(Teacher $teacher)
    {
        if ($teacher->foto) {
            Storage::disk('public')->delete($teacher->foto);
        }

        $teacher->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // ========================== MENGAMBIL DATA USER GURU ==========================
    public function getUser($id)
    {
        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan.'
                ], 404);
            }

            $user = User::where('teacher_id', $teacher->id)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User untuk guru ini tidak ditemukan.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'teacher' => $teacher,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByTeacher($teacher_id)
    {
        $user = User::where('teacher_id', $teacher_id)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }
}
