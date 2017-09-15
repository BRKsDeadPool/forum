<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     *  Edit Route Model Binding resolve key
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     *  Set Thread Relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     *  Set Activity Relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity(): HasMany
    {
        return $this->hasMany('App\Activity');
    }
}
