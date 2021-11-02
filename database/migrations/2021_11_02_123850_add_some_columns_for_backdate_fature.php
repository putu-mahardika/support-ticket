<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsForBackdateFature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dateTime('old_work_start')->nullable();
            $table->dateTime('old_work_end')->nullable();
            $table->text('work_start_reason')->nullable();
            $table->text('work_end_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['old_work_start', 'old_work_end', 'reason_work_start', 'reason_work_end']);
        });
    }
}
