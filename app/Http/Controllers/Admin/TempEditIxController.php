<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TempEditIxController extends Controller
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
        $templates = $this->database->getReference('lib/templates/bgp/ix')->getSnapshot()->getValue();
        return view('admin.templateIx-edit-bgp', compact('templates'));
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
        $status = "";
        $todo = $request['todo'];
        $vendor = $request['vendor'];
        $newFamily = $request['novaFamily'];
        $family = $request['family'];
        $configSection = $request['configSection'];
        $firstId = $request['firstId'];
        $secondId = $request['secondId'];
        $thirdId = $request['thirdId'];
        $textVal = $request['textVal'];
        $notification = '';
        if($todo == 'family-update') {
            if(isset($newFamily)) {
                try {
                    $toSaveData = [
                        $newFamily => $newFamily
                    ];
                    $this->database->getReference('lib/templates/bgp/ix/'.$vendor)->update($toSaveData);
                    $status = "ok";
                } catch (\Throwable $th) {
                    $status = "failed";
                }
                $notification = 'Family '.$newFamily.' cadastrada!';
            }
        } else if($todo == 'config-section') {
            if(isset($family)) {
                try {
                    $toSaveData = [
                        $family => []
                    ];
                    $this->database->getReference('lib/templates/bgp/ix/'.$configSection)->update($toSaveData);
                    $status = "ok";
                } catch (\Throwable $th) {
                    $status = "failed";
                }
                $notification = 'Config Section'.$family.'  cadastrada!';
            }
        } else {
            try {
                $status = 'ok';
                $toSaveData = [
                    $firstId => $textVal
                ];
                $this->database->getReference('lib/templates/bgp/ix/'.$thirdId.'/'.$secondId)->update($toSaveData);
            } catch (\Throwable $th) {
                $status = 'failed';
            }
            $notification = 'dados invalidos';
        }
        return response()->json([
            'status' => $status,
            'notification' => $notification
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
