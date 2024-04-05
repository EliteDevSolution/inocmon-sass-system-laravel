<?php

namespace App\Http\Controllers\Admin\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class PeController extends Controller
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
        $clientId = $req->query()['client_id'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaTemplates = $this->database->getReference('lib/templates/pe')->getSnapshot()->getValue();
        return view('admin.network.pe', compact('clientId' ,'buscaTemplates'));
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
        $clientId = $request['clientId'];
	    $newEquipId = $this->database->getReference('clientes/'. $clientId .'/equipamentos')->push()->getKey();
        $status = '';

        if( isset($request['routerid']) ) {
            $toSaveData = [
                'hostname'  => $request['hostname'],
                'routerid'    => $request['routerid'],
                'grupo-ibgp'    => $request['ibgp'],
                'template-vendor'   => $request['vendor'],
                'template-family'   => $request['family'],
                'protocolo'   => $request['protocol'],
                'porta'   => $request['porta'],
                'user'   => $request['user'],
                'pwd'   => $request['pwd']
            ];
            try {
	            $this->database->getReference('clientes/' . $clientId .'/equipamentos/'.$newEquipId )->set($toSaveData);
                $status = 'ok';
            } catch (\Throwable $th) {
                $status = 'failed';
            }
        }else {
            $status = 'failed';
        }
        return response()->json([
            'status' => $status
        ]);
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
