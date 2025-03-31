<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get the details associated with the user.
     */
    public function user_details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    /**
     * Get the details list associated with the user.
    */
    public function user_subscriptions($is_active = [1]): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'user_subscriptions', 'user_id', 'subscription_id')
                    ->whereIn('user_subscriptions.is_active', $is_active);
    }

    /**
     * Get the details list associated with the user.
    */
    public function user_parent_subscriptions()
    {
        return Package::where('user_subscriptions.user_id', auth()->user()->parent_id)
                        ->leftJoin('user_subscriptions', 'user_subscriptions.subscription_id', '=', 'packages.id')
                        ->where('user_subscriptions.is_active', '1')
                        ->first();
    }

}
