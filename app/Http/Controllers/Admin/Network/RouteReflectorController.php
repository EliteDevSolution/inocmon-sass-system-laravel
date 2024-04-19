<?php

namespace App\Http\Controllers\Admin\Network;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class RouteReflectorController extends Controller
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
        $buscaTemplates = $this->database->getReference('lib/templates/rr')->getSnapshot()->getValue();
        return view('admin.network.route_reflector', compact('clientId', 'buscaTemplates'));
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
        $buscaRr = $this->database->getReference('clientes/'.$clientId.'/rr')->getSnapshot();
        $newId = 0;
        $status = '';

        if( $buscaRr->getValue() == null ) {
            $nextId = 1;
        } else {
            foreach ( $buscaRr->getValue() as $index => $value ) {
                $nextId = $index;
            }
            $nextId = $nextId + 1;
        }

        $toSaveData = [
            'hostname'  => strtoupper($request['hostname']),
            'routerid'    => $request['routerid'],
            'template-vendor'   => $request['vendor'],
            'template-family'   => $request['family'],
            'protocolo'   => $request['protocol'],
            'porta'   => $request['porta'],
            'user'   => $request['user'],
            'pwd'   => $request['senha']
        ];

        try {
	        $this->database->getReference('clientes/' . $clientId .'/rr/'.$nextId )->set($toSaveData);
            $status = 'ok';
        } catch (\Throwable $th) {
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
