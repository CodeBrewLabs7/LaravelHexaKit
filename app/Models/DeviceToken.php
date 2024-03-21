<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
    public const TYPE_IOS = "ios";
    public const TYPE_ANDROID = "android";

    public static $deviceTypes = [
        self::TYPE_ANDROID,
        self::TYPE_IOS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'device_type', 'device_token'
    ];
}
