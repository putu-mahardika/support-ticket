<?php

namespace Database\Seeders;

use App\Helpers\FunctionHelper;
use App\Helpers\TicketHelper;
use App\Ticket;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // $start = microtime(true);
            // echo "Truncating....\n";
            // Ticket::truncate();
            // $diff = microtime(true) - $start;
            // $sec = intval($diff);
            // $micro = $diff - $sec;
            // echo "Truncated....(Time: " . round($micro * 1000, 4) . "ms) \n\n";

            // $start = microtime(true);
            // echo "Creating data....\n";
            // Ticket::factory()
            //       ->count(5000)
            //       ->create();
            // $diff = microtime(true) - $start;
            // $sec = intval($diff);
            // $micro = $diff - $sec;
            // echo "Data created....(Time: " . round($micro * 1000, 4) . "ms) \n\n";

            // $start = microtime(true);
            // echo "Work end updating....\n";
            // $tickets = Ticket::all();
            // $progressIndex = 0;
            // $progressMax = $tickets->count();
            // foreach ($tickets as $ticket) {
            //     $end = Carbon::create($ticket->work_start)->addMinutes(rand(30, 10000));
            //     $ticket->update([
            //         'created_at' => $ticket->work_start,
            //         'work_end' => $end->toDateTimeString()
            //     ]);
            //     $progressIndex++;
            //     FunctionHelper::progressBar($progressIndex, $progressMax);
            // }
            // $diff = microtime(true) - $start;
            // $sec = intval($diff);
            // $micro = $diff - $sec;
            // echo "Work end updated....(Time: " . round($micro * 1000, 4) . "ms) \n\n";

            // $start = microtime(true);
            // echo "Creating logs....\n";
            // TicketHelper::recreateLog($tickets);
            // $diff = microtime(true) - $start;
            // $sec = intval($diff);
            // $micro = $diff - $sec;
            // echo "Logs has been created....(Time: " . round($micro * 1000, 4) . "ms) \n\n";

            $tickets = Ticket::all();
            $progressIndex = 0;
            $progressMax = $tickets->count();
            foreach ($tickets as $ticket) {
                $ticket->update([
                    'code' => 'TS.1221.' . Str::padLeft($progressIndex+1, 4, '0')
                ]);
                $progressIndex++;
                FunctionHelper::progressBar($progressIndex, $progressMax);
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });

    }
}
