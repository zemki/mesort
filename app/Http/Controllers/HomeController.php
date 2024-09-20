<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //  $this->middleware('auth');
    }

    public function asuser($userid)
    {
        if (! auth()->user()->isAdmin()) {
            abort(401);
        }
        $data['studies'] = User::findOrFail((int) $userid)->studies()->with('sortings')->get();
        $data['invited_studies'] = User::findOrFail((int) $userid)->invites;
        $data['breadcrumb'] = ['Home'];

        return view('home', $data);
    }

    /**
     * Show the application dashboard.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $data['studies'] = auth()->user()->studies()->with('sortings', 'interviews')->get();
        foreach ($data['studies'] as $key => $value) {
            $data['studies'][$key]['authiscreator'] = Auth::user()->is($data['studies'][$key]->creator());
            $data['studies'][$key]['editable'] = $data['studies'][$key]->isEditable();
        }
        $data['invited_studies'] = auth()->user()->invites()->with('sortings', 'interviews')->get();

        foreach ($data['invited_studies'] as $key => $value) {
            $data['invited_studies'][$key]['authiscreator'] = false;
            $data['invited_studies'][$key]['editable'] = false;
            $data['invited_studies'][$key]['owner'] = $data['invited_studies'][$key]->creator()->email;
        }
        $data['breadcrumb'] = ['Home'];

        return view('home', $data);
    }

    public function qsort()
    {
        return view('interview.testingqsort');
    }

    public function login()
    {
        return redirect('/');
    }

    public function changeLanguage($locale)
    {
        Session::put('locale', $locale);

        return redirect()->back();
    }
}
