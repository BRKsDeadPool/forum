<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait RecordsActivity
{

    /**
     *  Boot the trait and add listeners to activities record
     * @return void
     */
    protected static function bootRecordsActivity(): void
    {
        if (auth()->guest()) return;
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
           $model->activity()->delete();
        });
    }

    /**
     * Get all events wich laravel will listen and create activities
     * @return array
     */
    protected static function getActivitiesToRecord(): array
    {
        return ['created'];
    }

    /**
     *  Records activity based on model
     * @param string $event
     * @return void
     */
    protected function recordActivity(string $event): void
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    /**
     *  Get activity polymorphic relationship
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity(): MorphMany
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * Get event name based on event and model class
     * @param string $event
     * @return string
     */
    protected function getActivityType(string $event): string
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}