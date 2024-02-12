<?php

namespace Modules\DeliveryOptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOption extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    // protected static function newFactory()
    // {
    //     // return \Modules\DeliveryOptions\Database\factories\DeliveryOptionFactory::new();
    // }
}
