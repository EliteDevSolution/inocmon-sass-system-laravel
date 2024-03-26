<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Template;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = \App\User::all();
        $isDashoardRouteFlag = false;

        if(!request()->query('clienteid')) {
            $isDashoardRouteFlag = true;
        } else {
            $isDashoardRouteFlag = false;
        }

        return view('admin.dashboard.index', compact('users', 'isDashoardRouteFlag'));
    }

}
