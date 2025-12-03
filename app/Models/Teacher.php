<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Teacher extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi mass assignment.
     */
    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'no_telepon',
        'email',
        'alamat',
        'level',
        'mata_pelajaran',
        'status',
        'foto',
    ];

    /**
     * Hapus foto lama saat guru dihapus.
     */
    protected static function booted()
    {
        static::deleted(function ($teacher) {
            if ($teacher->foto != null && $teacher->foto != '') {
                $old_foto = str_replace('/storage/', '', $teacher->foto);
                Storage::disk('public')->delete($old_foto);
            }
        });
    }

    public function user()
    {
        return $this->hasOne(User::class, 'teacher_id');
    }
}
