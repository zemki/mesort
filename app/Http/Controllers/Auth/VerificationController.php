<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be resent if the user did not receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('showresetpassword', 'newpassword');
        $this->middleware('signed')->only('verify')->except('showresetpassword', 'newpassword');
        $this->middleware('throttle:12,1')->only('verify', 'resend')->except('showresetpassword', 'newpassword');
    }

    /**
     * Show reset password by a given token.
     *
     * @return Factory|View
     */
    public function showresetpassword(Request $request)
    {
        if ($request->input('token') === '') {
            return view('errors.resetpassword');
        }
        $user = User::where('password_token', '=', $request->input('token'))->first();
        if (! $user) {
            return view('errors.resetpassword');
        }
        $data['user'] = $user;

        return view('auth.passwords.verify', $data);
    }

    /**
     * Save the new password set by the user if it has a token.
     *
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function newpassword(Request $request)
    {
        if ($request->input('token') === '') {
            $data['error'] = 'wrong request, contact the administrator.';
            $data['user'] = '';

            return view('errors.resetpassword');
        }
        $user = User::where('password_token', '=', $request->input('token'))->first();
        if (! $user) {
            $data['error'] = 'Something went wrong, please contact the administrator.';

            return view('errors.resetpassword');
        }
        $user->password_token = null;
        $user->password = bcrypt($request->input('password'));
        $user->email_verified_at = Date::now();
        $user->save();

        return redirect('/');
    }
}
