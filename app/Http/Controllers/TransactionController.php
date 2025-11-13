<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\SavingBalance;
use App\Models\Saving;
use App\Models\Bill;
use App\Models\Setting;
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
            'bill.student',
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
                'kode' => $item->kode,
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

            $bill = Bill::with('student', 'paymentType')->findOrFail($validatedData['bill_id']);
            $student = $bill->student;

            if ($validatedData['metode_pembayaran'] === 'Pembayaran melalui tabungan') {
                $savingBalance = SavingBalance::firstOrNew([
                    'student_id' => $student->id
                ]);

                $lastSaldo = $savingBalance->saldo ?? 0;

                if ($validatedData['nominal'] > $lastSaldo) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Saldo tabungan anda tidak cukup untuk melakukan pembayaran.',
                    ], 422);
                }
            }

            $transaction = Transaction::create($validatedData);

            if ($validatedData['status'] === 'Berhasil') {
                $bill->update(['status' => 'Lunas']);
            }

            if ($validatedData['metode_pembayaran'] === 'Pembayaran melalui tabungan') {
                $savingBalance->saldo = $lastSaldo - $validatedData['nominal'];
                $savingBalance->save();

                Saving::create([
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
            'bill_id'            => 'required|exists:bills,id',
            'nominal'            => 'required|numeric|min:0',
            'metode'             => 'required|string|max:50',
            'status'             => 'in:Pending,Berhasil,Gagal',
            'metode_pembayaran'  => 'in:Pembayaran melalui tabungan',
            'catatan'            => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $transaction->update($validatedData);

            $bill = Bill::with(['student', 'paymentType'])->findOrFail($transaction->bill_id);
            $student = $bill->student;

            if ($validatedData['status'] === 'Berhasil') {
                $bill->update(['status' => 'Lunas']);

                $savingBalance = SavingBalance::firstOrNew([
                    'student_id' => $student->id
                ]);

                $lastSaldo = $savingBalance->saldo ?? 0;

                $savingBalance->saldo = $lastSaldo - $validatedData['nominal'];
                $savingBalance->save();

                Saving::create([
                    'user_id'    => auth()->id(),
                    'student_id' => $student->id,
                    'nominal'    => $validatedData['nominal'],
                    'jenis'      => 'Tarik',
                    'keterangan' => 'Pembayaran ' . ($bill->paymentType->nama_jenis ?? ''),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui',
                'transaction' => $transaction->load('bill')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
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
            'bill.student.classroom.major',
            'bill.paymentType',
            'bill.schoolYear',
        ]);

        $bill = $transaction->bill;
        $student = $bill->student;
        $classroom = $student->classroom;
        $major = $classroom->major ?? null;
        $paymentType = $bill->paymentType;
        $schoolYear = $bill->schoolYear;

        $setting = Setting::first();

        $schoolName = $setting->school ?? 'Nama Sekolah';
        $schoolLogo = $setting->logo_sekolah
            ? asset('storage/' . $setting->logo_sekolah)
            : asset('default-logo.png');

        $unpaidBills = Bill::where('student_id', $student->id)
            ->where('status', 'Belum Dibayar')
            ->get();

        $tanggalBayar = $transaction->created_at;
        $kode = $transaction->kode;

        return view('transactions.receipt', [
            'transaction'   => $transaction,
            'bill'          => $bill,
            'student'       => $student,
            'classroom'     => $classroom,
            'major'         => $major,
            'paymentType'   => $paymentType,
            'schoolYear'    => $schoolYear,
            'setting'       => $setting,
            'schoolName'    => $schoolName,
            'schoolLogo'    => $schoolLogo,
            'unpaidBills'   => $unpaidBills,
            'tanggalBayar'  => $tanggalBayar,
            'kode'          => $kode,
        ]);
    }

    // ========================== MENGAMBIL SALDO TABUNGAN PER SISWA ==========================
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
            'balance' => (int) $balance->balance,
        ]);
    }

    // ========================== SIMPAN DATA TRANSACTION BARU YANG DIBUAT OLEH SISWA ==========================
    public function storeByStudent(Request $request)
    {
        $validatedData = $request->validate([
            'bill_id'   => 'required|exists:bills,id',
            'nominal'   => 'required|numeric|min:0',
            'catatan'   => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validatedData['user_id'] = auth()->id();
            $validatedData['status'] = 'Pending';
            $validatedData['metode_pembayaran'] = 'Pembayaran melalui tabungan';

            $bill = \App\Models\Bill::with('student', 'paymentType')->findOrFail($validatedData['bill_id']);
            $student = $bill->student;

            if ($bill->status === 'Lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tagihan ini sudah lunas.',
                ], 422);
            }

            $savingBalance = SavingBalance::firstOrNew([
                'student_id' => $student->id
            ]);

            $lastSaldo = $savingBalance->saldo ?? 0;

            if ($validatedData['nominal'] > $lastSaldo) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo tabungan anda tidak cukup untuk melakukan pembayaran.',
                ], 422);
            }

            $transaction = Transaction::create($validatedData);

            $bill->update(['status' => 'Pending']);

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Transaksi berhasil dibuat dan menunggu konfirmasi bendahara.',
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

    // ========================== AMBIL DATA TRANSACTION STATUS PENDING DENGAN PAGINASI ==========================
    public function indexPending(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Transaction::with([
            'bill.student',
            'bill.paymentType',
        ])
            ->where('status', 'Pending')
            ->when($request->search, function ($query, $search) {
                $query->where('kode', 'like', "%$search%")
                    ->orWhere('nominal', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhereHas('bill', function ($q) use ($search) {
                        $q->where('kode', 'like', "%$search%")
                            ->orWhereHas('student', function ($q2) use ($search) {
                                $q2->where('nama', 'like', "%$search%");
                            })
                            ->orWhereHas('paymentType', function ($q3) use ($search) {
                                $q3->where('nama_jenis', 'like', "%$search%");
                            });
                    });
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        $data->getCollection()->transform(function ($item) {
            return [
                'no'                => $item->no,
                'id'                => $item->id,
                'kode'              => $item->kode,
                'bill_id'           => $item->bill_id,
                'student_name'      => $item->bill->student->nama ?? null,
                'payment_type_name' => $item->bill->paymentType->nama_jenis ?? null,
                'nominal'           => $item->nominal,
                'status'            => $item->status,
                'catatan'           => $item->catatan,
                'created_at'        => $item->created_at,
            ];
        });
        return $data;
    }
}
