<?php

namespace App\Http\Controllers\Admin;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function __construct()
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }

    public function index()
    {
        $user = \App\User::all();
        $layout = false;
        $clients = $this->database->getReference('clientes')->getValue();
        return view('admin.client_home', compact('clients', 'layout'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \App\User::all();
        $clients = $this->database->getReference('clientes')->getValue();
        $layout = false;
        return view('admin.client_add', compact('clients', 'layout'));
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

	    try {
            $key = $this->database->getReference('clientes')->push()->getKey();
            $message = 'a proxima chave será: '.$key;

            $data = [
                'bgp' => [
                    'asn' => $request->all()['asn'],
                    'community0' => $request->all()['community0'],
                    'interconexoes' => '',
                    'ipv4bgpmultihop' => $request->all()['ipv4bgpmultihop'],
                    'ipv6bgpmultihop' => $request->all()['ipv6bgpmultihop']
                ],
                'email' => $request->all()['email'],
                'equipamentos' => [],
                'nome' => $request->all()['nome'],
                'seguranca' => [
                    'nomedogrupo' => $request->all()['nomedogrupo'],
                    'senhainocmon' => $request->all()['senhainocmon'],
                    'senhainocmoncifrada' => $request->all()['senhainocmoncifrada'],
                    'snmpcommunity' => $request->all()['snmpcommunity'],
                    'userinocmon' => $request->all()['userinocmon']
                ],
                'sondas' => '',
                'status' => $request->all()['status']
            ];

            $this->database->getReference('clientes/' . $key)->set($data);

            $status = "success";

        } catch(Exception  $err) {
            $status = $err;
        }
        $layout = false;
        $clients = $this->database->getReference('clientes')->getValue();
        return redirect()->route("client.create");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($key)
    {
        // return view('admin.dashboard.index' ,compact('key'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($key)
    {
        $user = \App\User::all();
	    $path = 'clientes/'.$key;
        $client = $this->database->getReference($path)->getValue();
        $clients = $this->database->getReference('clientes')->getValue();
        $layout = false;
        return view('admin.client_edit' ,compact('client', 'clients', 'key', 'layout'));

        // return redirect()->route('dashboard', ['key' => 'key']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $key)
    {
        try {
            $message = 'a proxima chave será: '.$key;

            $data = [
                'bgp' => [
                    'asn' => $request->all()['asn'],
                    'community0' => $request->all()['community0'],
                    'interconexoes' => [],
                    'ipv4bgpmultihop' => $request->all()['ipv4bgpmultihop'],
                    'ipv6bgpmultihop' => $request->all()['ipv6bgpmultihop']
                ],
                'email' => $request->all()['email'],
                'equipamentos' => '',
                'nome' => $request->all()['nome'],
                'seguranca' => [
                    'nomedogrupo' => $request->all()['nomedogrupo'],
                    'senhainocmon' => $request->all()['senhainocmon'],
                    'senhainocmoncifrada' => $request->all()['senhainocmoncifrada'],
                    'snmpcommunity' => $request->all()['snmpcommunity'],
                    'userinocmon' => $request->all()['userinocmon']
                ],
                'sondas' => [],
                'status' => $request->all()['status']
            ];

            $this->database->getReference('clientes/' . $key)->update($data);

            $status = "success";

        } catch(Exception  $err) {
            $status = $err;
        }

        $clients = $this->database->getReference('clientes')->getValue();
        $layout = false;
        return view('admin.client_home' ,compact('status', 'clients', 'layout'));
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
