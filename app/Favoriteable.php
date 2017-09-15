<?php

namespace app;

use App\Favorite;

trait Favoriteable
{
    public static function bootFavoriteable()
    {
        static::deleting(function($model) {
           $model->favorites
               ->each
               ->delete();
        });
    }


    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        $this->favorites()
            ->where($attributes)
            ->get()
            ->each
            ->delete();
    }

    /**
     * @return bool
     */
    public function isFavorited()
    {
        return !!$this
            ->favorites
            ->where('user_id', auth()->id())
            ->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}