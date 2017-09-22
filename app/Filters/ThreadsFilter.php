<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadsFilter extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];
    /**
     * Filter by user name
     * @param $username
     * @return mixed
     * @internal param $builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     *  Filter the query according to most popular threads
     *
     * @return $this
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];
        $this->builder->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        $this->builder->where('replies_count', 0);
    }
}