<?php

namespace App;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_Project extends Model
{
    // use HasFactory;
    protected $table = 'user_project';

    protected $fillable = ['user_id', 'project_id'];
}
