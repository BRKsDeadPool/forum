<?php

namespace App;

use app\Filters\Filters;
use App\Notifications\ThreadWasUpdated;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed replies
 * @property mixed channel
 * @property mixed $creator
 * @property mixed id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed $subscriptions
 */
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

    protected $appends = ['isSubscribedTo'];

    /**
     *  Boot the thread model, setup global scopes and add model listeners
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Thread $thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     *  Returns the thread instance path, useful for creating links
     * @param string $path
     * @return string
     */
    public function path($path = ''): string
    {
        return "/threads/{$this->channel->slug}/{$this->id}{$path}";
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
        $reply = $this->replies()->create($reply);

        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);

        return $reply;
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

    /**
     * @param null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()
            ->create([
                'user_id' => $userId ?: auth()->id()
            ]);

        return $this;
    }

    /**
     * @param null $userId
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    /**
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
