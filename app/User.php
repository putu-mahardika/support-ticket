<?php

namespace App;

use App\Helpers\FunctionHelper;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
// use Laravel\Passport\HasApiTokens;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use SoftDeletes, Notifiable, HasApiTokens, InteractsWithMedia;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
        'company',
        'is_active',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to_user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin()
    {
        return $this->roles->contains(1);
    }

    public function isAgent()
    {
        return $this->roles->contains(2);
    }

    public function isClient()
    {
        return $this->roles->contains(3);
    }

    public function getRoleNamesAttribute()
    {
        return $this->roles()->pluck('title');
    }

    public function hasRole($roleName)
    {
        $roleNames = $this->role_names->transform(function ($item, $key) {
            return Str::lower($item);
        });
        return $roleNames->contains(Str::lower($roleName));
    }

    public function projects(){
        return $this->belongsToMany(Project::class, 'user_project', 'user_id', 'project_id');
    }

    public function hasProject($project)
    {
        if (FunctionHelper::varIs($project) === "integer") {
            $id = $project;
        }
        elseif (FunctionHelper::varIs($project) === "object") {
            $id = $project->id;
        }
        else {
            return;
        }

        return $this->projects->pluck('id')->contains($id);
    }

    public function registerMediaCollections(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(64)->height(64);
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMedia('profile');
    }
}
