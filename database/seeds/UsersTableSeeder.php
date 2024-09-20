<?php

use App\User;
use Illuminate\Database\Seeder;

class UserRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask for user email and password interactively
        $email = $this->command->ask('Enter the email for the admin user');
        $password = $this->command->secret('Enter the password for the admin user');

        // Check if the user already exists
        $user = User::where('email', '=', $email)->first();

        if ($user) {
            // Update existing user's password
            $user->password = Hash::make($password);
            $user->save();
            $this->command->info("Updated the existing user's password.");
        } else {
            // Create a new user
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            $this->command->info('Created a new user.');
        }

        // Assign the admin role to the user
        DB::table('user_roles')->insert([
            'user_id' => $user->id,
            'role_id' => 1, // Assuming 1 is the role ID for admin
        ]);

        $this->command->info('Admin role assigned to the user.');

    }
}
