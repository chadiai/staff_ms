<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'name',
        'authorization_type_id',
        'staff_role_id',
        'country_id',
        'date_of_birth',
        'telephone',
        'bank_account_number',
        'email',
        'allergies',
        'active',
        'task_color',
        'appointment_color',
        'meal_color'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_format' => 'string',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'phoneFormat',
    ];

    public function authorization_type()
    {
        return $this->belongsTo(AuthorizationType::class)->withDefault();
    }

    public function staff_role()
    {
        return $this->belongsTo(StaffRole::class)->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function event_creation_requests()
    {
        return $this->hasMany(EventCreationRequest::class);
    }

    public function event_members()
    {
        return $this->hasMany(EventMember::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }



    public function meal_subscriptions()
    {
        return $this->hasMany(MealSubscription::class);
    }

    public function scheduled_absences()
    {
        return $this->hasMany(ScheduledAbsence::class);
    }

    /*protected function phoneFormat(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $phoneNumber = $attributes['telephone'];
                $countryCode = substr($phoneNumber, 0, 2); // Get the first two digits of the phone number, which represent the country code
                $formattedNumber = preg_replace('/[^0-9]/', '', $phoneNumber); // Remove all non-numeric characters from the phone number
                if ($countryCode === '04') {
                    // Belgium
                    $formattedNumber = preg_replace('/^0?(\d{2})(\d{3})(\d{2})(\d{2})$/', '+32 $1 $2 $3 $4', $formattedNumber); // Format the number as "+32 x xx xx xx"
                } else {
                    $formattedNumber = preg_replace('/^(\d{4})(\d{3})(\d{4})$/', '$1 $2 $3', $formattedNumber); // Format the number as "01xx yyy yyyy"
                }
                return $formattedNumber;
            },
        );
    }*/

    public function getPhoneFormatAttribute()
    {
        $phoneNumber = $this->attributes['telephone'];
        $countryCode = substr($phoneNumber, 0, 2);
        $formattedNumber = preg_replace('/[^0-9]/', '', $phoneNumber); // Remove all non-numeric characters from the phone number

        if ($countryCode === '04') {
            // Belgium
            $formattedNumber = preg_replace('/^0?(\d{3})(\d{2})(\d{2})(\d{2})$/', '+32 $1 $2 $3 $4', $formattedNumber); // Format the number as "+32 x xx xx xx"
        } else {
            $formattedNumber = preg_replace('/^(\d{3})(\d{4})(\d{4})$/', '$1 $2 $3', $phoneNumber);
        }

        $this->telephone = $formattedNumber;
        return $this->telephone;
    }

    public function isAdminOrSuperAdmin()
    {
        $isAdminOrSuperAdmin = false;
        $authType = $this->authorization_type;
        if ($authType) {
            $isAdminOrSuperAdmin = $authType->name === 'Admin' || $authType->name === 'Super Admin';
        }
        return $isAdminOrSuperAdmin;
    }

    public function isAdmin()
    {
        $isAdmin = false;
        $authType = $this->authorization_type;
        if ($authType) {
            $isAdmin = $authType->name === 'Admin';
        }
        return $isAdmin;
    }

    public function isSuperAdmin()
    {
        $isSuperAdmin = false;
        $authType = $this->authorization_type;
        if($authType) {
            $isSuperAdmin = $authType->name === 'Super Admin';
        }
        return $isSuperAdmin;
    }

    public function isCook()
    {
        $isCook = false;
        $authType = $this->authorization_type;
        if($authType) {
            $isCook = $authType->name === 'Cook';
        }
        return $isCook;
    }

    public function isStaff()
    {
        $isStaff = false;
        $authType = $this->authorization_type;
        if($authType) {
            $isStaff = $authType->name === 'Staff';
        }
        return $isStaff;
    }

    public function isGrandson()
    {
        $isGrandson = false;
        $authType = $this->authorization_type;
        if($authType) {
            $isGrandson = $authType->name === 'Grandson';
        }
        return $isGrandson;
    }

}
