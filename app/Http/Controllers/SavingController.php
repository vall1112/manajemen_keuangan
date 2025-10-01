<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingController extends Controller
{
    // ========================== AMBIL SEMUA DATA TABUNGAN (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Saving::all()
        ]);
    }

    // ========================== AMBIL SEMUA DATA TABUNGAN DENGAN PAGINASI) ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Saving::with('student') // relasi ke student
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                })
                    ->orWhere('jenis', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA SETOR TABUNGAN ==========================
    public function storeDeposit(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'nominal'    => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // ambil saldo terakhir dari tabel savings (riwayat transaksi)
        $lastSaving = Saving::where('student_id', $validatedData['student_id'])
            ->latest('created_at')
            ->first();

        $lastSaldo = $lastSaving ? $lastSaving->saldo : 0;

        $validatedData['saldo'] = $lastSaldo + $validatedData['nominal'];
        $validatedData['jenis'] = 'Setor';
        $validatedData['user_id'] = auth()->id();

        // simpan transaksi baru di tabel savings
        $saving = Saving::create($validatedData);

        // update/insert saldo di saving_balances
        \App\Models\SavingBalance::updateOrCreate(
            ['student_id' => $validatedData['student_id']], // cari berdasarkan student_id
            ['saldo' => $validatedData['saldo']] // update saldo terbaru
        );

        return response()->json([
            'success' => true,
            'saving'  => $saving->load('student', 'user'),
        ]);
    }

    // ========================== SIMPAN DATA TARIK TABUNGAN ==========================
    public function storePull(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|exists:students,id',
                'nominal'    => 'required|numeric|min:1',
                'keterangan' => 'nullable|string|max:255',
            ]);

            // ambil saldo dari tabel saving_balances
            $savingBalance = \App\Models\SavingBalance::where('student_id', $request->student_id)->first();

            $lastSaldo = $savingBalance ? $savingBalance->saldo : 0;

            if ($lastSaldo < $request->nominal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saldo anda tersisa ' . number_format($lastSaldo, 0, ',', '.'),
                    'errors'  => [
                        'nominal' => ['Saldo anda tersisa ' . number_format($lastSaldo, 0, ',', '.')]
                    ]
                ], 422);
            }

            $newSaldo = $lastSaldo - $request->nominal;

            // simpan transaksi riwayat di tabel savings
            $saving = \App\Models\Saving::create([
                'student_id' => $request->student_id,
                'nominal'    => $request->nominal,
                'jenis'      => 'Tarik',
                'saldo'      => $newSaldo,
                'keterangan' => $request->keterangan,
                'user_id'    => auth()->id(),
            ]);

            // update saldo di tabel saving_balances
            if ($savingBalance) {
                $savingBalance->update(['saldo' => $newSaldo]);
            } else {
                // kalau belum ada, buat baru
                \App\Models\SavingBalance::create([
                    'student_id' => $request->student_id,
                    'saldo' => $newSaldo,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Penarikan berhasil. Sisa saldo anda: ' . number_format($newSaldo, 0, ',', '.'),
                'saving'  => $saving->load('student', 'user')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors()
            ], 422);
        }
    }

    // ========================== MENGAMBIL DATA SALDO PER SISWA ==========================
    public function getBalance(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        // ambil saving + relasi student + classroom
        $allData = Saving::with('student.classroom')
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                })
                    ->orWhere('jenis', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('student_id')   // hanya ambil terbaru per student_id
            ->values();              // reset index collection

        $data = $allData->forPage($page + 1, $per)->values();

        $data = $data->map(function ($item) {
            $item->no = DB::selectOne('select @no := @no + 1 as no')->no;
            $item->nama_siswa = $item->student->nama ?? null;
            $item->nama_kelas = $item->student->classroom->nama_kelas ?? null;
            return $item;
        });

        return response()->json([
            'data' => $data,
            'total' => $allData->count(),
            'per_page' => $per,
            'current_page' => $page + 1,
            'last_page' => ceil($allData->count() / $per),
        ]);
    }

    public function getBalanceStudent(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        // Ambil student_id dari user yang login
        $studentId = auth()->user()->student_id;

        // ambil saving + relasi student + classroom
        $allData = Saving::with('student.classroom')
            ->where('student_id', $studentId) // hanya ambil data milik student login
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                })
                    ->orWhere('jenis', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('student_id')   // hanya ambil terbaru per student_id
            ->values();              // reset index collection

        $data = $allData->forPage($page + 1, $per)->values();

        $data = $data->map(function ($item) {
            $item->no = DB::selectOne('select @no := @no + 1 as no')->no;
            $item->nama_siswa = $item->student->nama ?? null;
            $item->nama_kelas = $item->student->classroom->nama_kelas ?? null;
            return $item;
        });

        return response()->json([
            'data' => $data,
            'total' => $allData->count(),
            'per_page' => $per,
            'current_page' => $page + 1,
            'last_page' => ceil($allData->count() / $per),
        ]);
    }

    // ========================== MENGAMBIL DETAIL TABUNGAN SETIAP SISWA ==========================
    public function detailSavings($id)
    {
        // ambil data siswa + relasi kelas & jurusan
        $student = Student::with(['classroom.major'])
            ->findOrFail($id);

        // ambil semua riwayat tabungan siswa
        $savings = Saving::where('student_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // ambil saldo terakhir dari tabel saving_balances
        $lastBalance = \App\Models\SavingBalance::where('student_id', $id)
            ->value('saldo') ?? 0;

        return response()->json([
            'success'      => true,
            'student'      => $student,
            'savings'      => $savings,
            'last_balance' => $lastBalance,
        ]);
    }

    // ========================== AMBIL SEMUA DATA TABUNGAN SETIAP SISWA DENGAN PAGINASI) ==========================
    public function indexUser(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        // Ambil student_id dari user yang sedang login
        $studentId = auth()->user()->student_id;

        $data = Saving::with('student') // relasi ke student
            ->where('student_id', $studentId) // filter berdasarkan student_id
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                })
                    ->orWhere('jenis', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }
}
