<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeSpace extends Model
{
    use HasFactory;

    protected $table = 'office_spaces';
    protected $guarded = ['id'];

    public function photos()
    {
        return $this->hasMany(OfficeSpacePhoto::class, 'office_space_id', 'id');
    }

    public function benefits()
    {
        return $this->hasMany(OfficeSpaceBenefit::class, 'office_space_id', 'id');
    }

    public function bookingTransactions()
    {
        return $this->hasMany(BookingTransaction::class, 'office_space_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

}
