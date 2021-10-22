<?php

use App\Workclock;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AddDefaultDataToWorkclock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $workclocks = Workclock::all()->pluck('day')->toArray();
        $days = Carbon::getDays();
        $data = [];
        foreach ($days as $day) {
            if (!in_array($day, $workclocks)) {
                array_push($data, [
                    'day' => $day,
                    'time_start' => '08:00:00',
                    'duration' => 9,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        Workclock::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Workclock::truncate();
    }
}
