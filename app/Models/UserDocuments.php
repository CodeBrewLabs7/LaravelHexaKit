<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocuments extends Model
{
    use HasFactory;

    const DOC_TYPE_DEED = 1;

    const DOC_TYPE_EMIRATES_ID = 2;

    const DOC_TYPE_PASSPORT = 3;

    const DOC_TYPE_OTHER = 4;

    const PENDING = 0;

    const APPROVED = 1;

    protected $fillable = [
        'user_id',
        'document_type',
        'document',
        'description',
        'status'
    ];
}
