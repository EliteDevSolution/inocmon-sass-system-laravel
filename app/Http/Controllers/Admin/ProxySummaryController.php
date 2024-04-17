<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProxySummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }

    public function index(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $buscarSondas = $this->database->getReference('clientes/'. $clientId . '/sondas')->getSnapshot()->getValue();
        return view('admin.assetmanagement.proxy_summary', compact('buscarSondas', 'clientId'));
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
    public function update(Request $request)
    {
        $toSaveData = [
            'hostname' =>$request['hostVal'],
            'ipv4' => $request['routerVal'],
            'portassh' => $request['portaSshVal'],
            'portahttp' => $request['portaVal'],
            'pwd' => $request['pwdVal'],
            'user' => $request['userVal']
        ];

        $proxyId = $request['proxyId'];
        $clientId = $request['clientId'];

        $this->database->getReference('clientes/'.$clientId.'/sondas/'.$proxyId)->update($toSaveData);

        return response()->json([
            'status' => 'ok'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $toDeleteId = $request['toDeleteId'];
        $clientId = $request['clientId'];
        $path = 'clientes/'.$clientId.'/sondas/'.$toDeleteId;
        $status = "";
        try {
            $this->database->getReference($path)->remove();
            $status = "success";
        } catch (\Throwable $th) {
            $status = "failed";
        }
        return response()->json([
            'status' => $status
        ]);
    }
}
