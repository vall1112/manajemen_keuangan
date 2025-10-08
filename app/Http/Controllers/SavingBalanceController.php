<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavingBalance;
use Illuminate\Support\Facades\DB;

class SavingBalanceController extends Controller
{
    public function get($student_id)
    {
        if (!$student_id || $student_id === 'undefined') {
            return response()->json([
                'success' => false,
                'message' => 'student_id tidak valid',
            ], 400);
        }

        $saving = SavingBalance::where('student_id', $student_id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'student_id' => (int) $student_id,
                'balance' => (int) ($saving->saldo ?? 0),
            ],
        ]);
    }

    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        // Ambil dari saving_balances + relasi student + classroom
        $query = \App\Models\SavingBalance::with('student.classroom')
            ->when($request->search, function ($q, $search) {
                $q->whereHas('student', function ($qq) use ($search) {
                    $qq->where('nama', 'like', "%$search%");
                });
            })
            ->orderBy('updated_at', 'desc');

        $allData = $query->get();

        $data = $allData->forPage($page + 1, $per)->values();

        $data = $data->map(function ($item) {
            $item->no = DB::selectOne('select @no := @no + 1 as no')->no;
            $item->nama_siswa = $item->student->nama ?? null;
            $item->nama_kelas = $item->student->classroom->nama_kelas ?? null;
            $item->saldo = $item->saldo ?? 0;
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
}
