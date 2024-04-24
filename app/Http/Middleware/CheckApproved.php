<?php

namespace App\Http\Middleware;

use Closure;

class CheckApproved
{
    public function __construct()
    {

    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->status != "Approve") {
            return redirect()->back()->with('error', "Você ainda não está aprovado!");
        }
        $clientId = auth()->user()->client_id ?? '';
        if ( !(auth()->user()->hasRole("administrator") || auth()->user()->hasRole("master")) ) {
            $database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
            $clients = $database->getReference('clientes')->getValue();
            if($clientId === '') {
                return redirect()->back()->with('error', "Isso não tem cliente!");
            } else {
                foreach ($clients as $index => $value) {
                    $flag = false;
                    if($index == $clientId) {
                        $flag = true;
                        return $next($request);
                    }
                }
                if($flag == false) {
                    return redirect()->back()->with('error', "Desculpe, mas seu cliente não existe!");
                }
            }
        } else {
            return $next($request);
        }
    }
}
