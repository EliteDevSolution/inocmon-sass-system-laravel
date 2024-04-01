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
        $layout = true;
        $clientId = $req->query()['client_id'];


        $equipments = $this->database->getReference('clientes/' . $clientId . '/equipamentos')->getSnapshot()->getValue();
        // $buscaBgpTransito = $database->getReference('clientes/' . $_GET["clienteid"] . '/bgp/interconexoes/transito')->getSnapshot();
        // $buscaBgpIx = $database->getReference('clientes/' . $_GET["clienteid"] . '/bgp/interconexoes/ix')->getSnapshot();
        // $buscaBgpClientes = $database->getReference('clientes/' . $_GET["clienteid"] . '/bgp/interconexoes/clientesbgp')->getSnapshot();
	    // $nomeCliente = $database->getReference('clientes/' . $_GET["clienteid"] . '/nome')->getSnapshot()->getValue();
        // $SenhaInocmon = $database->getReference('clientes/' . $_GET["clienteid"] . '/seguranca/senhainocmon')->getSnapshot()->getValue();
        // $asn = $database->getReference('clientes/' . $_GET["clienteid"] . '/bgp/asn')->getSnapshot()->getValue();
        // $community0  = $database->getReference('clientes/' . $_GET["clienteid"] . '/bgp/community0')->getSnapshot()->getValue();
        return view('admin.assetmanagement.mpls_pe', compact('layout', 'equipments'));
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
