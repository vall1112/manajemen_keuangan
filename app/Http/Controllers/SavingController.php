<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    // ========================== AMBIL SEMUA DATA STUDENT (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Saving::all()
        ]);
    }

    // ========================== SIMPAN DATA STUDENT BARU ==========================
    public function storeDeposit(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'tanggal'    => 'required|date',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $validatedData['jenis'] = 'Setor';

        $saving = Saving::create($validatedData);

        return response()->json([
            'success' => true,
            'saving'  => $saving
        ]);
    }
public function storePull(Request $request)
{
    $validatedData = $request->validate([
        'student_id' => 'required|exists:students,id',
        'tanggal'    => 'required|date',
        'nominal'    => 'required|numeric|min:1',
        'keterangan' => 'nullable|string|max:255',
    ]);

    // Ambil saldo terakhir siswa
    $lastSaldo = Saving::where('student_id', $request->student_id)
        ->orderBy('id', 'desc')
        ->value('saldo') ?? 0;

    // Cek cukup/tidak
    if ($lastSaldo < $request->nominal) {
        return response()->json([
            'success' => false,
            'message' => 'Saldo tidak mencukupi untuk penarikan.'
        ], 422);
    }

    // Hitung saldo baru
    $newSaldo = $lastSaldo - $request->nominal;

    // Simpan transaksi
    $saving = Saving::create([
        'student_id' => $request->student_id,
        'tanggal'    => $request->tanggal,
        'nominal'    => $request->nominal,
        'jenis'      => 'Tarik',
        'saldo'      => $newSaldo,
        'keterangan' => $request->keterangan,
    ]);

    return response()->json([
        'success' => true,
        'saving'  => $saving
    ]);
}
}
