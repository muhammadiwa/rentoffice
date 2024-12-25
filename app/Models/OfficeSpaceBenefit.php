<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfficeSpaceBenefit extends Model
{
    use HasFactory;

    protected $table = 'office_space_benefits';
    protected $guarded = ['id'];
}
