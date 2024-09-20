<?php

namespace App\Console\Commands;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('Enter *supervisor* email');
        /*        $this->info('2 -> Supervisor'); */
        /*        $this->info('3 -> Researcher'); */
        /*        $role = $this->choice('User role?', [2, 3]); */
        $password = $this->secret('Enter password');
        $role = 2;

        if ($this->store($role, $email, $password, $user)) {
            // Mail::to($email)->send(new VerificationEmail($user, config('utilities.emailDefaultText')));
            // $this->info('An email was sent to '.$user->email.' he/she needs to set the password.');
            $this->info('User ' . $email . ' created');

            return true;
        }

        $this->info('There it was an error during user creation, please try again.');

        return false;
    }

    public function store($roleId, $email, $password, &$user)
    {
        $role = Role::where('id', $roleId)->first();
        $user = new User();
        $user->email = $email;
        $user->password = bcrypt($password);
        // $user->password_token = Helper::random_str(30);
        $user->email_verified_at = Date::now();
        $user->save();
        $user->attachRole($role);
        $createStudyPermission = Permission::where('name', 'create-studies')
            ->first();
        $user->supervised_by = $user->id;
        $user->save();
        $user->attachPermissions([$createStudyPermission]);

        return true;
    }
}
