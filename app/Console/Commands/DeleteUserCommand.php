<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a user account from the system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('⚠️  USER DELETION TOOL');
        $this->newLine();

        // Choose search method
        $searchBy = $this->choice('How would you like to find the user?', ['email', 'id'], 'email');
        $searchValue = $this->ask("Enter user {$searchBy}");

        // Find user
        try {
            $user = User::where($searchBy, $searchValue)->withTrashed()->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            $this->error("❌ User not found with {$searchBy}: {$searchValue}");
            return Command::FAILURE;
        }

        // Display user info
        $this->newLine();
        $this->info("Found user:");
        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $user->id],
                ['Name', $user->name ?? 'N/A'],
                ['Email', $user->email],
                ['Created', $user->created_at->format('Y-m-d H:i:s')],
                ['Status', $user->trashed() ? 'Deleted' : 'Active'],
            ]
        );

        // Confirm deletion
        if (!$this->confirm("🚨 ARE YOU SURE YOU WANT TO DELETE USER: {$user->email}?", false)) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        // Choose deletion type
        $this->newLine();
        $this->warn('⚠️  Soft deletion: Keeps user in database but marks as deleted (can be restored)');
        $this->error('🗑️  Force deletion: Permanently removes user and all related data (CANNOT be undone)');

        $deletionType = $this->choice('Select deletion type:', ['Soft', 'Force'], 'Soft');

        try {
            if ($deletionType === 'Force') {
                $user->forceDelete();
                $this->info("✅ User '{$user->email}' permanently deleted.");
            } else {
                $user->delete();
                $this->info("✅ User '{$user->email}' soft deleted.");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Failed to delete user: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
