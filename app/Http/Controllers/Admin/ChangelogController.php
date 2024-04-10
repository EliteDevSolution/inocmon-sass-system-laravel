<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangelogController extends Controller
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
        $changelogData = $this->database->getReference('lib/changelog')->getSnapshot()->getValue();
        return view('admin.changelog', compact('clientId', 'changelogData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $clientId = $request->query()['client_id'];
        $changeId = "";
        $todo = $request['todo'];
        if(isset($request['change_id'])) {
            $changeId = $request['change_id'];
            $changelogData = $this->database->getReference('lib/changelog/'.$changeId)->getSnapshot()->getValue();
        } else {
            $changelogData = "";
        }
        return view('admin.log_update_add', compact('clientId', 'todo', 'changelogData', 'changeId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newId = $this->database->getReference('lib/changelog')->push()->getKey();
        $toSaveData = [
            'content' => $request['changelog'],
            'date' => $request['changelogdata'],
            'versao' => $request['version']
        ];
        $status = "";
        try {
            $this->database->getReference('lib/changelog/'.$newId )->set($toSaveData);
            $status = "ok";
        } catch (\Throwable $th) {
            $status = "failed";
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
        $changeId = $request['changeId'];

        $toUpdateData = [
            'content' => $request['changelog'],
            'date' => $request['changelogdata'],
            'versao' => $request['version']
        ];

        $status = '';

        try {
            $this->database->getReference('lib/changelog/'.$changeId)->update($toUpdateData);
            $status = 'ok';
        } catch (\Throwable $th) {
            $status = 'failed';
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
