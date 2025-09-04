<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    // ========================== AMBIL SEMUA DATA TRANSACTION (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Transaction::with('bill')->get()
        ]);
    }

    // ========================== AMBIL DATA TRANSACTION DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Transaction::with([
            'bill.student', // ambil nama siswa
        ])
            ->when($request->search, function ($query, $search) {
                $query->where('nominal', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhereHas('bill', function ($q) use ($search) {
                        $q->where('kode', 'like', "%$search%")
                            ->orWhereHas('student', function ($q2) use ($search) {
                                $q2->where('nama', 'like', "%$search%");
                            });
                    });
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->getCollection()->transform(function ($item) {
            return [
                'no' => $item->no,
                'id' => $item->id,
                'bill_id' => $item->bill_id,
                'kode_tagihan' => $item->bill->kode ?? null,
                'student_name' => $item->bill->student->nama ?? null,
                'nominal' => $item->nominal,
                'status' => $item->status,
                'catatan' => $item->catatan,
                'created_at' => $item->created_at,
            ];
        });

        return response()->json($data);
    }

    // ========================== SIMPAN DATA TRANSACTION BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'nominal' => 'required|numeric|min:0',
            'status' => 'in:Pending,Berhasil,Gagal',
            'catatan' => 'nullable|string',
        ]);

        $transaction = Transaction::create($validatedData);

        if ($validatedData['status'] === 'Berhasil') {
            $transaction->bill()->update(['status' => 'Lunas']);
        }

        return response()->json([
            'success' => true,
            'transaction' => $transaction->load('bill')
        ]);
    }

    // ========================== TAMPILKAN DETAIL TRANSACTION ==========================

    public function show(Transaction $transaction)
    {
        return response()->json([
            'transaction' => $transaction->load('bill')
        ]);
    }

    // ========================== UPDATE DATA TRANSACTION ==========================
    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'nominal' => 'required|numeric|min:0',
            'metode' => 'required|string|max:50',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'in:Pending,Berhasil,Gagal',
            'catatan' => 'nullable|string',
        ]);

        if ($request->hasFile('bukti')) {
            if ($transaction->bukti) {
                Storage::disk('public')->delete($transaction->bukti);
            }
            $validatedData['bukti'] = $request->file('bukti')->store('transactions', 'public');
        }

        $transaction->update($validatedData);

        return response()->json([
            'success' => true,
            'transaction' => $transaction->load('bill')
        ]);
    }

    // ========================== HAPUS DATA TRANSACTION ==========================
    public function destroy(Transaction $transaction)
    {
        if ($transaction->bukti) {
            Storage::disk('public')->delete($transaction->bukti);
        }

        $transaction->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
