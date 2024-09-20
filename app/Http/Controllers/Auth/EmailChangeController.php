<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\EmailChangeNotification;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class EmailChangeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Change Controller
    |--------------------------------------------------------------------------
    |
    | This controller allows the user to change his email address after he
    | verifies it through a message delivered to the enew email address.
    | This uses a temporarily signed url to validate the email change.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Only the authenticated user can change its email, but he should be able
        // to verify his email address using other device without having to be
        // authenticated. This happens a lot when they confirm by phone.
        $this->middleware('auth')->only('change');

        // A signed URL will prevent anyone except the User to change his email.
        $this->middleware('signed')->only('verify');
    }

    public function show()
    {
        return view('auth.emails.changeemail');
    }

    /**
     * Changes the user Email Address for a new one.
     *
     * @return RedirectResponse
     */
    public function change(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        // Send the email to the user
        Notification::route('mail', $request->email)
            ->notify(new EmailChangeNotification(Auth::user()->id));

        return response(__('An email has been sent, please check your inbox. Link is valid for 60 minutes'), 200);
    }

    /**
     * Verifies and completes the Email change.
     *
     * @param  string  $email
     * @return RedirectResponse|Response
     */
    public function verify(Request $request, User $user)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
        ]);

        $oldEmail = $user->first()->email;

        // Change the Email
        $user->first()->update([
            'email' => $request->email,
        ]);

        // log the action
        $user->first()->addAction('Email change from ' . $oldEmail . ' to ' . $user->email, 'from email link');

        // And finally return the view telling the change has been done
        return response()->view('auth.emails.change-complete');
    }
}
