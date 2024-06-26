<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accesor profile_photo_url
    public function getProfilePhotoUrlAttribute($value)
    {
        if (!$value || $this->isDirty('name')) {
            $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
                return mb_substr($segment, 0, 1);
            })->join(' '));

            return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&size=512';
        }

        return $value;
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function($user) {
    //         if (!$user->profile_photo_url || $user->isDirty('name')) {
    //             $name = trim(collect(explode(' ', $user->name))->map(function ($segment) {
    //                 return mb_substr($segment, 0, 1);
    //             })->join(' '));

    //             $user->profile_photo_url = 'https://ui-avatars.com/api/?name='.urlencode($name).'&size=512';
    //         } else {
    //             $user->profile_photo_url;
    //         }
    //     });
    // }

    /**
     * The clases that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clases()
    {
        return $this->belongsToMany(Clase::class)->withTimestamps();
    }

    /**
     * Get all of the imparte for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imparte()
    {
        return $this->hasMany(Clase::class);
    }
}
