<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeSpacePhoto extends Model
{
    use HasFactory;

    protected $table = 'office_space_photos';
    protected $guarded = ['id'];
}
