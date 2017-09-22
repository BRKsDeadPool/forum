<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed thread
 * @property mixed $owner
 * @property mixed id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Reply extends Model
{
    use Favoriteable, RecordsActivity;

    /**
     *  Define guarded attributes
     * @var array
     */
    protected $guarded = [];

    /**
     *  Define global relationship queries
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    /**
     *  Append some global attributes to json cast
     */
    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function($reply) {
           $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply) {
            $reply->thread->decrement('replies_count');
        });
    }

    /**
     *  Define owner relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *  Define thread relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     *  Define the path for reply
     * @return string
     */
    public function path(): string
    {
        return $this->thread->path() . "#reply-{$this->id}";
    }
}
