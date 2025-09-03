<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $table = 'majors'; // tabel yang dipakai

    protected $fillable = [
        'kode',
        'nama_jurusan',
        'keterangan',
    ];
}
