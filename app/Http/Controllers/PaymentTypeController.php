<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentTypeController extends Controller
{
    // ========================== AMBIL SEMUA DATA PAYMENT TYPE (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => PaymentType::all()
        ]);
    }

    // ========================== AMBIL DATA PAYMENT YPE DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);
        $data = PaymentType::when($request->search, function (Builder $query, string $search) {
            $query->where('nama_jenis', 'like', "%$search%")
                ->orWhere('keterangan', 'like', "%$search%");
        })->latest()->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA PAYMENT TYPE BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:payment_types,nama_jenis',
            'kode'       => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $paymentType = PaymentType::create($validatedData);

        return response()->json([
            'success' => true,
            'payment_type' => $paymentType
        ]);
    }

    // ========================== TAMPILKAN DETAIL PAYMENT TYPE ==========================
    public function show(PaymentType $paymentType)
    {
        return response()->json([
            'payment_type' => $paymentType
        ]);
    }

    // ========================== UPDATE DATA PAYMENT TYPE ==========================
    public function update(Request $request, PaymentType $paymentType)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:payment_types,nama_jenis,' . $paymentType->id,
            'kode'       => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $paymentType->update($validatedData);

        return response()->json([
            'success' => true,
            'payment_type' => $paymentType
        ]);
    }

    // ========================== HAPUS DATA PAYMENT TYPE ==========================
    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
