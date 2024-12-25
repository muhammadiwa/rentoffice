<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingTransaction extends Model
{
    use HasFactory;

    protected $table = 'booking_transactions';
    protected $guarded = ['id'];

    public function officeSpace()
    {
        return $this->belongsTo(OfficeSpace::class);
    }

    public static function generateUniqueTrxId()
    {
        $prefix = 'RO';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }
}
