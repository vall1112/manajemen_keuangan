<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classrooms';

    protected $fillable = [
        'nama_kelas',
        'jurusan',
        'wali_kelas_id',
        'jumlah_anak',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'wali_kelas_id');
    }
}
