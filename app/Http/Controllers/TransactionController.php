<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Ambil semua data transaksi.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Transaction::with('bill')->get()
        ]);
    }

    /**
     * Ambil data transaksi dengan pagination + pencarian.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Transaction::with('bill')
            ->when($request->search, function (Builder $query, string $search) {
                $query->where('metode', 'like', "%$search%")
                      ->orWhere('status', 'like', "%$search%")
                      ->orWhere('keterangan', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tagihan_id' => 'required|exists:bills,id',
            'nominal' => 'required|numeric|min:0',
            'metode' => 'required|string|max:50',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'in:Pending,Berhasil,Gagal',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('bukti')) {
            $validatedData['bukti'] = $request->file('bukti')->store('transactions', 'public');
        }

        $transaction = Transaction::create($validatedData);

        return response()->json([
            'success' => true,
            'transaction' => $transaction->load('bill')
        ]);
    }

    /**
     * Tampilkan detail transaksi.
     */
    public function show(Transaction $transaction)
    {
        return response()->json([
            'transaction' => $transaction->load('bill')
        ]);
    }

    /**
     * Update transaksi.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'tagihan_id' => 'required|exists:bills,id',
            'nominal' => 'required|numeric|min:0',
            'metode' => 'required|string|max:50',
            'bukti' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'in:Pending,Berhasil,Gagal',
            'keterangan' => 'nullable|string',
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

    /**
     * Hapus transaksi.
     */
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
