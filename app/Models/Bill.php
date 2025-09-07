<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'student_id',
        'payment_type_id',
        'school_year_id',
        'total_tagihan',
        'tanggal_tagih',
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

    public function getRouteKeyName()
    {
        return 'kode';
    }

    /**
     * Boot method untuk auto-generate kode tagihan
     */
    protected static function booted()
    {
        static::creating(function ($bill) {
            // Ambil data relasi lewat ID
            $paymentType = PaymentType::find($bill->payment_type_id);
            $schoolYear = SchoolYear::find($bill->school_year_id);
            $student = Student::find($bill->student_id);

            // Ambil prefix 3 huruf dari nama jenis pembayaran
            $prefix = strtoupper(substr($paymentType->nama_jenis, 0, 3));

            // Ambil 2 digit dari tahun ajaran
            $yearPart = substr($schoolYear->tahun_ajaran, 2, 2);

            // Ambil kode dari tabel payment_types
            $paymentCode = strtoupper($paymentType->kode);

            // Ambil 5 digit awal NIS siswa
            $nisPart = substr($student->nis, 0, 5);

            // Gabungkan menjadi kode final
            $bill->kode = $prefix . $yearPart . $paymentCode . $nisPart;
        });
    }
}
