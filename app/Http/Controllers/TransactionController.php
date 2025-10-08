<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\SavingBalance;
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
                $query->where('kode', 'like', "%$search%")
                    ->orWhere('nominal', 'like', "%$search%")
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
                'kode' => $item->kode, // kode transaksi
                'bill_id' => $item->bill_id,
                'student_name' => $item->bill->student->nama ?? null,
                'nominal' => $item->nominal,
                'status' => $item->status,
                'catatan' => $item->catatan,
                'created_at' => $item->created_at,
            ];
        });

        return $data;
    }

    // ========================== SIMPAN DATA TRANSACTION BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'bill_id'            => 'required|exists:bills,id',
            'nominal'            => 'required|numeric|min:0',
            'status'             => 'in:Pending,Berhasil,Gagal',
            'metode_pembayaran'  => 'in:Pembayaran melalui tabungan,Pembayaran melalui uang cash',
            'catatan'            => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['user_id'] = auth()->id();

            $bill = \App\Models\Bill::with('student', 'paymentType')->findOrFail($validatedData['bill_id']);
            $student = $bill->student;

            // === Validasi tambahan: nominal tidak boleh melebihi saldo tabungan ===
            if ($validatedData['metode_pembayaran'] === 'Pembayaran melalui tabungan') {
                $savingBalance = \App\Models\SavingBalance::firstOrNew([
                    'student_id' => $student->id
                ]);

                $lastSaldo = $savingBalance->saldo ?? 0;

                if ($validatedData['nominal'] > $lastSaldo) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Nominal pembayaran tidak boleh melebihi saldo tabungan.',
                    ], 422);
                }
            }

            // Simpan transaksi
            $transaction = \App\Models\Transaction::create($validatedData);

            // Jika pembayaran berhasil, ubah status tagihan
            if ($validatedData['status'] === 'Berhasil') {
                $bill->update(['status' => 'Lunas']);
            }

            // === Proses potong saldo tabungan ===
            if ($validatedData['metode_pembayaran'] === 'Pembayaran melalui tabungan') {
                $savingBalance->saldo = $lastSaldo - $validatedData['nominal'];
                $savingBalance->save();

                // Simpan riwayat ke tabel savings
                \App\Models\Saving::create([
                    'user_id'    => auth()->id(),
                    'student_id' => $student->id,
                    'nominal'    => $validatedData['nominal'],
                    'jenis'      => 'Tarik',
                    'keterangan' => 'Pembayaran ' . ($bill->paymentType->nama_jenis ?? ''),
                ]);
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Transaksi berhasil disimpan',
                'transaction'  => $transaction->load('bill', 'user'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
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
            'metode_pembayaran' => 'in:Pembayaran melalui tabungan,Pembayaran melalui uang cash',
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

    // ========================== MEMBUAT KUITANSI ==========================
    public function receipt(Transaction $transaction)
    {
        $transaction->load([
            'bill.student.classroom',
            'bill.paymentType',
            'bill.schoolYear'
        ]);

        $student = $transaction->bill->student;

        // Ambil tagihan yang belum dibayar
        $unpaidBills = \App\Models\Bill::where('student_id', $student->id)
            ->where('status', 'Belum Dibayar')
            ->get();

        return view('transactions.receipt', [
            'student' => $student,
            'transaction' => $transaction,
            'unpaidBills' => $unpaidBills
        ]);
    }

    public function getSavingBalance($student_id)
    {
        $balance = SavingBalance::where('student_id', $student_id)->first();

        if (!$balance) {
            return response()->json([
                'message' => 'Saldo tabungan tidak ditemukan',
                'balance' => 0,
            ], 404);
        }

        return response()->json([
            'student_id' => $student_id,
            'balance' => (int) $balance->balance, // misal kolomnya 'balance'
        ]);
    }
}
