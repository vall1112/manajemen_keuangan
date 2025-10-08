<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'bill_id',
        'nominal',
        'metode_pembayaran',
        'status',
        'catatan',
        'kode',
    ];

    /**
     * Relasi ke Bill
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Boot method untuk generate kode transaksi otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $today = now()->format('Ymd');

            // cari transaksi terakhir hari ini
            $lastTransaction = self::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = 0;
            if ($lastTransaction && $lastTransaction->kode) {
                $parts = explode('-', $lastTransaction->kode);
                $lastNumber = intval(end($parts));
            }

            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $model->kode = "TRX-{$today}-{$nextNumber}";
        });
    }
}
