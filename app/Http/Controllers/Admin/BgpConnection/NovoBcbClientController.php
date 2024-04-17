<?php

namespace App\Http\Controllers\Admin\BgpConnection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class NovoBcbClientController extends Controller
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
        $buscaEquipamentos= $detailClientData['equipamentos'];

        if( array_key_exists('interconexoes', $detailClientData['bgp']) ) {
            $buscaCommunitiesTransito = $detailClientData['bgp']['interconexoes']['transito'] ?? [];
            $buscaCommunitiesIx = $detailClientData['bgp']['interconexoes']['ix'] ?? [];
            $cdns = $detailClientData['bgp']['interconexoes']['cdn'];
        } else {
            $buscaCommunitiesTransito =[];
            $buscaCommunitiesIx= [];
            $cdns = [];
        }

        $community = $detailClientData['bgp']['community0'];

        return view('admin.bgpconnection.bgpclient', compact('clientId','buscaEquipamentos',
        'buscaCommunitiesTransito', 'buscaCommunitiesIx', 'community'));
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
        $nome = $request['nome'];
        $asn = $request['asn'];
        $pop = $request['pop'];
        $blocosipv4 = $request['blocosipv4'];
        $blocosipv6 = $request['blocosipv6'];
        $ipv4pro = $request['ipv4pro'];
        $ipv4client = $request['ipv4client'];
        $ipv6pro = $request['ipv6pro'];
        $ipv6client = $request['ipv6client'];
        $recursivos = $request['recursivos'];
        $equip = $request['equip'];
        $community = $request['community'];
        $global = $request['global'];
        $transito = $request['transito'];
        $ix = $request['ix'];
        $cdn = $request['cdn'];
	    $newId = $this->database->getReference('clientes/'. $clientId .'/interconexoes/clientesbgp')->push()->getKey();
        $communitySet = "";

        if(is_array($request['community'])) {
            $communitySet = implode(', ', $request['community']);
        }

        $toSaveData = [
            'nomedoclientebgp' => $nome,
            'remoteas' => $asn,
            'pop' => $pop,
            'ipv4-01' => $ipv4pro,
            'ipv4-02' => $ipv4client,
            'ipv6-01' => $ipv6pro,
            'ipv6-02' => $ipv6client,
            'blocosipv4' => $blocosipv4,
            'blocosipv6' => $ipv6client,
            'peid' => $equip,
            'recursive-asn' => $recursivos,
            'communityset' => $global.$transito.$ix.$communitySet
        ];
        $status = '';
        try {
            $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/clientesbgp/'.$newId)->set($toSaveData);
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
