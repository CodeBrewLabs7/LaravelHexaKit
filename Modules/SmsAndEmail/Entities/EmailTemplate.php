<?php

namespace Modules\SmsAndEmail\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class EmailTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['slug'];
}
