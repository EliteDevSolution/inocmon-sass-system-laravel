<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IxbrController extends Controller
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
    public function index(Request $request)
    {
        $buscaLibIxbr = $this->database->getReference('lib/ixbr')->getSnapshot()->getValue();
        return view('admin.ixbr', compact('buscaLibIxbr'));
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
        $status = "";
        $id = $request['sigla'];
        if(isset($id)) {
            $novoIxbr = [
                'cidade' =>  $request['cidade'],
                'remoteas' =>  26162,
                'lgremoteas' => 20121,
                'rs1v4' => $request['rs1'],
                'rs2v4' => $request['rs2'],
                'lgv4'  => $request['lg4'],
                'rs1v6' => $request['rs16'],
                'rs2v6' => $request['rs26'],
                'lgv6'  => $request['lg6']
            ];
            try {
                $this->database->getReference('lib/ixbr/'.$id)->set($novoIxbr);
                $status = 'ok';
            } catch (\Throwable $th) {
                $status = 'failed';
            }
        } else {
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
