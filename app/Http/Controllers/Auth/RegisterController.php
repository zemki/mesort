<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Exception;
use GrantHolle\Altcha\Rules\ValidAltcha;
use Helper;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|string|min:6|regex:/^(?=.*[a-zA-Z])(?=.*[0-9]).+$/',
            'altoken' => [new ValidAltcha()],
        ],
            [
                // Custom error messages
                'password.regex' => __('Be sure your password contains at least 1 letter and 1 number.'),
            ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User|View
     *
     * @throws Exception
     */
    protected function create($data)
    {
        $userexist = User::where('email', '=', $data['email'])->first();

        if ($userexist) {
            return $this->showRegistrationForm();
        }

        $role = Role::where('name', 'researcher')->first();
        $user = new User();

        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->password_token = Helper::random_str(30);
        $user->save();

        $profile = $user->addProfile($user);

        $profile->newsletter = array_key_exists('newsletter', $data) ? config('enums.newsletter_status.SUBSCRIBED') : config('enums.newsletter_status.NOT SUBSCRIBED');
        $profile->save();

        $user->roles()->sync($role);

        $user->save();

        return $user;
    }
}
