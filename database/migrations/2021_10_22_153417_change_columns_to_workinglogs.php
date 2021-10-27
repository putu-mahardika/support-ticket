<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsToWorkinglogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workinglogs', function (Blueprint $table) {
            $table->bigInteger('ticket_id')->after('id')->index();
            $table->bigInteger('status_id')->after('ticket_id')->index();
            $table->dateTime('started_at')->nullable()->change();
            $table->dateTime('finished_at')->nullable()->change();
            $table->dropColumn('ticket_title');
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workinglogs', function (Blueprint $table) {
            $table->string('ticket_title')->after('id');
            $table->string('status')->after('ticket_title');
            $table->dateTime('started_at')->nullable(false)->change();
            $table->dateTime('finished_at')->nullable(false)->change();
            $table->dropColumn('ticket_id');
            $table->dropColumn('ticket_id');
        });
    }
}
