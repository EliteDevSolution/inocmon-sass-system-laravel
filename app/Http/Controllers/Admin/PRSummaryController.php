<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PRSummaryController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
        // $this->clientId = $request->query();
        /*Beafore go in dashboard check id*/
    }
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $users = \App\User::all();
        $layout = true;
        $clientId =  $request->query()['client_id'];
        $clients = $this->database->getReference('clientes')->getValue();

        $buscarRr = $this->database->getReference('clientes/' . $clientId . '/rr')->getSnapshot()->getValue();
        if($buscarRr == null ) $buscarRr = [];
        return view('admin.assetmanagement.manage', compact('users', 'layout','clientId', 'buscarRr', 'clients'));
    }

    public function update(Request $request) {
        dd($request);exit;
        return;
    }
}