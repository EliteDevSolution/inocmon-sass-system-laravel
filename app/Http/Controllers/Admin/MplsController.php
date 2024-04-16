<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MplsController extends Controller
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
        $buscaTemplateVendor = $this->database->getReference('lib/templates/pe')->getSnapshot()->getValue() ?? [];
        $clientDetailData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $buscaEquipment = $clientDetailData['equipamentos'] ?? [];
        $buscaBgpTransito = $clientDetailData['bgp']['interconexoes']['transito'] ?? '';
        $buscaBgpIx = $clientDetailData['bgp']['interconexoes']['ix'] ?? '';
        $buscaBgpClientes = $clientDetailData['bgp']['interconexoes']['clientesbgp'] ?? '';
        $nomeClient = $clientDetailData['nome'] ?? '';
        $senhaInocmon = $clientDetailData['seguranca']['senhainocmon'] ?? '';
        $asn = $clientDetailData['bgp']['asn'] ?? '';
        $community = $clientDetailData['bgp']['community0'] ?? '';
        $toSendData = [
            'equipments' => $buscaEquipment
        ];
        return view('admin.assetmanagement.mpls_pe', compact('toSendData', 'clientId', 'buscaTemplateVendor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $clientId = $request->query()['client_id'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $buscaTemplates = $this->database->getReference('lib/templates/pe')->getSnapshot()->getValue();
        return view('admin.network.pe', compact('clientId' ,'buscaTemplates'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "";
        $clientId = $request['client_id'];
	    try {
            $key = $this->database->getReference('clientes/'.$clientId.'/equipamentos')->push()->getKey();
            $data = [
                'configs' => [],
                'debug' => "ideal",
                'grupo-ibgp' => "",
                'hostname' => $request['hostname'],
                'porta' => $request['porta'],
                'protocolo' => $request['protocolo'],
                'pwd' => $request['senha'],
                'routerid' => $request['routerId'],
                'template-vendor' =>$request['vendor'],
                'template-family' => $request['family'],
                'user' => $request['user']
            ];

            $this->database->getReference('clientes/'.$clientId.'/equipamentos/' . $key)->set($data);
            $status = "success";
            return redirect()->back()->with('success', $status);;

        } catch(Exception  $err) {
            $status = 'failed';
            return redirect()->back()->with('failed', $status);
        }

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
        $equipId = $request['equipId'];
        $toSaveData = [
            'hostname' => $request['hostName'],
            'routerid' => $request['routerId'],
            'template-vendor' => $request['vendor'],
            'template-family' => $request['family'],
            'protocolo' => $request['protocolo'],
            'porta' => $request['porta'],
            'user' => $request['user'],
            'pwd' => $request['pwd']
        ];

        $proxyId = $request['proxyId'];
        $clientId = $request['clientId'];

        $this->database->getReference('clientes/'.$clientId.'/equipamentos/'.$equipId)->update($toSaveData);

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
        $clientId = $request['clientId'];
        $toDeleteId = $request['toDeleteId'];
        $path = 'clientes/'.$clientId.'/equipamentos/'.$toDeleteId;
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
