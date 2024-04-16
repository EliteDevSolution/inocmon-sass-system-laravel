<?php

namespace App\Http\Controllers\Admin\Upstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class IxController extends Controller
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
        $buscaBgpConexoes = $detailClientData['bgp']['interconexoes']['ix'] ?? [];
        $buscaBaseDadosIxbr = $this->database->getReference('lib/ixbr')->getSnapshot()->getValue();

        $toSendData = [
            'buscaBgp' => $buscaBgpConexoes,
            'buscaEquip' => $buscaEquipamentos,
            'optionVal' => $buscaBaseDadosIxbr
        ];

        return view('admin.upstreams.ix.index', compact('clientId', 'toSendData'));
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
        $ixbrData = $detailClientData['bgp']['interconexoes']['ix'] ?? [];
        $buscaBaseDadosIxbr = $this->database->getReference('lib/ixbr')->getSnapshot()->GetValue();

        return view('admin.upstreams.ix.create', compact('clientId', 'buscaEquipamentos','ixbrData', 'buscaBaseDadosIxbr'));

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
        $tipoConexao = "ix";
        $communityGroup = 2;
        $buscaBgpConexoes = $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao)->getSnapshot()->getValue() ?? [];
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

        $ixbr = $request['ixbr'];
        $pop = $request['pop'];
        $equipId = $request['equip'];
        $nomeDoGrupo = 'IX-'.$nextId.'-'.strtoupper($ixbr).'-'.strtoupper($pop);
        $ixbrData = $this->database->getReference('lib/ixbr/'.$ixbr)->getSnapshot()->getValue();
        $remoteas = $ixbrData['remoteas'];
        $lgremote = $ixbrData['lgremoteas'];
        $rs1v4 = $ixbrData['rs1v4'];
        $rs2v4 = $ixbrData['rs2v4'];
        $lgv4 = $ixbrData['lgv4'];
        $rs1v6 = $ixbrData['rs1v6'];
        $rs2v6 = $ixbrData['rs2v6'];
        $lgv6 = $ixbrData['lgv6'];

        $novoBgpIx = [
            'sigla'  => strtoupper($ixbr),
            'pop'    => strtoupper($pop),
            'remoteas'    => $remoteas,
            'rs1v4'    => $rs1v4,
            'rs2v4'    => $rs2v4,
            'lgv4'    => $lgv4,
            'rs1v6'    => $rs1v6,
            'rs2v6'    => $rs2v6,
            'lgv6'    => $lgv6,
            'peid'   => $equipId,
            'denycustomerin'   => 'yes',
            'nomedogrupo' => $nomeDoGrupo,
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
	    $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao.'/'.$nextId)->set($novoBgpIx);

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
        $ixId = $request['ixId'];
        $clientId = $request['clientId'];
        $tipoConexao = "ix";
        $communityGroup = 2;
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $buscaEquipamentos = $detailClientData['equipamentos'];
        $equipPeidPath = $detailClientData['bgp']['interconexoes']['ix'][$ixId]['peid'];

        $nomeDoGrupo = 'IX-'.$ixId.'-'.strtoupper($request['sigla']).'-'.strtoupper($request['popVal']);
        $ixBrData = $this->database->getReference('lib/ixbr/'.$request['sigla'])->getSnapshot()->getValue();
        $remoteas = $ixBrData['remoteas'] ?? '';
        $lgremoteas = $ixBrData['lgremoteas'] ?? '';
        $rs1v4 = $ixBrData['rs1v4'] ?? '';
        $rs2v4 = $ixBrData['rs2v4'] ?? '';
        $lgv4 = $ixBrData['lgv4'] ?? '';
        $rs1v6 = $ixBrData['rs1v6'] ?? '';
        $rs2v6 = $ixBrData['rs2v6'] ?? '';
        $lgv6 = $ixBrData['lgv6'] ?? '';
        $community0 = $this->database->getReference('clientes/'.$clientId.'/bgp/community0')->getSnapshot()->GetValue();

        // $toSaveAnotherData = [
        //     'hostname' => $request['peVal']
        // ];
        $toSaveData = [
                        'sigla'  => strtoupper($request['sigla']),
                        'pop'    => strtoupper($request['popVal']),
                        'remoteas'    => $remoteas,
                        'rs1v4'    => $rs1v4,
                        'rs2v4'    => $rs2v4,
                        'lgv4'    => $lgv4,
                        'rs1v6'    => $rs1v6,
                        'rs2v6'    => $rs2v6,
                        'lgv6'    => $lgv6,
                        'peid'   => $request['peVal'],
                        'localpref'   => $request['localpref'],
                        'medin'   => $request['medin'],
                        'denycustomerin'   => 'yes',
                        'nomedogrupo' => $nomeDoGrupo,
                        'communities' => [
                            'NO-EXPORT-'.$nomeDoGrupo => $community0.':2'.$ixId.'0',
                            'PREPEND-1X-'.$nomeDoGrupo => $community0.':2'.$ixId.'1',
                            'PREPEND-2X-'.$nomeDoGrupo => $community0.':2'.$ixId.'2',
                            'PREPEND-3X-'.$nomeDoGrupo => $community0.':2'.$ixId.'3',
                            'PREPEND-4X-'.$nomeDoGrupo => $community0.':2'.$ixId.'4',
                            'PREPEND-5X-'.$nomeDoGrupo => $community0.':2'.$ixId.'5',
                            'PREPEND-6X-'.$nomeDoGrupo => $community0.':2'.$ixId.'6'
                        ],
                ];

        try {
            $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/ix/'.$ixId.'/')->update($toSaveData);
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
        $path = 'clientes/'.$clientId.'/bgp/interconexoes/ix/'.$toDeleteId;
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
