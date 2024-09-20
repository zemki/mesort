<?php

namespace App\Console\Commands;

use App\Role;
use App\User;
use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;

class InstallMesortCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mesort:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Mesort application';

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
        $this->warn('---Migrating the database---');
        Artisan::call('migrate');
        $this->info(Artisan::output());
        $this->warn('--Cleaning the cache---');
        Artisan::call('optimize:clear');
        $this->info(Artisan::output());
        $this->warn('---Creating the required folders---');
        $this->error('---If this fails, check your folder permissions!---');
        $this->CreatePaths();
        $this->warn('Folders created successfully!');
        $this->warn('You will now create your personal user with all access rights.');
        $this->CreateAdmin($email, $password, $user);
        $this->info('You can now enter with the following data:');
        $this->info('Email: ' . $email);
        $this->info('Password: ' . $password);
    }

    public function storeUser($roleId, $email, $password, &$user): bool
    {
        if (! $this->validateUser($email, $password)) {
            return false;
        }
        $role = Role::where('id', $roleId)->first();
        $user = new User();
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->supervised_by = $user->id;
        $user->email_verified_at = Date::now();
        $user->save();
        $user->roles()->sync($role);

        return true;
    }

    private function validateUser($email, $password): bool
    {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->warn('Please enter a valid email.');

            return false;
        }
        if (strlen($password) < 6) {
            $this->warn('Please enter a valid password.');

            return false;
        }

        return true;
    }

    private function CreatePaths(): void
    {
        $path = public_path() . '/images/preset_tokens';

        File::makeDirectory($path, $mode = 0775, true, true);
        $path = public_path() . '/images/classifiers';
        File::makeDirectory($path, $mode = 0775, true, true);

        $path = storage_path('app/preset_tokens');
        File::makeDirectory($path, $mode = 0775, true, true);
        $path = storage_path('app/classifiers');
        File::makeDirectory($path, $mode = 0775, true, true);
    }

    private function CreateAdmin(&$email, &$password, &$user): void
    {
        $stored = false;
        while (! $stored) {
            $email = $this->ask('Enter your email');
            $password = $this->ask('Enter your password - minimum 6 chars.');
            $stored = $this->storeUser(1, $email, $password, $user);
        }
    }
}
