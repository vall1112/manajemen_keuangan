<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'bill_id',
        'nominal',
        'metode',
        'bukti',
        'status',
        'catatan',
    ];

    /**
     * Relasi ke Bill
     */
    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }
}

