<?php

namespace App\Http\Controllers;

use App\Action;
use App\Mail\RegisterSupervisor;
use App\Mail\VerificationEmail;
use App\User;
use Carbon\Carbon;
use Exception;
use Helper;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Get data to fill select dropdown input.
     *
     * @return [collection] [input data for the form]
     */
    public function getinputdata()
    {
        $allRoles = [['value' => 2, 'name' => 'Supervisor']];
        // $relatedstudies = Auth::user()->studiesforedit;
        // $data['relatedstudies'] = $relatedstudies;
        // if(in_array(1,Auth::user()->roles()->pluck('role_id')->toArray())){
        //   $data['supervisors'] = User::join('user_roles', 'users.id', '=','user_roles.user_id')->where('role_id','=','2')->get();
        //   $allRoles = [['value' => 1,'name' => 'Admin'],['value' => 2,'name' => 'Supervisor'],['value' => 3,'name' => 'Researcher']];
        // }
        $data['allroles'] = $allRoles;

        return response()->json($data, 200);
    }

    public function show()
    {
        $data['actions'] = Action::join('users', 'users.id', 'actions.user_id')->where('users.id', '=', auth()->user()->id)->orderBy('actions.id', 'desc')->paginate(10);

        foreach ($data['actions'] as $action) {
            $action->time = Carbon::parse($action['time'])->format('d.m.Y h:s:i');
        }

        $data['profile'] = auth()->user()->profile;

        return view('user.profile', $data);
    }

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
        if ($request->testEmail == true) {
            Mail::to($request->email)->send(new VerificationEmail(new User(), $request->emailtext ? $request->emailtext : config('utilities.emailDefaultText')));

            return true;
        }

        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return User|Builder|Collection|Model|null
     */
    public function edit($id)
    {
        return User::with('studies', 'roles')->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws Exception
     */
    public function destroy(User $user)
    {
        if (! auth()->user()->isAdmin()) {
            abort(401);
        }
        // $user = User::where('id', $id)->first();
        $user->delete();

        return response('user deleted', 200);
    }

    /**
     * @return ResponseFactory|Response
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
