<?php

namespace Modules\Auth\Entities;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Users extends Model implements Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "users";
    protected $fillable = [
        "name",
        "email",
        "password",
        "phone_number",
        "type",
        "status"
    ];

    const TYPE_ADMIN = 1;
    const TYPE_SUB_ADMIN = 2;
    const TYPE_USER = 3;
    const TYPE_VENDOR = 4;

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    
    // protected static function newFactory()
    // {
    //     return \Modules\Auth\Database\factories\UsersFactory::new();
    // }
}
