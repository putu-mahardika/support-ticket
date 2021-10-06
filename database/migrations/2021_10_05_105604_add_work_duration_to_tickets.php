<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkDurationToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->bigInteger('ref_id')->nullable()->index();
            $table->string('code')->index();
            $table->dateTime('work_start')->nullable();
            $table->dateTime('work_end')->nullable();
            $table->integer('work_duration')->nullable();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('code')->nullable();
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
            $table->dropColumn([
                'ref_id',
                'code',
                'work_start',
                'work_end',
                'work_duration'
            ]);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
