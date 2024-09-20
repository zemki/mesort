<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    use ResetsPasswords;

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest');
    }

    /**
     * Display the password reset view for the given token.
     * If no token is present, display the link request form.
     *
     * @param  string|null  $token
     * @return Factory|View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.resetPassword')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Reset the given user's password.
     *
     * @return Factory|View
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());
        // Validate the token
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        if (! $tokenData) {
            return view('auth.passwords.email', ['message' => 'You don\'t have tokens!']);
        } elseif (! Hash::check($request->token, $tokenData->token)) {
            return view('auth.passwords.email', ['message' => 'Token mismatch']);
        }
        // Delete the token
        DB::table('password_resets')->where('email', $request->email)
            ->delete();
        $user = User::where('email', '=', $request->email)->first();
        $this->setUserPassword($user, $request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        return view('auth.login', ['message' => 'Password Reset Successfully done.']);
    }
}
