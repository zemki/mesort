<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public static function boot()
    {
        parent::boot();
        static::deleting(static function ($user) {
            Profile::whereUserId($user->id)->forceDelete();
            Action::whereUserId($user->id)->forceDelete();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * List the user roles.
     *
     * @var array
     */
    public function listRoles(): array
    {
        return $this->roles()->distinct()->pluck('name')->toArray();
    }

    public function isAdmin(): bool
    {
        return in_array('admin', $this->roles()->pluck('roles.name')->toArray());
    }

    /**
     * Check if the user has a certain or array of roles.
     *
     *
     * @var array
     */
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return count(array_intersect($roles, $this->roles()->distinct()->pluck('name')->toArray())) > 0;
        } else {
            return in_array($roles, $this->roles()->distinct()->pluck('name')->toArray());
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function addProfile($user)
    {
        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->save();

        return $profile;
    }

    /**
     * @param  string  $description
     * @return action
     *                add action to the action table
     */
    public function addAction($name, $url, $description = '')
    {
        $action = new Action();
        $action->name = $name;
        $action->description = $description;
        $action->url = $url;
        $action->user_id = auth()->user()->id;
        $action->time = date('Y-m-d H:i:s');
        $action->save();

        return $action;
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'author');
    }

    public function hasReachMaxNumberOfStudies(): bool
    {
        return $this->studies()->count() >= config('utilities.maxNumberOfStudies');
    }

    /**
     * The role that belong to the user.
     */
    public function studies()
    {
        return $this->hasMany(Study::class, 'user_id');
    }

    public function invites()
    {
        return $this->belongsToMany(Study::class, 'user_studies');
    }

    public function notOwnerNorInvited($study)
    {
        return auth()->user()->isNot($study->creator()) && ! auth()->user()->isAdmin() && ! in_array($study->id, auth()->user()->invites()->pluck('study_id')->toArray());
    }
}
