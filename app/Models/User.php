<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelUserActivity\Traits\Loggable;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Loggable;

    // protected $guard = 'admin';

    const ROLE_ADMIN = 1;

    const ROLE_USER = 2;

    const ROLE_SUB_ADMIN = 3;

    const SENT = 1;

    const OTP_PENDING = 'pending';

    const OTP_VERIFIED = 'verified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'name',
        'status',
        'country_flag',
        'country_code',
        'phone_number',
        'email',
        'password',
        'device_type',
        'role',
        'is_otp_sent'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'documents_uploaded',
    ];

    public function getDocumentsUploadedAttribute()
    {
        // Assuming you have a 'user_documents' model associated with the 'user_documents' table
        $userDocumentsCount = UserDocuments::where('user_id', $this->id)->count();

        // If the count is greater than 0, it means the user has uploaded documents
        return $userDocumentsCount > 0;
    }

    public function getRoleCodes()
    {
        $user = Auth::user();
        return $roles = Role::where('name', $user->getRoleNames())->get();
    }

    public function deviceTokens()
    {
        return $this->hasMany("App\Models\DeviceToken");
    }

    public function getDeviceToken()
    {
        return $this->deviceTokens()
            ->latest()
            ->first();
    }

    public function updateDeviceToken($deviceType, $deviceToken)
    {
        $this->deviceTokens()->delete();
        $this->deviceTokens()->create([
            'device_type' => $deviceType,
            'device_token' => $deviceToken
        ]);
    }

    public function revokeTokens()
    {
        $this->tokens()->update([
            'revoked' => true
        ]);
    }

    public function documents()
    {
        return $this->hasMany(UserDocuments::class);
    }
}
