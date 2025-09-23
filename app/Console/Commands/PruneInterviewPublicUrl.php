<?php

namespace App\Console\Commands;

use App\PublicInterviewUrl;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneInterviewPublicUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pruneurls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired public interview URLs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(14);

        // Find expired unused URLs
        $expiredUrls = PublicInterviewUrl::where('created_at', '<=', $cutoffDate)
            ->whereNull('submitted_at')
            ->get(['id', 'study_id', 'created_at']);

        $totalUrls = $expiredUrls->count();

        if ($totalUrls === 0) {
            $this->info('No expired URLs found to clean up.');
            return Command::SUCCESS;
        }

        $this->info("Found {$totalUrls} expired public interview URLs to clean up...");

        $deletedCount = 0;
        foreach ($expiredUrls as $url) {
            try {
                // Delete related short URLs if they exist
                $deletedShortUrls = DB::table('art_urls')
                    ->where('url', 'like', '%' . $url->id)
                    ->delete();

                // Delete the public URL
                $url->delete();
                $deletedCount++;

                $urlDisplay = substr($url->id, 0, 8) . '...'; // Show first 8 chars of UUID
                $ageInDays = Carbon::parse($url->created_at)->diffInDays(Carbon::now());

                if ($deletedShortUrls > 0) {
                    $this->line("  - Cleaned UUID {$urlDisplay} (study: {$url->study_id}, age: {$ageInDays}d) + {$deletedShortUrls} short URLs");
                } else {
                    $this->line("  - Cleaned UUID {$urlDisplay} (study: {$url->study_id}, age: {$ageInDays}d)");
                }
            } catch (\Exception $e) {
                $this->error("Failed to delete URL {$url->id}: " . $e->getMessage());
            }
        }

        $this->info("✅ Successfully cleaned up {$deletedCount} expired public interview URLs.");
        return Command::SUCCESS;
    }
}
