<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    // ========================== AMBIL SEMUA DATA BILL (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Bill::with(['student', 'paymentType', 'schoolYear'])->get()
        ]);
    }

    // ========================== AMBIL DATA BILL DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Bill::with(['student', 'paymentType', 'schoolYear'])
            ->when($request->search, function (Builder $query, string $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                })
                    ->orWhereHas('paymentType', function ($q) use ($search) {
                        $q->where('nama_jenis', 'like', "%$search%");
                    })
                    ->orWhereHas('schoolYear', function ($q) use ($search) {
                        $q->where('tahun_ajaran', 'like', "%$search%");
                    })
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhere('keterangan', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA BILL BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'siswa_id'            => 'required|exists:students,id',
            'jenis_pembayaran_id' => 'required|exists:payment_types,id',
            'tahun_ajaran_id'     => 'required|exists:school_years,id',
            'total'               => 'required|numeric|min:0',
            'tanggal_tagih'       => 'required|date',
            'keterangan'          => 'nullable|string|max:255',
        ]);

        $bill = Bill::create($validatedData);

        return response()->json([
            'success' => true,
            'bill' => $bill->load(['student', 'paymentType', 'schoolYear'])
        ]);
    }

    // ========================== TAMPILKAN DETAIL BILL ==========================
    public function show(Bill $bill)
    {
        return response()->json([
            'bill' => $bill->load(['student', 'paymentType', 'schoolYear'])
        ]);
    }

    // ========================== UPDATE DATA BILL ==========================
    public function update(Request $request, Bill $bill)
    {
        $validatedData = $request->validate([
            'siswa_id'            => 'required|exists:students,id',
            'jenis_pembayaran_id' => 'required|exists:payment_types,id',
            'tahun_ajaran_id'     => 'required|exists:school_years,id',
            'total'               => 'required|numeric|min:0',
            'tanggal_tagih'       => 'required|date',
            'keterangan'          => 'nullable|string|max:255',
        ]);

        $bill->update($validatedData);

        return response()->json([
            'success' => true,
            'bill' => $bill->load(['student', 'paymentType', 'schoolYear'])
        ]);
    }

    // ========================== HAPUS DATA BILL ==========================
    public function destroy(Bill $bill)
    {
        $bill->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
