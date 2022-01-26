<?php

namespace App;

use App\Scopes\AgentScope;
use App\Traits\Auditable;
use App\Notifications\CommentEmailNotification;
use Faker\Provider\Lorem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Ticket extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'tickets';

    protected $appends = [
        'attachments',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'content',
        'status_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'priority_id',
        'category_id',
        'author_name',
        'author_email',
        'assigned_to_user_id',
        'project_id',
        'ref_id',
        'code',
        'work_start',
        'work_end',
        'work_duration',
        'old_work_start',
        'old_work_end',
        'work_start_reason',
        'work_end_reason',
    ];

    public static function boot()
    {
        parent::boot();

        Ticket::observe(new \App\Observers\TicketActionObserver);

        static::addGlobalScope(new AgentScope);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(50)->height(50);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id', 'id');
    }

    public function getAttachmentsAttribute()
    {
        return $this->getMedia('attachments');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'priority_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function assigned_to_user()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function scopeFilterTickets($query)
    {
        $query->when(request()->input('priority'), function($query) {
                $query->whereHas('priority', function($query) {
                    $query->whereId(request()->input('priority'));
                });
            })
            ->when(request()->input('category'), function($query) {
                $query->whereHas('category', function($query) {
                    $query->whereId(request()->input('category'));
                });
            })
            ->when(request()->input('status'), function($query) {
                $query->whereHas('status', function($query) {
                    $query->whereId(request()->input('status'));
                });
            });
    }

    public function sendCommentNotification($comment)
    {
        $users = \App\User::where('id','!=', $comment->user_id)
                            ->whereDoesntHave('roles', function ($q) {
                                return $q->where('title', 'client');
                            })
                            ->orWhere('name','=', $this->author_name)
                            ->get();
        // $client = \App\User::where('name','=', $this->author_name)
        //                     ->get();
        // dd($users);
        try {
            $notification = new CommentEmailNotification($comment);
            Notification::send($users, $notification);
            // Notification::send($client, $notification);
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function project(){
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function ref()
    {
        return $this->belongsTo(Ticket::class, 'ref_id');
    }

    /**
     * Get all of the logs for the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(TicketLog::class);
    }
}
