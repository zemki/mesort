<?php

namespace App\Http\Controllers;

use App\Notifications\NotificationFromStaff;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function show(Request $request)
    {
        return auth()->user()->notifications()->take(5)->get();
    }

    public function create()
    {
        return view('admin.notifications');
    }

    public function update(Request $request)
    {
        auth()->user()->notifications()->where('id', $request->notification)->update(['read_at' => now()]);

        return now();
    }

    public function store(Request $request)
    {
        if (! auth()->user()->isAdmin()) {
            abort(401);
        }
        $attributes = request()->validate([
            'title' => 'required',
            'message' => 'required',
        ]);

        if ((! $request->has('all') || ! $request->Input('all')) && $request->input('email') == '') {
            return view('admin.notifications', ['message' => 'Fill email or send to all']);
        }

        if ($request->input('all')) {
            $users = User::all();
            Notification::send($users, new NotificationFromStaff(['title' => $request->input('title'), 'message' => $request->input('message')]));

            return view('admin.notifications', ['message' => 'Notification sent to all users']);
        }
        $user = User::where('email', $request->input('email'))->first();
        $user->notify(new NotificationFromStaff(['title' => $request->input('title'), 'message' => $request->input('message')]));

        return view('admin.notifications', ['message' => 'Notification sent to ' . $request->input('email')]);
    }

    public function addToNewsletter(Request $request)
    {
        $subscribe = $request->input('subscribed') ? config('enums.newsletter_status.SUBSCRIBED') : config('enums.newsletter_status.NOT SUBSCRIBED');
        try {
            if (auth()->user()->profile()->exists()) {
                auth()->user()->profile->newsletter = $subscribe;
                auth()->user()->profile->save();
            } else {
                $profile = auth()->user()->addProfile(auth()->user());
                $profile->newsletter = $subscribe;
                $profile->save();
            }

            return response()->json(['message' => 'Your preference was saved!', 'r' => $subscribe], 200);
        } catch (Exception $exception) {
            return response()->json(['message' => 'A problem occurred, contact the administrator.'], 500);
        }
    }
}
