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
        if( auth()->user()->hasRole("administrator") || auth()->user()->hasRole("master") ) {
            $status = session('success');
            $clients = $this->database->getReference('clientes')->getValue();
            return view('admin.client_home', compact('clients', 'status'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = "";
        return view('admin.client_add', compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $status = "success";

	    try {
            $key = $this->database->getReference('clientes')->push()->getKey();
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
                'equipamentos' => [],
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

            $this->database->getReference('clientes/' . $key)->set($data);

            $status = "success";
            return redirect()->back()->with('success', $status);

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
	    $path = 'clientes/'.$key;
        $client = $this->database->getReference($path)->getValue();

        return view('admin.client_edit' ,compact('client', 'key'));
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
            $status = "failed";
        }
        $clients = $this->database->getReference('clientes')->getValue();
        return redirect()->route('client.index')->with('success', $status);
        // return view('admin.client_home' ,compact('status', 'clients'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $toDeleteId = $request['toDeleteId'];
        $path = 'clientes/'.$toDeleteId;
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
