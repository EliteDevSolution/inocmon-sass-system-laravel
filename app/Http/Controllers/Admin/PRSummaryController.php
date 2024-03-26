<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PRSummaryController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = \App\User::all();
        return view('admin.assetmanagement.manage', compact('users'));
    }
}
