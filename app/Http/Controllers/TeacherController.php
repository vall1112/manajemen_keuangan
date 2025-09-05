<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
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
        $data = Teacher::when($request->search, function (Builder $query, string $search) {
            $query->where('nama', 'like', "%$search%")
                ->orWhere('nip', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

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
            'jabatan' => 'required|string',
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
        return response()->json([
            'teacher' => $teacher
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
            'jabatan' => 'required|string',
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
}
