<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Major;
use App\Models\Bill;
use App\Models\Transaction;
use App\Models\Saving;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // Statistik umum
        $statistics = [
            'students'   => Student::count(),
            'users'      => User::count(),
            'classrooms' => Classroom::count(),
            'majors'     => Major::count(),
            'bills' => [
                'paid'   => Bill::where('status', 'Lunas')->count(),
                'unpaid' => Bill::where('status', 'Belum Dibayar')->count(),
            ],
            'transactions' => [
                'success' => Transaction::where('status', 'Berhasil')->sum('nominal'),
                'failed'  => Transaction::where('status', 'Gagal')->sum('nominal'),
            ],
            'savings' => [
                'deposit' => Saving::where('jenis', 'Setor')->sum('nominal'),
                'pull'    => Saving::where('jenis', 'Tarik')->sum('nominal'),
            ],
        ];

        // Distribusi siswa per jurusan
        $studentsPerMajor = Student::select('majors.nama_jurusan as jurusan', DB::raw('COUNT(students.id) as jumlah'))
            ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
            ->join('majors', 'classrooms.major_id', '=', 'majors.id')
            ->groupBy('majors.id', 'majors.nama_jurusan')
            ->get();

        $activities = [
            'new_savings' => Saving::with(['student.classroom'])
                ->latest()
                ->take(5)
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
                }),
        ];

        return response()->json([
            'statistics' => $statistics,
            'statistics' => array_merge($statistics, ['students_per_major' => $studentsPerMajor]),
            'activities' => $activities,
        ]);
    }

    public function bendahara()
    {
        // Ringkasan Keuangan
        $statistics = [
            'bills' => [
                'today' => Bill::whereDate('created_at', Carbon::today())->sum('total_tagihan'),
                'month' => Bill::whereMonth('created_at', Carbon::now()->month)->sum('total_tagihan'),
            ],
            'transactions' => [
                'total' => Transaction::where('status', 'Berhasil')->sum('nominal'),
            ],
            'savings' => [
                'deposit' => Saving::where('jenis', 'Setor')->sum('nominal'),
                'pull'    => Saving::where('jenis', 'Tarik')->sum('nominal'),
                'total'   => Saving::sum('nominal'),
            ],
            'payment_status' => [
                'unpaid'  => Bill::where('status', 'Belum Dibayar')->count(),
                'paid'    => Bill::where('status', 'Lunas')->count(),
            ],
        ];

        // Distribusi tagihan per jenis pembayaran
        $paymentTypes = Bill::select('payment_types.nama_jenis as jenis', DB::raw('COUNT(bills.id) as jumlah'))
            ->join('payment_types', 'bills.payment_type_id', '=', 'payment_types.id')
            ->groupBy('payment_types.id', 'payment_types.nama_jenis')
            ->get();

        // 10 transaksi terakhir
        $transactions = Transaction::with(['bill.student.classroom'])
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

        // 10 tabungan terbaru
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
}
