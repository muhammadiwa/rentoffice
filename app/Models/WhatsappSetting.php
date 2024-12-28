<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappSetting extends Model
{
    protected $fillable = [
        'device_name',
        'phone_number',
        'is_connected',
        'session_data'
    ];

    protected $casts = [
        'is_connected' => 'boolean',
        'session_data' => 'array'
    ];
}
