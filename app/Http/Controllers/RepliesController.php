<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel, Thread $thread)
    {
        $this->validate(request(), [
           'body' => 'required'
        ]);
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);
        $reply->load('owner');

        if (request()->expectsJson()) {
            return response($reply);
        }

        return back()
            ->with('flash', 'Your answer was sent');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        if (request()->expectsJson()) {
            return response([
                'status' => 'Reply deleted'
            ]);
        }

        return back()
            ->with('flash', 'Reply deleted!');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }
}
