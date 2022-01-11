<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    public $table = 'projects';

    protected $appends = ['user_pm'];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
        // 'assign_user_id',
        // 'pm_name',
        // 'pm_email',
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'user_project', 'project_id', 'user_id');
    }

    public function getUserPmAttribute(){
        return $this->users()->wherePivot('is_pm', true)->first();
    }

    public function tickets(){
        return $this->hasMany(Ticket::class, 'project_id', 'id');
    }
}
