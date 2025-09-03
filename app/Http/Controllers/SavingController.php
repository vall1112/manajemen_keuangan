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

        $lastSaving = Saving::where('student_id', $validatedData['student_id'])
            ->latest('created_at')
            ->first();

        $lastSaldo = $lastSaving ? $lastSaving->saldo : 0;

        $validatedData['saldo'] = $lastSaldo + $validatedData['nominal'];
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

        $validatedData['jenis'] = 'Tarik';

        $saving = Saving::create($validatedData);

        return response()->json([
            'success' => true,
            'saving'  => $saving
        ]);
    }
}
