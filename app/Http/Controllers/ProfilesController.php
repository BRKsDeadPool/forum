<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user)
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    protected function getActivity(User $user): Collection
    {
        return $user->activity()->latest()->with('subject')->take(50)->get()->groupBy(function (Activity $activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
