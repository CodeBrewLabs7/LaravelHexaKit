<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'phone_number', 'is_email_verified', 'email_verified_at', 'is_phone_verified', 'phone_verified_at', 'password', 'type', 'status', 'country_id', 'role_id', 'auth_token', 'system_id', 'remember_token'];

    protected static function newFactory()
    {
        return \Modules\Users\Database\factories\UserFactory::new();
    }

    const TYPE_ADMIN = 1;
    const TYPE_SUB_ADMIN = 2;
    const TYPE_USER = 3;
    const TYPE_VENDOR = 4;
}
