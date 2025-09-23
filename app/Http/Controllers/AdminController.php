<?php

namespace App\Http\Controllers;

use App\Action;
use App\Files;
use App\Helpers\FileHelper;
use App\Interview;
use App\Study;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('throttle:60,1')->only(['downloadBackup', 'downloadYesterdayBackup']);
    }

    /**
     * Show admin dashboard with statistics.
     *
     * @return View
     */
    public function index()
    {
        // gather data for the initial panel
        $data['user'] = auth()->user();
        $data['usercount'] = User::count();
        $data['studiescount'] = Study::count();
        $data['interviewcount'] = Interview::count();
        $data['actions'] = Action::with('user')->orderBy('id', 'desc')->paginate(15);
        $data['actionscount'] = Action::count();
        $data['occupiedstorage'] = FileHelper::formatBytes(Files::occupiedStorage() ?? 0);

        return view('admin.dashboard', $data);
    }

    /**
     * Show all studies with pagination.
     *
     * @return View
     */
    public function showStudies()
    {
        $studies = Study::with('user')->paginate(20);

        return view('admin.studies', compact('studies'));
    }

    /**
     * Show users dashboard.
     *
     * @return View
     */
    public function showUsers()
    {
        // gather data for the initial panel
        $data['user'] = auth()->user();
        $data['usercount'] = User::count();
        $data['users'] = User::with('studies', 'interviews')->get();

        return view('admin.usersdashboard', $data);
    }

    /**
     * download current backup.
     *
     * @return StreamedResponse
     */
    public function downloadBackup()
    {
        if (! Storage::disk('local')->exists('backup.7z')) {
            Log::warning('Admin backup download failed: backup.7z not found', [
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            abort(404, 'Backup file not found');
        }

        Log::info('Admin backup downloaded', [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return Storage::disk('local')->download('backup.7z');
    }

    /**
     * download yesterday backup.
     *
     * @return StreamedResponse
     */
    public function downloadYesterdayBackup()
    {
        if (! Storage::disk('local')->exists('backup.old.7z')) {
            Log::warning('Admin yesterday backup download failed: backup.old.7z not found', [
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
            abort(404, 'Yesterday backup file not found');
        }

        Log::info('Admin yesterday backup downloaded', [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
        ]);

        return Storage::disk('local')->download('backup.old.7z');
    }

    /**
     * List users subscribed to newsletter.
     *
     * @return View
     */
    public function listForNewsletter()
    {
        $users = User::whereHas('profile', function ($query) {
            $query->where('newsletter', 2);
        })->with('profile')->get();

        return view('admin.newsletter', compact('users'));
    }
}
