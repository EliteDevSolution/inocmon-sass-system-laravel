<?php

namespace App\Http\Controllers\Admin\Downstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientsController extends Controller
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
        $clientDetailData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $buscaBgpClientesTransito = $clientDetailData['bgp']['interconexoes']['clientesbgp'] ?? [];
        $clientName = $clientDetailData['nome'];
        $senhainocmon = $clientDetailData['seguranca']['senhainocmon'];
        $asn = $clientDetailData['bgp']['asn'];
        $community = $clientDetailData['bgp']['community0'];
        $equipments = $clientDetailData['equipamentos'] ?? [];
        if( array_key_exists('interconexoes', $clientDetailData['bgp']) ) {
            $buscaCommunitiesTransito = $clientDetailData['bgp']['interconexoes']['transito'];
            $buscaCommunitiesIx = $clientDetailData['bgp']['interconexoes']['transito'];
        }
        else {
            $buscaCommunitiesTransito = [];
            $buscaCommunitiesIx = [];
        }
        $community = $clientDetailData['bgp']['community0'];

        $toSendData = [
            'clientId' => $clientId,
            'clientName' => $clientName,
            'clientTransito' => $buscaBgpClientesTransito,
            'senhainocmon' => $senhainocmon,
            'asn' => $asn,
            'community' => $community,
            'equipment' => $equipments
        ];

        return view('admin.downstreams.clients', compact('toSendData', 'clientId', 'buscaCommunitiesTransito' , 'buscaCommunitiesIx', 'community'));
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
        $toStatus = '';
        $updateId = $request['dataId'];
        $clientId = $request['clientId'];
        $communitySet = "";
        if(is_array($request['community'])) {
            $communitySet = implode(', ', $request['community']);
        }
        $toSaveData = [
            'nomedoclientebgp' => strtoupper($request['clientName']),
            'remoteas' => $request['asn'],
            'pop' => strtoupper($request['pop']),
            'ipv4-01' => $request['ipv4Local'],
            'ipv4-02' => $request['ipv4Remote'],
            'ipv6-01' => $request['ipv6Local'],
            'ipv6-02' => $request['ipv6Remote'],
            'blocosipv4'   => $request['block4'],
            'blocosipv6'   => $request['block6'],
            'peid'   => $request['pe'],
            'recursive-asn' => $request['clientasn'],
            'communityset'   =>  $request['global']
                                .$request['transito']
                                .$request['ix']
                                .$request['peering']
                                .$request['noexporter'].$communitySet,
        ];
        $clientDetailData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        try {
            $this->database->getReference('clientes/' . $clientId .'/bgp/interconexoes/clientesbgp/'.$updateId)->update($toSaveData);
            $status = 'ok';
        } catch (\Throwable $th) {
            $status = 'failed';
        }
        return response()->json([
            'status' => $status
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
        $clientId = $request['clientId'];
        $toDeleteId = $request['toDeleteId'];
        $path = 'clientes/'.$clientId.'/bgp/interconexoes/clientesbgp/'.$toDeleteId;
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
