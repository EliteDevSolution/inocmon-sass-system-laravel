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
        $clientId =  $request->query()['client_id'];

        $buscarRr = $this->database->getReference('clientes/' . $clientId . '/rr')->getSnapshot()->getValue();
        if($buscarRr == null ) $buscarRr = [];
        return view('admin.assetmanagement.manage', compact('clientId', 'buscarRr'));
    }

    public function update(Request $request) {

        $toSaveData = [
            'hostname' => $request['hostName'],
            'routerid' => $request['routerId'],
            'template-family' => $request['family'],
            'template-vendor' => $request['vendor'],
            'protocolo' => $request['protocol'],
            'user' => $request['user'],
            'pwd' => $request['pwd'],
            'porta' => $request['porta']
        ];

        $rrId = $request['rrId'];
        $clientId = $request['clientId'];

        $this->database->getReference('clientes/'.$clientId.'/rr/'.$rrId)->update($toSaveData);

        return response()->json([
            'status' => 'ok',
            'toSaveData' => $toSaveData
        ]);
    }

    public function delete($id, Request $request)
    {
        $toDeleteId = $id;
        // $path = 'clientes/'.$toDeleteId;
        $clientId = $request['clientId'];
        $path = 'clientes/'.$clientId.'/rr/'.$toDeleteId;
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