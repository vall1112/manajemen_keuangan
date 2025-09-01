<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function get(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => PaymentType::all()
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
        $data = PaymentType::when($request->search, function (Builder $query, string $search) {
            $query->where('nama_jenis', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%");
        })->latest()->paginate($per, ['*', \DB::raw('@no := @no + 1 AS no')]);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:payment_types,nama_jenis',
            'keterangan' => 'nullable|string',
        ]);

        $paymentType = PaymentType::create($validatedData);

        return response()->json([
            'success' => true,
            'payment_type' => $paymentType
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentType $paymentType)
    {
        return response()->json([
            'payment_type' => $paymentType
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $validatedData = $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:payment_types,nama_jenis,' . $paymentType->id,
            'keterangan' => 'nullable|string',
        ]);

        $paymentType->update($validatedData);

        return response()->json([
            'success' => true,
            'payment_type' => $paymentType
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
