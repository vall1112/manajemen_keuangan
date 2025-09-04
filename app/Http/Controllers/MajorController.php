<?php

namespace App\Http\Controllers;

use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class MajorController extends Controller
{
    // ========================== AMBIL SEMUA DATA MAJOR (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Major::all()
        ]);
    }

    // ========================== AMBIL DATA MAJOR DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Major::when($request->search, function (Builder $query, string $search) {
                $query->where('kode', 'like', "%$search%")
                      ->orWhere('nama_jurusan', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA MAJOR BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode'              => 'required|string|max:10|unique:majors,kode',
            'nama_jurusan'      => 'required|string|max:255',
            'keterangan'        => 'nullable|string',
        ]);

        $major = Major::create($validatedData);

        return response()->json([
            'success' => true,
            'major'   => $major
        ]);
    }

    // ========================== TAMPILKAN DETAIL MAJOR ==========================
    public function show(Major $major)
    {
        return response()->json([
            'major' => $major
        ]);
    }

    // ========================== UPDATE DATA MAJOR ==========================
    public function update(Request $request, Major $major)
    {
        $validatedData = $request->validate([
            'kode'          => 'required|string|max:10|unique:majors,kode,' . $major->id,
            'nama_jurusan'  => 'required|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        $major->update($validatedData);

        return response()->json([
            'success' => true,
            'major'   => $major
        ]);
    }

    // ========================== HAPUS DATA MAJOR ==========================
    public function destroy(Major $major)
    {
        $major->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
