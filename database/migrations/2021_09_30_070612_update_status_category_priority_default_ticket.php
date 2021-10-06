<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusCategoryPriorityDefaultTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->nullable()->change();
            $table->unsignedInteger('priority_id')->nullable()->change();
            $table->unsignedInteger('category_id')->nullable()->change();
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
            $table->unsignedInteger('status_id')->change();
            $table->unsignedInteger('priority_id')->change();
            $table->unsignedInteger('category_id')->change();
        });
    }
}
