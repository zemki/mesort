<?php

namespace App\Console\Commands;

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
    protected $description = 'Create a new user account';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Creating a new user account...');
        $this->newLine();

        // Get user input with validation
        $email = $this->askRequiredEmail('Enter user email address');
        $password = $this->askRequiredSecret('Enter password');


        // Choose role
        $this->info('Available roles:');
        $this->info('1 -> Admin (full access)');
        $this->info('2 -> Researcher (can create studies and interviews)');
        $roleChoice = $this->choice('Select user role', ['1', '2'], '2');

        $roleName = $roleChoice === '1' ? 'admin' : 'researcher';

        // Ask about email verification
        $this->newLine();
        $verifyEmail = $this->confirm('Should the email be marked as verified?', true);

        if ($this->createUser($email, $password, $roleName, $verifyEmail)) {
            $this->newLine();
            $verificationStatus = $verifyEmail ? 'verified' : 'unverified';
            $this->info("✓ User '{$email}' created successfully with role: {$roleName} (email: {$verificationStatus})");
            return Command::SUCCESS;
        }

        $this->newLine();
        $this->error('✗ Failed to create user. Please check the details and try again.');
        return Command::FAILURE;
    }

    /**
     * Ask for required email input with validation
     */
    private function askRequiredEmail(string $question): string
    {
        do {
            $value = $this->ask($question);
            if (empty(trim($value))) {
                $this->error('Email is required. Please enter an email address.');
                continue;
            }

            if (!filter_var(trim($value), FILTER_VALIDATE_EMAIL)) {
                $this->error('Please enter a valid email address.');
                continue;
            }

            break;
        } while (true);

        return trim($value);
    }

    /**
     * Ask for required secret input, repeat until non-empty value is provided
     */
    private function askRequiredSecret(string $question): string
    {
        do {
            $value = $this->secret($question);
            if (empty(trim($value))) {
                $this->error('Password is required. Please enter a password.');
            }
        } while (empty(trim($value)));

        return trim($value);
    }

    /**
     * Create a new user with the specified role
     */
    private function createUser(string $email, string $password, string $roleName, bool $verifyEmail = true): bool
    {
        try {
            // Check if user already exists
            if (User::where('email', $email)->exists()) {
                $this->error("User with email '{$email}' already exists!");
                return false;
            }

            // Get the role
            $role = Role::where('name', $roleName)->first();
            if (!$role) {
                $this->error("Role '{$roleName}' not found!");
                return false;
            }

            // Create the user
            $user = new User();
            $user->email = $email;
            $user->password = bcrypt($password);

            // Set email verification based on user choice
            if ($verifyEmail) {
                $user->email_verified_at = Date::now();
            }

            $user->save();

            // Attach role using sync method (like in RegisterController)
            $user->roles()->sync($role);

            return true;
        } catch (\Exception $e) {
            $this->error("Error creating user: " . $e->getMessage());
            return false;
        }
    }
}
