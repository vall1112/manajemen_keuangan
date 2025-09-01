<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => Classroom::all()
        ]);
    }

    /**
     * Display a paginated list of the resource.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        DB::statement('set @no=0+' . $page * $per);

        $data = Classroom::with('teacher') // 👈 tambahkan ini
            ->when($request->search, function (Builder $query, string $search) {
                $query->where('nama_kelas', 'like', "%$search%")
                    ->orWhere('jurusan', 'like', "%$search%");
            })
            ->latest()
            ->paginate($per, ['*', DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelas'   => 'required|string|max:255',
            'jurusan'      => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:teachers,id',
            'jumlah_anak'  => 'required|integer|min:0',
        ]);

        $classroom = Classroom::create($validatedData);

        return response()->json([
            'success'   => true,
            'classroom' => $classroom
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        return response()->json([
            'classroom' => $classroom
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validatedData = $request->validate([
            'nama_kelas'   => 'required|string|max:255',
            'jurusan'      => 'required|string|max:255',
            'wali_kelas_id' => 'required|exists:teachers,id',
            'jumlah_anak'  => 'required|integer|min:0',
        ]);

        $classroom->update($validatedData);

        return response()->json([
            'success'   => true,
            'classroom' => $classroom
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
