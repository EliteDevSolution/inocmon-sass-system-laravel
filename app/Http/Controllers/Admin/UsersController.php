<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;

class UsersController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }
        $clients = $this->database->getReference('clientes')->getSnapshot()->getValue();
        $users = User::all();
        return view('admin.users.index', compact('users', 'clients'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }
        $clients = $this->database->getReference('clientes')->getSnapshot()->getValue();
        $users = User::all();
        $selectClients = [];
        foreach ($clients as $clientIndex => $client) {
            $clientForArray = [];
            $clientForArray = array( $clientIndex => $client['nome']. ' ('.$client['email']. ')' );
            $selectClients = array_merge($selectClients, $clientForArray);
        }

        $roles = Role::get()->pluck('name', 'name');
        $status = array("Pending"=>"Pending", "Approve"=>"Approve", "Reject"=>"Reject");
        $trial_types = array("0"=>"Basic", "1"=>"Pro");

        return view('admin.users.create', compact('roles', 'status', 'trial_types', 'selectClients'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        $data = $request->all();
        $user = User::create($data);
        $data['trial_type'] = 1;
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
        $user->save();
        // if (isset($request->trial_start) && isset($request->trial_end))
        // {
        //     $user->trial_start = $request->trial_start;
        //     $user->trial_end = $request->trial_end;

        // }
        // if (isset($request->trial_type)) {
        //     $user->trial_type = $request->trial_type;
        //     $user->save();
        // }
        return redirect()->route('users.index');
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }
        $clients = $this->database->getReference('clientes')->getSnapshot()->getValue();
        $users = User::all();
        $selectClients = [];
        foreach ($clients as $clientIndex => $client) {
            $clientForArray = [];
            $clientForArray = array( $clientIndex => $client['nome']. ' ('.$client['email']. ')' );
            $selectClients = array_merge($selectClients, $clientForArray);
        }

        $roles = Role::get()->pluck('name', 'name');

        $status = array("Pending"=>"Pending", "Approve"=>"Approve", "Reject"=>"Reject");
        $trial_types = array("0"=>"Basic", "1"=>"Pro");
        return view('admin.users.edit', compact('user', 'selectClients', 'roles', 'status', 'trial_types'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, User $user)
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }

        // $user->update(['field_name' => 'new value']);
        $user->update($request->all());
        if (isset($request->trial_start) && isset($request->trial_end))
        {
            $user->trial_start = $request->trial_start;
            $user->trial_end = $request->trial_end;
            $user->save();
        }
        if (isset($request->trial_type)) {
            $user->trial_type = $request->trial_type;
            $user->save();
        }
        if( $request['roles'][0] == 'administrator' || $request['roles'][0] == 'master' ) {
            $user->client_id = '';
            $user->update();
            $user->save();
        }
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);

        return redirect()->route('users.index');
    }

    public function show(User $user)
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }

        $user->delete();

        return redirect()->route('users.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage') && !Gate::allows('master_manage') )
        {
            return abort(401);
        }
        User::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

}
