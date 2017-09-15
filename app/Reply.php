<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
