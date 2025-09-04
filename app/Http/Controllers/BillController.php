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
            'student_id' => 'required|exists:students,id',
            'payment_type_id' => [
                'required',
                'exists:payment_types,id',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = \App\Models\Bill::where('student_id', $request->student_id)
                        ->where('payment_type_id', $value)
                        ->exists();
                    if ($exists) {
                        $fail('Siswa sudah memiliki jenis pembayaran ini.');
                    }
                },
            ],
            'school_year_id' => 'required|exists:school_years,id',
            'total' => 'required|numeric|min:0',
            'tanggal_tagih' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $validatedData['status'] = 'Belum Dibayar';

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
            'id' => $bill->id,
            'student_name' => $bill->student ? $bill->student->nama : '',
            'payment_type_name' => $bill->paymentType ? $bill->paymentType->nama_jenis : '',
            'school_year' => $bill->schoolYear ? $bill->schoolYear->tahun_ajaran : '',
            'total' => $bill->total,
            'status' => $bill->status,
        ]);
    }

    // ========================== UPDATE DATA BILL ==========================
    public function update(Request $request, Bill $bill)
    {
        $validatedData = $request->validate([
            'student_id'            => 'required|exists:students,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'school_year_id'     => 'required|exists:school_years,id',
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
