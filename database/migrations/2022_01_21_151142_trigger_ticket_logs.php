<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TriggerTicketLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE TRIGGER `ticket_log_create` AFTER INSERT ON `tickets`
            FOR EACH ROW
                INSERT INTO ticket_logs (`ticket_id`, `type`, `new`, `created_at`, `updated_at`)
                VALUES (
                    new.id,
                    'create',
                    JSON_MERGE_PRESERVE(
                        JSON_OBJECT('id', new.id),
                        JSON_OBJECT('title', new.title),
                        JSON_OBJECT('content', new.content),
                        JSON_OBJECT('author_name', new.author_name),
                        JSON_OBJECT('author_email', new.author_email),
                        JSON_OBJECT('created_at', new.created_at),
                        JSON_OBJECT('updated_at', new.updated_at),
                        JSON_OBJECT('deleted_at', new.deleted_at),
                        JSON_OBJECT('status_id', new.status_id),
                        JSON_OBJECT('priority_id', new.priority_id),
                        JSON_OBJECT('category_id', new.category_id),
                        JSON_OBJECT('assigned_to_user_id', new.assigned_to_user_id),
                        JSON_OBJECT('project_id', new.project_id),
                        JSON_OBJECT('ref_id', new.ref_id),
                        JSON_OBJECT('code', new.code),
                        JSON_OBJECT('work_start', new.work_start),
                        JSON_OBJECT('work_end', new.work_end),
                        JSON_OBJECT('work_duration', new.work_duration),
                        JSON_OBJECT('old_work_start', new.old_work_start),
                        JSON_OBJECT('old_work_end', new.old_work_end),
                        JSON_OBJECT('work_start_reason', new.work_start_reason),
                        JSON_OBJECT('work_end_reason', new.work_end_reason)
                    ),
                    now(),
                    now()
                );
       ");

       DB::unprepared("
            CREATE TRIGGER `ticket_log_update` AFTER UPDATE ON `tickets`
            FOR EACH ROW
                INSERT INTO ticket_logs (`ticket_id`, `type`, `old`, `new`, `created_at`, `updated_at`)
                VALUES (
                    new.id,
                    'update',
                    JSON_MERGE_PRESERVE(
                        JSON_OBJECT('id', old.id),
                        JSON_OBJECT('title', old.title),
                        JSON_OBJECT('content', old.content),
                        JSON_OBJECT('author_name', old.author_name),
                        JSON_OBJECT('author_email', old.author_email),
                        JSON_OBJECT('created_at', old.created_at),
                        JSON_OBJECT('updated_at', old.updated_at),
                        JSON_OBJECT('deleted_at', old.deleted_at),
                        JSON_OBJECT('status_id', old.status_id),
                        JSON_OBJECT('priority_id', old.priority_id),
                        JSON_OBJECT('category_id', old.category_id),
                        JSON_OBJECT('assigned_to_user_id', old.assigned_to_user_id),
                        JSON_OBJECT('project_id', old.project_id),
                        JSON_OBJECT('ref_id', old.ref_id),
                        JSON_OBJECT('code', old.code),
                        JSON_OBJECT('work_start', old.work_start),
                        JSON_OBJECT('work_end', old.work_end),
                        JSON_OBJECT('work_duration', old.work_duration),
                        JSON_OBJECT('old_work_start', old.old_work_start),
                        JSON_OBJECT('old_work_end', old.old_work_end),
                        JSON_OBJECT('work_start_reason', old.work_start_reason),
                        JSON_OBJECT('work_end_reason', old.work_end_reason)
                    ),
                    JSON_MERGE_PRESERVE(
                        JSON_OBJECT('id', new.id),
                        JSON_OBJECT('title', new.title),
                        JSON_OBJECT('content', new.content),
                        JSON_OBJECT('author_name', new.author_name),
                        JSON_OBJECT('author_email', new.author_email),
                        JSON_OBJECT('created_at', new.created_at),
                        JSON_OBJECT('updated_at', new.updated_at),
                        JSON_OBJECT('deleted_at', new.deleted_at),
                        JSON_OBJECT('status_id', new.status_id),
                        JSON_OBJECT('priority_id', new.priority_id),
                        JSON_OBJECT('category_id', new.category_id),
                        JSON_OBJECT('assigned_to_user_id', new.assigned_to_user_id),
                        JSON_OBJECT('project_id', new.project_id),
                        JSON_OBJECT('ref_id', new.ref_id),
                        JSON_OBJECT('code', new.code),
                        JSON_OBJECT('work_start', new.work_start),
                        JSON_OBJECT('work_end', new.work_end),
                        JSON_OBJECT('work_duration', new.work_duration),
                        JSON_OBJECT('old_work_start', new.old_work_start),
                        JSON_OBJECT('old_work_end', new.old_work_end),
                        JSON_OBJECT('work_start_reason', new.work_start_reason),
                        JSON_OBJECT('work_end_reason', new.work_end_reason)
                    ),
                    now(),
                    now()
                );
       ");

       DB::unprepared("
            CREATE TRIGGER `ticket_log_delete` AFTER DELETE ON `tickets`
            FOR EACH ROW INSERT INTO ticket_logs (`ticket_id`, `type`, `old`, `created_at`, `updated_at`)
            VALUES (
                old.id,
                'delete',
                JSON_MERGE_PRESERVE(
                    JSON_OBJECT('id', old.id),
                    JSON_OBJECT('title', old.title),
                    JSON_OBJECT('content', old.content),
                    JSON_OBJECT('author_name', old.author_name),
                    JSON_OBJECT('author_email', old.author_email),
                    JSON_OBJECT('created_at', old.created_at),
                    JSON_OBJECT('updated_at', old.updated_at),
                    JSON_OBJECT('deleted_at', old.deleted_at),
                    JSON_OBJECT('status_id', old.status_id),
                    JSON_OBJECT('priority_id', old.priority_id),
                    JSON_OBJECT('category_id', old.category_id),
                    JSON_OBJECT('assigned_to_user_id', old.assigned_to_user_id),
                    JSON_OBJECT('project_id', old.project_id),
                    JSON_OBJECT('ref_id', old.ref_id),
                    JSON_OBJECT('code', old.code),
                    JSON_OBJECT('work_start', old.work_start),
                    JSON_OBJECT('work_end', old.work_end),
                    JSON_OBJECT('work_duration', old.work_duration),
                    JSON_OBJECT('old_work_start', old.old_work_start),
                    JSON_OBJECT('old_work_end', old.old_work_end),
                    JSON_OBJECT('work_start_reason', old.work_start_reason),
                    JSON_OBJECT('work_end_reason', old.work_end_reason)
                ),
                now(),
                now()
            )
       ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `ticket_log_create`;');
        DB::unprepared('DROP TRIGGER IF EXISTS `ticket_log_update`;');
        DB::unprepared('DROP TRIGGER IF EXISTS `ticket_log_delete`;');
    }
}
