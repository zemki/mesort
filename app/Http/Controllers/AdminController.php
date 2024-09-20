<?php

namespace App\Http\Controllers;

use App\Action;
use App\Files;
use App\Interview;
use App\Study;
use App\User;
use Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * show dashboard.
     *
     * @return [type] [description]
     */
    public function index()
    {
        // gather data for the initial panel
        $data['user'] = auth()->user();
        $data['usercount'] = User::all()->count();
        $data['studiescount'] = Study::all()->count();
        $data['interviewcount'] = Interview::all()->count();
        $data['actions'] = Action::with('user')->orderBy('id', 'desc')->paginate(15);
        $data['actionscount'] = Action::all()->count();
        $data['occupiedstorage'] = $this->formatBytes(Files::occupiedStorage());

        return view('admin.dashboard', $data);
    }

    public function showStudies()
    {
        $data['studies'] = Study::all();

        return view('admin.studies', $data);
    }

    public function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = ['', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * show user dashboard.
     *
     * @return [type] [description]
     */
    public function indexUsers()
    {
        // gather data for the initial panel
        $data['user'] = auth()->user();
        $data['usercount'] = User::all()->count();
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
        return Storage::disk('local')->download('backup.7z');
    }

    /**
     * download yesterday backup.
     *
     * @return StreamedResponse
     */
    public function downloadYesterdayBackup()
    {
        return Storage::disk('local')->download('backup.old.7z');
    }

    public function listForNewsletter()
    {
        $users = User::join('users_profiles', 'users.id', '=', 'user_id')->where('newsletter', '=', 2)->get();

        return view('admin.newsletter', ['users' => $users]);
    }
}
