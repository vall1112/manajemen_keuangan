<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    /**
     * Ambil semua data bill (tanpa pagination).
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Bill::with(['student', 'paymentType', 'schoolYear'])->get()
        ]);
    }

    /**
     * Ambil data bill dengan pagination.
     */
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

    /**
     * Simpan data bill baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'siswa_id'            => 'required|exists:students,id',
            'jenis_pembayaran_id' => 'required|exists:payment_types,id',
            'school_year_id'      => 'required|exists:school_years,id',
            'total'               => 'required|numeric|min:0',
            'tanggal_tagih'       => 'required|date',
            'status'              => 'required|in:Belum Dibayar,Dibayar Sebagian,Lunas',
            'dibayar'             => 'nullable|numeric|min:0',
            'sisa'                => 'nullable|numeric|min:0',
            'keterangan'          => 'nullable|string|max:255',
        ]);

        $bill = Bill::create($validatedData);

        return response()->json([
            'success' => true,
            'bill' => $bill->load(['student', 'paymentType', 'schoolYear'])
        ]);
    }

    /**
     * Tampilkan detail bill.
     */
    public function show(Bill $bill)
    {
        return response()->json([
            'bill' => $bill->load(['student', 'paymentType', 'schoolYear'])
        ]);
    }

    /**
     * Update data bill.
     */
    public function update(Request $request, Bill $bill)
    {
        $validatedData = $request->validate([
            'siswa_id'            => 'required|exists:students,id',
            'jenis_pembayaran_id' => 'required|exists:payment_types,id',
            'school_year_id'      => 'required|exists:school_years,id',
            'total'               => 'required|numeric|min:0',
            'tanggal_tagih'       => 'required|date',
            'status'              => 'required|in:Belum Dibayar,Dibayar Sebagian,Lunas',
            'dibayar'             => 'nullable|numeric|min:0',
            'sisa'                => 'nullable|numeric|min:0',
            'keterangan'          => 'nullable|string|max:255',
        ]);

        $bill->update($validatedData);

        return response()->json([
            'success' => true,
            'bill' => $bill->load(['student', 'paymentType', 'schoolYear'])
        ]);
    }

    /**
     * Hapus data bill.
     */
    public function destroy(Bill $bill)
    {
        $bill->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
