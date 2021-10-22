<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WorkClock;

class WorkClockController extends Controller
{
    public function index(){

        $workclocks = Workclock::all();
        return view('admin.workclock.index', compact('workclocks'));
    }

}
