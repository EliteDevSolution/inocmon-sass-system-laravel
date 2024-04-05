<?php

namespace App\Http\Controllers\Admin\Upstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class PeerController extends Controller
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
        $buscaBgpConexoes = $detailClientData['bgp']['interconexoes']['peering'];

        $toSendData = [
            'buscaBgp' => $buscaBgpConexoes,
            'buscaEquip' => $buscaEquipamentos
        ];

        return view('admin.upstreams.peering.index', compact('clientId', 'toSendData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaEquipamentos= $detailClientData['equipamentos'];
        $cdns = $detailClientData['bgp']['interconexoes']['peering'];
        $buscaBaseDadosIxbr = $this->database->getReference('lib/ixbr')->getSnapshot()->GetValue();

        return view('admin.upstreams.peering.create', compact('clientId', 'buscaEquipamentos','cdns', 'buscaBaseDadosIxbr'));
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
        $tipoConexao = "peering";
        $communityGroup = 3;
        $buscaBgpConexoes = $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao)->getSnapshot()->getValue();
        $lastId = 0;
        foreach ($buscaBgpConexoes as $index => $value) {
            $lastId = $index;
        }
        $nextId = $lastId + 1;
        if($nextId < 10) {
            $nextId = '0'.$nextId;
        }
        $communityGroup = 1;
        $community0 = $this->database->getReference('clientes/'.$clientId.'/bgp/community0')->getSnapshot()->GetValue();

        $nome = $request['nome'];
        $pop = $request['pop'];
        $equipId = $request['equip'];
        $nomeDoGrupo = 'PEERING-'.$nextId.'-'.strtoupper($nome).'-'.strtoupper($pop);
        $remoteas = $request['asn'];
        $ipv401 = $request['ipv401'];
        $ipv402 = $request['ipv402'];
        $ipv601 = $request['ipv601'];
        $ipv602 = $request['ipv602'];
        $peid = $equipId;
        $denycustomerin = $request['check'];


        $novoBgp = [
            'provedor'  => strtoupper($nome),
            'pop'    => strtoupper($pop),
            'remoteas'    => $remoteas,
            'ipv4-01'    => $ipv401,
            'ipv4-02'    => $ipv402,
            'ipv6-01'    => $ipv601,
            'ipv6-02'    => $ipv602,
            'peid'    => $peid,
            'denycustomerin'    => $denycustomerin,
            'nomedogrupo'   => $nomeDoGrupo,
            'communities' => [
                'NO-EXPORT-'.$nomeDoGrupo => $community0.':2'.$nextId.'0',
                'PREPEND-1X-'.$nomeDoGrupo => $community0.':2'.$nextId.'1',
                'PREPEND-2X-'.$nomeDoGrupo => $community0.':2'.$nextId.'2',
                'PREPEND-3X-'.$nomeDoGrupo => $community0.':2'.$nextId.'3',
                'PREPEND-4X-'.$nomeDoGrupo => $community0.':2'.$nextId.'4',
                'PREPEND-5X-'.$nomeDoGrupo => $community0.':2'.$nextId.'5',
                'PREPEND-6X-'.$nomeDoGrupo => $community0.':2'.$nextId.'6'
            ],
        ];

	    $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao.'/'.$nextId)->set($novoBgp);

        return response()->json([
            'status' => 'ok',
            'addedData' => [
                'id' => $nextId,
                'remoteas' => $remoteas,
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

        $peerId = $request['peerId'];
        $clientId = $request['clientId'];

        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $equipPeidPath = $detailClientData['bgp']['interconexoes']['peering'][$peerId]['peid'];

        $toSaveAnotherData = [
            'hostname' => $request['peVal']
        ];

        try {
            $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/peering/'.$peerId.'/')->update($toSaveData);
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
    public function destroy($id)
    {
        //
    }
}
