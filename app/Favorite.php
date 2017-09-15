<?php

namespace App;

use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $reply_id
 */
class Favorite extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    public $timestamps = false;

    public function favorited()
    {
        return $this->morphTo();
    }
}
