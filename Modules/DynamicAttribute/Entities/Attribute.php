<?php

namespace Modules\DynamicAttribute\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\DynamicAttribute\Database\factories\AttributeFactory::new();
    }
}
