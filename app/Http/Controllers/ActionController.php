<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActionController extends Controller
{
    public function store(Request $request)
    {
        $action = new Action();
        $action->name = $request->name;
        $action->description = $request->description;
        $action->url = $request->url();
        $action->user = auth()->user()->id;
        $action->time = date('Y-m-d H:i:s');
        if ($action->save()) {
            return true;
        }
    }
}
