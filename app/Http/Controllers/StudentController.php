<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // ========================== AMBIL SEMUA DATA STUDENT (TANPA PAGINASI) ==========================
    public function get(Request $request)
    {
        $students = Student::with('classroom')->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    // ========================== AMBIL DATA STUDENT DENGAN PAGINASI ==========================
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Student::with(['classroom', 'user'])
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%")
                        ->orWhere('nis', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('status', 'like', "%$search%")
                        ->orWhereRaw("
                        CASE 
                            WHEN jenis_kelamin = 'L' THEN 'laki-laki'
                            WHEN jenis_kelamin = 'P' THEN 'perempuan'
                        END LIKE ?", ["%$search%"])
                        // search di relasi classroom
                        ->orWhereHas('classroom', function (Builder $q3) use ($search) {
                            $q3->where('nama_kelas', 'like', "%$search%");
                        })
                        // search di relasi user
                        ->orWhereHas('user', function (Builder $q4) use ($search) {
                            $q4->where('username', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    // ========================== SIMPAN DATA STUDENT BARU ==========================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama'          => 'required|string|max:255',
            'nis'           => 'required|string|unique:students,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
            'classroom_id'  => 'required|exists:classrooms,id',
            'email'         => 'required|email|unique:students,email',
            'telepon'       => 'required|string|max:20',
            'status'        => 'in:Aktif,Prakerin,Alumni,Keluar',
            'nama_ayah'     => 'nullable|string|max:255',
            'telepon_ayah'  => 'nullable|string|max:20',
            'nama_ibu'      => 'nullable|string|max:255',
            'telepon_ibu'   => 'nullable|string|max:20',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validatedData['foto'] = $request->file('foto')->store('students', 'public');
        }

        $student = Student::create($validatedData);

        return response()->json([
            'success' => true,
            'student' => $student
        ]);
    }

    // ========================== TAMPILKAN DETAIL STUDENT ==========================
    public function show(Student $student)
    {
        return response()->json([
            'student' => $student
        ]);
    }

    // ========================== UPDATE DATA STUDENT ==========================
    public function update(Request $request, Student $student)
    {
        $validatedData = $request->validate([
            'nama'          => 'required|string|max:255',
            'nis'           => 'required|string|unique:students,nis,' . $student->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
            'classroom_id'  => 'required|exists:classrooms,id',
            'email'         => 'required|email|unique:students,email,' . $student->id,
            'telepon'       => 'required|string|max:20',
            'status'        => 'in:Aktif,Prakerin,Alumni,Keluar',
            'nama_ayah'     => 'nullable|string|max:255',
            'telepon_ayah'  => 'nullable|string|max:20',
            'nama_ibu'      => 'nullable|string|max:255',
            'telepon_ibu'   => 'nullable|string|max:20',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($student->foto) {
                Storage::disk('public')->delete($student->foto);
            }
            $validatedData['foto'] = $request->file('foto')->store('students', 'public');
        }

        $student->update($validatedData);

        return response()->json([
            'success' => true,
            'student' => $student
        ]);
    }

    // ========================== HAPUS DATA STUDENT ==========================
    public function destroy(Student $student)
    {
        if ($student->foto) {
            Storage::disk('public')->delete($student->foto);
        }

        $student->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function getStudentAndClassroom(Request $request)
    {
        $students = Student::with('classroom')->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function getByStudent($id)
    {
        $user = User::where('student_id', $id)->first();

        return response()->json([
            'exists' => $user ? true : false,
            'user' => $user
        ]);
    }

    public function card(Student $student)
    {
        $user = $student->user;
        if (!$user) {
            abort(404, 'Siswa belum memiliki akun.');
        }

        $setting = Setting::firstOrFail();

        return view('users.card', [
            'user' => $user,
            'setting' => $setting,
        ]);
    }
}
