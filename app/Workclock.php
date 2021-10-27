<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Workclock extends Model
{

    public $table = 'workclock';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'id',
        'day',
        'time_start',
        'duration',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'time_start' => 'datetime'
    ];
}
