<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'users_profiles';

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'birthday',
        'phonenumber1',
        'phonenumber2',
        'workaddress',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}