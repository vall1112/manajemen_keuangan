<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\Bill;
use App\Models\SchoolYear;
use App\Models\PaymentType;
use App\Models\Transaction;
use App\Models\Saving;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // ========================== DASHBOARD ADMIN ==========================
    public function admin()
    {
        // Statistik master data
        $statistics = [
            'students'      => Student::count(),
            'users'         => User::count(),
            'majors'        => Major::count(),
            'roles'         => Role::count(),
            'school_years'  => SchoolYear::count(),
            'classrooms'    => Classroom::count(),
            'payment_type'  => PaymentType::count(),
        ];

        // Distribusi siswa per jurusan
        $studentsPerMajor = Student::select('majors.kode as jurusan', DB::raw('COUNT(students.id) as jumlah'))
            ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
            ->join('majors', 'classrooms.major_id', '=', 'majors.id')
            ->groupBy('majors.id', 'majors.kode')
            ->get()
            ->map(fn($s) => [
                'jurusan' => $s->jurusan,
                'jumlah'  => (int) $s->jumlah,
            ]);

        // Distribusi siswa per kelas, termasuk kelas tanpa siswa
        $studentsPerClass = Classroom::leftJoin('students', 'classrooms.id', '=', 'students.classroom_id')
            ->select('classrooms.nama_kelas as kelas', DB::raw('COUNT(students.id) as jumlah'))
            ->groupBy('classrooms.id', 'classrooms.nama_kelas')
            ->get()
            ->map(fn($c) => [
                'kelas'  => $c->kelas,
                'jumlah' => (int) $c->jumlah,
            ]);

        // Gabungkan students_per_major dan students_per_class ke statistics
        $statistics['students_per_major'] = $studentsPerMajor;
        $statistics['students_per_class'] = $studentsPerClass;

        // Return JSON
        return response()->json([
            'statistics' => $statistics
        ]);
    }


    // ========================== DASHBOARD BEBDAHARA ==========================
    public function bendahara()
    {
        // Statistik Keuangan
        $statistics = [
            'saldo_kas' => Transaction::where('status', 'Berhasil')->sum('nominal'),
            'pemasukan_hari_ini' => Transaction::where('status', 'Berhasil')
                ->whereDate('created_at', Carbon::today())
                ->sum('nominal'),
            'tagihan_belum_bayar' => Bill::where('status', 'Belum Dibayar')->sum('total_tagihan'),
            'saldo_tabungan' => Saving::orderBy('id', 'desc')->value('saldo') ?? 0,
        ];

        // Distribusi tagihan per jenis pembayaran
        $paymentTypes = Bill::select('payment_types.nama_jenis as jenis', DB::raw('COUNT(bills.id) as jumlah'))
            ->join('payment_types', 'bills.payment_type_id', '=', 'payment_types.id')
            ->groupBy('payment_types.id', 'payment_types.nama_jenis')
            ->get();

        // 10 transaksi terakhir
        $transactions = Transaction::with(['bill.student.classroom'])
            ->where('status', 'Berhasil')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($trx) {
                return [
                    'kode'    => $trx->kode,
                    'siswa'   => $trx->bill->student->nama ?? '-',
                    'kelas'   => $trx->bill->student->classroom->nama_kelas ?? '-',
                    'nominal' => $trx->nominal,
                    'tanggal' => $trx->created_at->format('Y-m-d H:i'),
                ];
            });

        // 10 aktivitas tabungan terbaru
        $savings = Saving::with(['student.classroom'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($saving) {
                return [
                    'id'      => $saving->id,
                    'siswa'   => $saving->student->nama ?? '-',
                    'kelas'   => $saving->student->classroom->nama_kelas ?? '-',
                    'jenis'   => $saving->jenis,
                    'nominal' => $saving->nominal,
                    'tanggal' => $saving->created_at->format('Y-m-d H:i'),
                ];
            });

        // Trend pendapatan per bulan (12 bulan terakhir)
        $monthlyIncome = Transaction::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(nominal) as total')
        )
            ->where('status', 'Berhasil')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

        // Jumlah siswa dengan tunggakan
        $studentsWithUnpaid = Bill::where('status', 'Belum Dibayar')
            ->select('student_id')
            ->distinct()
            ->count();

        return response()->json([
            'statistics' => array_merge($statistics, [
                'payment_types'   => $paymentTypes,
                'monthly_income'  => $monthlyIncome,
                'students_unpaid' => $studentsWithUnpaid,
            ]),
            'activities' => [
                'transactions' => $transactions,
                'savings'      => $savings,
            ],
        ]);
    }


    // ========================== DASHBOARD SISWA ==========================
    public function siswa()
    {
        $student = auth()->user()->student; // relasi User -> Student

        // Profil siswa
        $profile = [
            'nama'    => $student->nama,
            'nis'     => $student->nis,
            'kelas'   => $student->classroom->nama_kelas,
            'jurusan' => $student->classroom->major->nama_jurusan,
            'email'   => $student->user->email,
            'foto'    => $student->foto,
        ];

        // Tagihan siswa (Belum Dibayar)
        $bills = $student->bills()
            ->where('status', 'Belum Dibayar')
            ->get(['id', 'kode', 'total_tagihan', 'tanggal_tagih', 'status', 'keterangan'])
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'title' => $bill->kode, // bisa diganti pakai 'keterangan' jika ingin
                    'amount' => $bill->total_tagihan,
                    'due_date' => $bill->tanggal_tagih,
                    'status' => $bill->status,
                ];
            });

        // Ambil tabungan terbaru
        $latestSaving = $student->savings()->latest('id')->first();

        $saldo = $latestSaving->saldo ?? 0;

        $transactions = $latestSaving
            ? [
                [
                    'id' => $latestSaving->id,
                    'type' => $latestSaving->jenis,
                    'amount' => $latestSaving->nominal,
                    'saldo' => $latestSaving->saldo,
                    'note' => $latestSaving->keterangan,
                ]
            ]
            : [];

        return response()->json([
            'profile' => $profile,
            'bills' => $bills,
            'savings' => [
                'saldo' => $saldo,
                'transactions' => $transactions,
            ],
        ]);
    }
}
