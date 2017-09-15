<?php

namespace App;

use app\Filters\Filters;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use RecordsActivity;

    /**
     *  Define guarded attributes
     * @var array
     */
    protected $guarded = [];

    /**
     *  Define global relationship queries
     * @var array
     */
    protected $with = ['creator', 'channel'];

    /**
     *  Boot the thread model, setup global scopes and add model listeners
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('replyCount', function (Builder $builder) {
            $builder->withCount('replies');
        });

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     *  Returns the thread instance path, useful for creating links
     * @return string
     */
    public function path(): string
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     *  Define replies relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     *  Define creator relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *  Add a reply to this thread
     * @param $reply array
     * @return Model
     */
    public function addReply(array $reply): Model
    {
        return $this->replies()->create($reply);
    }

    /**
     *  Define channel relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     *  Add filters to scope
     * @param Builder $query
     * @param Filters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, Filters $filters): Builder
    {
        return $filters->apply($query);
    }
}
