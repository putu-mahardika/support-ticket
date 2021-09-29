<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignUserIdToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            // $table->unsignedBigInteger('assign_user_id')->index();
            // $table->string('pm_name')->nullable();
            // $table->string('pm_email')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            // $table->dropColumn('pm_name');
            // $table->dropColumn('pm_email');
        });
    }
}
