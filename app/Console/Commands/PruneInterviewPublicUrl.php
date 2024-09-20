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
    protected $description = 'Delete not used urls older than 48h';

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
        $urls = PublicInterviewUrl::where('created_at', '<=', Carbon::now()->subDays(14))->whereNull('submitted_at')->get();
        $countUrls = $urls->count();
        foreach ($urls as $url) {
            $delete_shorturl = DB::table('art_urls')
                ->where('url', 'like', '%' . $url->id)
                ->delete();
            $url->delete();
        }
    }
}
