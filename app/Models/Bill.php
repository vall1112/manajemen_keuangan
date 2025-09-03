<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'payment_type_id',
        'school_year_id',
        'total',
        'tanggal_tagih',
        // 'status',
        // 'dibayar',
        // 'sisa',
        'keterangan',
    ];

    /**
     * Relasi ke siswa (student)
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Relasi ke jenis pembayaran
     */
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    /**
     * Relasi ke tahun ajaran
     */
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }
}
