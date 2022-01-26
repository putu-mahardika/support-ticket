<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixRelationToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->id()->change();
        });

        Schema::table('statuses', function (Blueprint $table) {
            $table->id()->change();
        });

        Schema::table('priorities', function (Blueprint $table) {
            $table->id()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->id()->change();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->change();
            $table->unsignedBigInteger('priority_id')->change();
            $table->unsignedBigInteger('category_id')->change();
            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('ticket_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        Schema::table('statuses', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        Schema::table('priorities', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedInteger('status_id')->change();
            $table->unsignedInteger('priority_id')->change();
            $table->unsignedInteger('category_id')->change();
            $table->unsignedInteger('assigned_to_user_id')->nullable()->change();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedInteger('ticket_id')->change();
            $table->unsignedInteger('user_id')->change();
        });
        Schema::enableForeignKeyConstraints();
    }
}
