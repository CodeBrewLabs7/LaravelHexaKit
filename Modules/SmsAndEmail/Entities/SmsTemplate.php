<?php

namespace Modules\SmsAndEmail\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'slug', 'tags', 'label', 'content', 'subject','template_id'];
}
