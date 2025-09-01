<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'classroom_id',
        'email',
        'telepon',
        'status',
        'nama_ayah',
        'telepon_ayah',
        'nama_ibu',
        'telepon_ibu',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // relasi ke kelas
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
}

