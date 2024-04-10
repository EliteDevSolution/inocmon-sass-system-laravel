<?php

namespace App\Http\Controllers\Admin\Upstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class TrafficController extends Controller
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

        $buscaEquipamentos = $detailClientData['equipamentos'];

        $buscaBgpConexoes = $detailClientData['bgp']['interconexoes']['transito'];
        $toSendData = [
            'buscaBgp' => $buscaBgpConexoes,
            'buscaEquip' => $buscaEquipamentos
        ];

        return view('admin.upstreams.traffic.index', compact('clientId', 'toSendData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response11
     */
    public function create(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaEquipamentos= $detailClientData['equipamentos'];
        $cdns = $detailClientData['bgp']['interconexoes']['transito'];
        return view('admin.upstreams.traffic.create', compact('clientId', 'buscaEquipamentos','cdns'));

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
        $tipoConexao = "transito";
        $buscaBgpConexoes = $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao)->getSnapshot()->getValue();
        $lastId = 0;
        foreach ($buscaBgpConexoes as $index => $value) {
            $lastId = $index;
        }
        $nextId = $lastId + 1;
        if($nextId < 10) {
            $nextId = '0'.$nextId;
        }
        $nomeDoGrupo = strtoupper($tipoConexao).'-'.$nextId.'-'.$request['nomeVal'].'-'.$request['popVal'];
        $communityGroup = 1;
        $community0 = $this->database->getReference('clientes/'.$clientId.'/bgp/community0')->getSnapshot()->GetValue();

        $novoBgp = [
            'provedor'  => strtoupper($request['nomeVal']),
            'pop'    => strtoupper($request['popVal']),
            'remoteas'    => $request['asnVal'],
            'ipv4-01'    => $request['bgp1Val'],
            'ipv4-02'   => $request['bgp2Val'],
            'ipv6-01'    => $request['bgp01Val'],
            'ipv6-02'   => $request['bgp02Val'],
            'peid'   => $request['equipVal'],
            'denycustomerin'   => $request['checkVal'],
            'nomedogrupo' => $nomeDoGrupo,
            'communities' => [
                'NO-EXPORT-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'0',
                'PREPEND-1X-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'1',
                'PREPEND-2X-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'2',
                'PREPEND-3X-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'3',
                'PREPEND-4X-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'4',
                'PREPEND-5X-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'5',
                'PREPEND-6X-'.$nomeDoGrupo => $community0.':'.$communityGroup.$nextId.'6'
			]
        ];
	    $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao.'/'.$nextId)->set($novoBgp);

        return response()->json([
                    'status' => 'ok',
                    'addedData' => [
                        'id' => $nextId,
                        'remoteas' => $request['asnVal'],
                        'nomedogrupo' => $nomeDoGrupo
                    ]
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
        $toSaveData = [
            'remoteas' => $request['asnVal'],
            'pop' => $request['popVal']
        ];

        $transitoId = $request['trafficId'];
        $clientId = $request['clientId'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $equipPeidPath = $detailClientData['bgp']['interconexoes']['transito'][$transitoId]['peid'];
        $toSaveAnotherData = [
            'hostname' => $request['peVal']
        ];

        try {
            $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/transito/'.$transitoId.'/')->update($toSaveData);
            $this->database->getReference('clientes/'.$clientId.'/equipamentos/'.$equipPeidPath.'/')->update($toSaveAnotherData);
            return response()->json([
                'status' => 'ok'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => 'failed'
            ]);
        }
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
        $path = 'clientes/'.$clientId.'/bgp/interconexoes/transito/'.$toDeleteId;
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
