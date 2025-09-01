<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => SchoolYear::all()
        ]);
    }

    /**
     * Display a paginated list of the resource.
     */
    public function index(Request $request)
    {
        $per = $request->per ?? 10;
        $page = $request->page ? $request->page - 1 : 0;

        \DB::statement('set @no=0+' . $page * $per);
        $data = SchoolYear::when($request->search, function (Builder $query, string $search) {
            $query->where('tahun_ajaran', 'like', "%$search%")
                  ->orWhere('semester', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%");
        })->latest()->paginate($per, ['*', \DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tahun_ajaran' => 'required|string|max:20|unique:school_years,tahun_ajaran',
            'semester'     => 'required|in:Ganjil,Genap',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $schoolYear = SchoolYear::create($validatedData);

        return response()->json([
            'success' => true,
            'school_year' => $schoolYear
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolYear $schoolYear)
    {
        return response()->json([
            'school_year' => $schoolYear
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validatedData = $request->validate([
            'tahun_ajaran' => 'required|string|max:20|unique:school_years,tahun_ajaran,' . $schoolYear->id,
            'semester'     => 'required|in:Ganjil,Genap',
            'status'       => 'required|in:Aktif,Tidak Aktif',
        ]);

        $schoolYear->update($validatedData);

        return response()->json([
            'success' => true,
            'school_year' => $schoolYear
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
