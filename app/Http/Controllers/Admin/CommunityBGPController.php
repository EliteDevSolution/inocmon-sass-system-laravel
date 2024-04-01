<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommunityBGPController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $users = \App\User::all();
        $layout = true;
        $clientId = $req->query()['client_id'];
        $data = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $community = $data['bgp']['community0'];
        $transito = $data['bgp']['interconexoes']['transito'];
        $ix = $data['bgp']['interconexoes']['ix'];
        $equipmento = $data['equipamentos'];
        $toSendData = [
            "community" => $community,
            "transito" => $transito,
            "ix" => $ix,
            "client_id" => $clientId,
            "equipment" => $equipmento
        ];
        $clients = $this->database->getReference('clientes')->getValue();
        return view('admin.dashboard.community', compact('layout', 'toSendData' ,'clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
