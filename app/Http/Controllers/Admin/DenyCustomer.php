<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DenyCustomer extends Controller
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
    public function index()
    {
        // $templates = $this->database->getReference('lib/templates/bgp/transito')->getSnapshot()->getValue();
        $buscaTemplateActivate = $this->database->getReference('lib/templates/bgp/deny-customer-in/activate')->getSnapshot()->getValue();
	    $buscaTemplateDeactivate = $this->database->getReference('lib/templates/bgp/deny-customer-in/deactivate')->getSnapshot()->getValue();
        return view('admin.denycustomer', compact('buscaTemplateActivate', 'buscaTemplateDeactivate'));
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
        $status = '';
        $toPlace = $request['toPlace'];
        $toUpdateVal = $request['textAreaVal'];
        $firstPlace = $request['dir1'];
        $secondPlace = $request['dir2'];
        $direct = $request['direction'];

        if($direct  == 'activity') {
            if(isset($toUpdateVal)) {
                $toSaveData = [
                    $toPlace => $toUpdateVal
                ];
                try {
                    $this->database->getReference('lib/templates/bgp/deny-customer-in/activate/'.$firstPlace.'/'.$secondPlace)->update($toSaveData);
                    $status= 'ok';
                } catch (\Throwable $th) {
                    $status = 'failed';
                }
            }
        } else if($direct == 'deactivity') {
            if(isset($toUpdateVal)) {
                $toSaveData = [
                    $toPlace => $toUpdateVal
                ];
                try {
                    $this->database->getReference('lib/templates/bgp/deny-customer-in/deactivate/'.$firstPlace.'/'.$secondPlace)->update($toSaveData);
                    $status= 'ok';
                } catch (\Throwable $th) {
                    $status = 'failed';
                }
            }
        }
        return response()->json([
            'status' => $status
        ]);

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
