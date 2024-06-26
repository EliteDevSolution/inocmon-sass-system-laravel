<?php

namespace App\Http\Controllers\Admin\Upstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class CdnController extends Controller
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
        $buscaEquipamentos = $detailClientData['equipamentos'] ?? [];
        $buscaBgpConexoes = $detailClientData['bgp']['interconexoes']['cdn'] ?? [];

        $toSendData = [
            'buscaBgp' => $buscaBgpConexoes,
            'buscaEquip' => $buscaEquipamentos
        ];

        return view('admin.upstreams.cdn.index', compact('clientId', 'toSendData'));
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
        $buscaEquipamentos= $detailClientData['equipamentos'] ?? [];
        $cdns = $detailClientData['bgp']['interconexoes']['cdn'] ?? [];
        $buscaBaseDadosIxbr = $this->database->getReference('lib/ixbr')->getSnapshot()->GetValue();

        return view('admin.upstreams.cdn.create', compact('clientId', 'buscaEquipamentos','cdns', 'buscaBaseDadosIxbr'));
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
        $tipoConexao = "cdn";
        $communityGroup = 4;
        $buscaBgpConexoes = $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao)->getSnapshot()->getValue() ?? [];
        $lastId = 0;
        foreach ($buscaBgpConexoes as $index => $value) {
            $lastId = $index;
        }
        $nextId = $lastId + 1;
        if($nextId < 10) {
            $nextId = '0'.$nextId;
        }
        $community0 = $this->database->getReference('clientes/'.$clientId.'/bgp/community0')->getSnapshot()->GetValue();

        $nome = $request['nome'];
        $pop = $request['pop'];
        $equipId = $request['equip'];
        $nomeDoGrupo = 'CDN-'.$nextId.'-'.strtoupper($nome).'-'.strtoupper($pop);
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
                'NO-EXPORT-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'0',
                'PREPEND-1X-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'1',
                'PREPEND-2X-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'2',
                'PREPEND-3X-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'3',
                'PREPEND-4X-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'4',
                'PREPEND-5X-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'5',
                'PREPEND-6X-'.$nomeDoGrupo => $community0.$communityGroup.$nextId.'6'
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

        $cdnId = $request['cdnId'];
        $clientId = $request['clientId'];
        $tipoConexao = "cdn";
        $communityGroup = 4;
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $equipPeidPath = $detailClientData['bgp']['interconexoes']['cdn'][$cdnId]['peid'];
        $community0 = $this->database->getReference('clientes/'.$clientId.'/bgp/community0')->getSnapshot()->GetValue();
	    $nomeDoGrupo = strtoupper($tipoConexao).'-'.$cdnId.'-'.$request['provedor'].'-'.strtoupper($request['popVal']);

        // $toSaveAnotherData = [
        //     'hostname' => $request['peVal']
        // ];

        $toSaveData = [
            'provedor'  => strtoupper($request['provedor']),
            'pop'    => strtoupper($request['popVal']),
            'remoteas'    => $request['asnVal'],
            'ipv4-01'    => $request['ipv401'],
            'ipv4-02'   => $request['ipv402'],
            'ipv6-01'    => $request['ipv601'],
            'ipv6-02'   => $request['ipv602'],
            'peid'   => $request['peVal'],
            'nomedogrupo' => $nomeDoGrupo,
            'communities' => [
                'NO-EXPORT-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'0',
                'PREPEND-1X-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'1',
                'PREPEND-2X-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'2',
                'PREPEND-3X-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'3',
                'PREPEND-4X-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'4',
                'PREPEND-5X-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'5',
                'PREPEND-6X-'.$nomeDoGrupo => $community0.$communityGroup.$cdnId.'6'
            ],
	    ];

        try {
            $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/cdn/'.$cdnId.'/')->update($toSaveData);
            // $this->database->getReference('clientes/'.$clientId.'/equipamentos/'.$equipPeidPath.'/')->update($toSaveAnotherData);
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
        $path = 'clientes/'.$clientId.'/bgp/interconexoes/cdn/'.$toDeleteId;
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
