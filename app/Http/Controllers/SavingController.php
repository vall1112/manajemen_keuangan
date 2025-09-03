<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingController extends Controller
{
    /**
     * Ambil semua data tabungan (tanpa pagination).
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Saving::with(['student'])->get()
        ]);
    }

    /**
     * Ambil data tabungan dengan pagination.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = Saving::with(['student'])
            ->when($request->search, function (Builder $query, string $search) {
                $query->whereHas('student', function ($q) use ($search) {
                        $q->where('nama', 'like', "%$search%");
                    })
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('keterangan', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Simpan data tabungan baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'siswa_id'      => 'required|exists:students,id',
            'total'         => 'required|numeric|min:0',
            'tanggal_setor' => 'required|date',
            'status'        => 'required|in:Belum Disetor,Disetor',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $saving = Saving::create($validatedData);

        return response()->json([
            'success' => true,
            'saving' => $saving->load(['student'])
        ]);
    }

    /**
     * Tampilkan detail tabungan.
     */
    public function show(Saving $saving)
    {
        return response()->json([
            'saving' => $saving->load(['student'])
        ]);
    }

    /**
     * Update data tabungan.
     */
    public function update(Request $request, Saving $saving)
    {
        $validatedData = $request->validate([
            'siswa_id'      => 'required|exists:students,id',
            'total'         => 'required|numeric|min:0',
            'tanggal_setor' => 'required|date',
            'status'        => 'required|in:Belum Disetor,Disetor',
            'keterangan'    => 'nullable|string|max:255',
        ]);

        $saving->update($validatedData);

        return response()->json([
            'success' => true,
            'saving' => $saving->load(['student'])
        ]);
    }

    /**
     * Hapus data tabungan.
     */
    public function destroy(Saving $saving)
    {
        $saving->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
