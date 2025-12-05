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
use App\Models\SavingBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // ========================== DASHBOARD ADMIN ==========================
    public function admin()
    {
        $statistics = [
            'students'      => Student::count(),
            'users'         => User::count(),
            'majors'        => Major::count(),
            'roles'         => Role::count(),
            'school_years'  => SchoolYear::count(),
            'classrooms'    => Classroom::count(),
            'payment_type'  => PaymentType::count(),
        ];

        $studentsPerMajor = Student::select('majors.kode as jurusan', DB::raw('COUNT(students.id) as jumlah'))
            ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
            ->join('majors', 'classrooms.major_id', '=', 'majors.id')
            ->groupBy('majors.id', 'majors.kode')
            ->get()
            ->map(fn($s) => [
                'jurusan' => $s->jurusan,
                'jumlah'  => (int) $s->jumlah,
            ]);

        $studentsPerClass = Classroom::leftJoin('students', 'classrooms.id', '=', 'students.classroom_id')
            ->select('classrooms.nama_kelas as kelas', DB::raw('COUNT(students.id) as jumlah'))
            ->groupBy('classrooms.id', 'classrooms.nama_kelas')
            ->get()
            ->map(fn($c) => [
                'kelas'  => $c->kelas,
                'jumlah' => (int) $c->jumlah,
            ]);

        $statistics['students_per_major'] = $studentsPerMajor;
        $statistics['students_per_class'] = $studentsPerClass;

        return response()->json([
            'statistics' => $statistics
        ]);
    }

    // ========================== DASHBOARD BENDAHARA ==========================
    public function bendahara()
    {
        $statistics = [
            'saldo_kas'          => Transaction::where('status', 'Berhasil')->sum('nominal'),
            'pemasukan_hari_ini' => Transaction::where('status', 'Berhasil')
                ->whereDate('created_at', Carbon::today())
                ->sum('nominal'),
            'tagihan_belum_bayar' => Bill::where('status', 'Belum Dibayar')->sum('total_tagihan'),
            'saldo_tabungan'      => SavingBalance::sum('saldo'),
        ];

        $paymentTypes = Bill::select('payment_types.nama_jenis as jenis', DB::raw('COUNT(bills.id) as jumlah'))
            ->join('payment_types', 'bills.payment_type_id', '=', 'payment_types.id')
            ->groupBy('payment_types.id', 'payment_types.nama_jenis')
            ->get();

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

        $today = Carbon::today();

        $dueToday = Bill::whereIn('status', ['Belum Dibayar', 'Dibayar Sebagian'])
            ->whereDate('jatuh_tempo', $today)
            ->get(['kode', 'jatuh_tempo'])
            ->map(function ($bill) {
                return [
                    'kode'        => $bill->kode,
                    'jatuh_tempo' => Carbon::parse($bill->jatuh_tempo)->format('Y-m-d'),
                ];
            });

        $overdueBills = Bill::whereIn('status', ['Belum Dibayar', 'Dibayar Sebagian'])
            ->whereDate('jatuh_tempo', '<', $today)
            ->get(['kode', 'jatuh_tempo'])
            ->map(function ($bill) {
                return [
                    'kode'        => $bill->kode,
                    'jatuh_tempo' => Carbon::parse($bill->jatuh_tempo)->format('Y-m-d'),
                ];
            });

        $totalUnpaid = Bill::where('status', 'Belum Dibayar')->count();

        $newUsers = User::where('created_at', '>=', Carbon::now()->subDay())
            ->get(['id', 'username', 'email', 'created_at'])
            ->map(function ($user) {
                return [
                    'id'        => $user->id,
                    'username'  => $user->username,
                    'email'     => $user->email,
                    'joined_at' => $user->created_at->format('Y-m-d H:i'),
                ];
            });

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

        $monthlyIncome = Transaction::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('SUM(nominal) as total')
        )
            ->where('status', 'Berhasil')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'bulan');

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
            'notifications' => [
                'due_today'      => $dueToday,
                'overdue'        => $overdueBills,
                'total_unpaid'   => $totalUnpaid,
                'new_users'      => $newUsers,
            ],
        ]);
    }

    // ========================== DASHBOARD SISWA ==========================
    public function siswa()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'User belum login.'
            ], 401);
        }

        $student = $user->student;

        if (!$student) {
            return response()->json([
                'message' => 'Data siswa tidak ditemukan.'
            ], 404);
        }

        /**
         * ===========================
         * 1. PROFILE SISWA
         * ===========================
         */
        $profile = [
            'nama'    => $student->nama,
            'nis'     => $student->nis,
            'kelas'   => $student->classroom->nama_kelas ?? '-',
            'jurusan' => $student->classroom->major->nama_jurusan ?? '-',
            'email'   => $student->user->email ?? '-',
            'foto'    => $student->foto ? asset('storage/' . $student->foto) : null,
        ];


        /**
         * ===========================
         * 2. TAGIHAN BELUM DIBAYAR (limit 5)
         * ===========================
         */
        $bills = $student->bills()
            ->where('status', 'Belum Dibayar')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(function ($bill) {
                return [
                    'id'       => $bill->id,
                    'title'    => $bill->kode,
                    'amount'   => $bill->total_tagihan,
                    'due_date' => $bill->jatuh_tempo ?? $bill->tanggal_tagih,
                ];
            });

        /**
         * ===========================
         * 3. TOTAL SEMUA TAGIHAN BELUM DIBAYAR
         * ===========================
         */
        $totalTagihan = $student->bills()
            ->where('status', 'Belum Dibayar')
            ->sum('total_tagihan');


        /**
         * ===========================
         * 4. NOTIFIKASI TAGIHAN MENDekati JATUH TEMPO
         * (≤ 3 hari)
         * ===========================
         */
        $notifTagihan = $student->bills()
            ->where('status', 'Belum Dibayar')
            ->whereDate('jatuh_tempo', '<=', now()->addDays(3))
            ->orderBy('jatuh_tempo', 'asc')
            ->get()
            ->map(function ($b) {
                return [
                    'id'       => $b->id,
                    'title'    => $b->kode,
                    'amount'   => $b->total_tagihan,
                    'due_date' => $b->jatuh_tempo,
                ];
            });


        /**
         * ===========================
         * 5. SALDO TABUNGAN
         * ===========================
         */
        $saldo = SavingBalance::where('student_id', $student->id)->value('saldo') ?? 0;


        /**
         * ===========================
         * 6. RIWAYAT TABUNGAN (limit 5)
         * ===========================
         */
        $transactions = $student->savings()
            ->latest('id')
            ->take(5)
            ->get()
            ->map(function ($s) {
                return [
                    'id'         => $s->id,
                    'type'       => $s->jenis,
                    'amount'     => $s->nominal,
                    'created_at' => $s->created_at,
                ];
            });


        /**
         * ===========================
         * 7. RIWAYAT PEMBAYARAN TAGIHAN (limit 5)
         *    Student → User → Transactions
         * ===========================
         */
        $riwayatPembayaran = Transaction::where('user_id', $student->user->id)
            ->latest('id')
            ->take(5)
            ->get()
            ->map(function ($trx) {
                return [
                    'id'        => $trx->id,
                    'kode'      => $trx->kode,
                    'tagihan'   => $trx->bill->kode ?? '-',
                    'amount'    => $trx->nominal,
                    'status'    => $trx->status,
                    'tanggal'   => $trx->created_at->format('Y-m-d'),
                ];
            });


        /**
         * ===========================
         * 8. RESPONSE FINAL
         * ===========================
         */
        return response()->json([
            'profile' => $profile,

            'bills' => $bills,
            'total_tagihan' => $totalTagihan,
            'notif_tagihan' => $notifTagihan,

            'riwayat_pembayaran' => $riwayatPembayaran,

            'savings' => [
                'saldo'        => $saldo,
                'transactions' => $transactions,
            ],
        ]);
    }
}
