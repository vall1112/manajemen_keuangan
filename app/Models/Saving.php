<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $table = 'savings'; // tabel yang dipakai

    protected $fillable = [
        'student_id',
        'nominal',
        'jenis',
        'saldo',
        'keterangan',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}