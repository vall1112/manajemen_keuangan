<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    // ========================== AMBIL SEMUA DATA CLASSROOM (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Classroom::all()
        ]);
    }

    // ========================== AMBIL DATA CLASSROOM DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Classroom::with([
            'teacher',
            'major:id,nama_jurusan,kode'
        ])
            ->when($request->search, function (Builder $query, string $search) {
                $query->where('nama_kelas', 'like', "%$search%")
                    ->orWhereHas('major', function (Builder $q) use ($search) {
                        $q->where('nama_jurusan', 'like', "%$search%")
                            ->orWhere('kode', 'like', "%$search%");
                    });
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA CLASSROOM BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelas'   => 'required|string|max:255',
            'major_id'     => 'required',
            'wali_kelas_id' => 'required|exists:teachers,id',
            'jumlah_anak'  => 'required|integer|min:0',
        ]);

        $classroom = Classroom::create($validatedData);

        return response()->json([
            'success'   => true,
            'classroom' => $classroom
        ]);
    }

    // ========================== TAMPILKAN DETAIL CLASSROOM ==========================
    public function show(Classroom $classroom)
    {
        return response()->json([
            'classroom' => $classroom
        ]);
    }

    // ========================== UPDATE DATA CLASSROOM ==========================
    public function update(Request $request, Classroom $classroom)
    {
        $validatedData = $request->validate([
            'nama_kelas'   => 'required|string|max:255',
            'major_id'      => 'required',
            'wali_kelas_id' => 'required|exists:teachers,id',
            'jumlah_anak'  => 'required|integer|min:0',
        ]);

        $classroom->update($validatedData);

        return response()->json([
            'success'   => true,
            'classroom' => $classroom
        ]);
    }

    // ========================== HAPUS DATA CLASSROOM ==========================
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
