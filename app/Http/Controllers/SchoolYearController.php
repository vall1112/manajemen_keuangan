<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SchoolYearController extends Controller
{
    // ========================== AMBIL SEMUA DATA SCHOOL YEAR (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => SchoolYear::all()
        ]);
    }

    // ========================== AMBIL DATA SCHOOL YEAR DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = SchoolYear::when($request->search, function (Builder $query, string $search) {
            $query->where('tahun_ajaran', 'like', "%$search%")
                ->orWhere('semester', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA SCHOOL YEAR BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester'     => 'required|in:Ganjil,Genap',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $schoolYear = SchoolYear::create($validatedData);

        return response()->json([
            'success' => true,
            'school_year' => $schoolYear
        ]);
    }

    // ========================== TAMPILKAN DETAIL SCHOOL YEAR ==========================
    public function show(SchoolYear $schoolYear)
    {
        return response()->json([
            'school_year' => $schoolYear
        ]);
    }

    // ========================== UPDATE DATA SCHOOL YEAR ==========================
    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validatedData = $request->validate([
            'tahun_ajaran' => 'required|string|max:20',
            'semester'     => 'required|in:Ganjil,Genap',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $schoolYear->update($validatedData);

        return response()->json([
            'success' => true,
            'school_year' => $schoolYear
        ]);
    }

    // ========================== HAPUS DATA SCHOOL YEAR ==========================
    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return response()->json([
            'success' => true
        ]);
    }

    // ========================== UBAH STATUS SECARA MANUAL ==========================
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Aktif,Tidak Aktif',
        ]);

        $schoolYear = SchoolYear::find($id);
        if (!$schoolYear) {
            return response()->json([
                'message' => 'Tahun ajaran tidak ditemukan'
            ], 404);
        }

        $schoolYear->status = $validated['status'];
        $schoolYear->save();

        $message = match ($validated['status']) {
            'Aktif'       => 'Tahun ajaran telah diaktifkan',
            'Tidak Aktif' => 'Tahun ajaran telah dinonaktifkan',
            default       => 'Status tahun ajaran berhasil diperbarui',
        };

        return response()->json([
            'message' => $message,
            'schoolYear' => $schoolYear
        ], 200);
    }
}
