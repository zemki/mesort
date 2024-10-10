<?php

namespace App\Http\Controllers;

use App\Mail\RegisterSupervisor;
use App\Mail\VerificationEmail;
use App\User;
use Exception;
use Helper;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Register a user.
     *
     * @param  string  $text
     * @return void
     */
    public function sendEmail(User $user, $text)
    {
        Mail::to($user)->send(new RegisterSupervisor($text));
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
    }

    private function sendTestEmail(Request $request): bool
    {
        if ($request->testEmail) {
            Mail::to($request->email)->send(new VerificationEmail(new User, $request->emailtext ? $request->emailtext : config('utilities.emailDefaultText')));

            return true;
        }

        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(User $user)
    {
        if (! auth()->user()->isAdmin()) {
            abort(401);
        }
        $user->delete();

        return response('user deleted', 200);
    }

    /**
     * @return ResponseFactory|Response
     *
     * @throws Exception
     */
    public function sendresetpassword(User $user)
    {
        $user->password = Helper::random_str(60);
        $user->password_token = Helper::random_str(30);
        Mail::to($user->email)->send(new VerificationEmail($user));
        $user->save();

        return response('Password is resetted for ' . $user->email . ' and an email was sent.');
    }
}
