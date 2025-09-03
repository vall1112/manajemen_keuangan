<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $name = $request->input('name'); // Get name from request query parameter

        // If name is not provided, fall back to authenticated user
        if (!$name) {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
        } else {
            $student = Student::where('name', 'like', "%$name%")->first(); // Case-insensitive search
        }

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $bills = Bill::where('student_id', $student->id)->get();

        $dashboardData = [
            'student' => [
                'name' => $student->name,
                'class' => $student->class,
                'nisn' => $student->nisn,
                'balance' => $student->balance ?? 0,
            ],
            'bills' => $bills->map(function ($bill) {
                return [
                    'type' => $bill->type,
                    'month' => $bill->month,
                    'amount' => $bill->amount,
                    'due_date' => $bill->due_date,
                    'status' => $bill->status,
                ];
            }),
        ];

        return response()->json($dashboardData);
    }

    public function bills(Request $request)
    {
        $name = $request->input('name'); // Get name from request query parameter

        // If name is not provided, fall back to authenticated user
        if (!$name) {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
        } else {
            $student = Student::where('name', 'like', "%$name%")->first(); // Case-insensitive search
        }

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $bills = Bill::where('student_id', $student->id)->get();

        $billSummary = [
            'total_amount' => $bills->sum('amount'),
            'bills' => $bills->map(function ($bill) {
                return [
                    'type' => $bill->type,
                    'month' => $bill->month,
                    'amount' => $bill->amount,
                    'due_date' => $bill->due_date,
                    'status' => $bill->status,
                ];
            }),
        ];

        return response()->json($billSummary);
    }
}