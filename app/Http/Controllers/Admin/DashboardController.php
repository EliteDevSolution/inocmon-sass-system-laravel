<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Template;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;


class DashboardController extends Controller
{
    protected $database;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();

        /*Beafore go in dashboard check id*/
    }

    public function formatBytes($bytes, $precision = 2){
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = intdiv(log($bytes, 1024), 1);
        $pow = min($pow, count($units) - 1);
        $bytes /= 1024 ** $pow;
        return round($bytes, $precision) . ' ' . $units[$pow];
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */ 


    public function executeSshCommand()
    {
        $ssh = new SSH2('example.com');

        if ($ssh->login('username', 'password')) {
            $output = $ssh->exec('ls -l');
            return response()->json(['output' => $output]);
        } else {
            return response()->json(['error' => 'SSH login failed']);
        }
    }


    public function index(Request $request)
    {
        $layout = true;
        $users = \App\User::all();
        $clients = $this->database->getReference('clientes')->getValue();  
        if(count($request->query()) > 0) {
            $key = $request->query();
            $path = 'clientes/'.$key['client_id'];
            $client = $this->database->getReference($path)->getValue();
            $upstreamsCount = count($client['bgp']['interconexoes']); /*add '/transito'*/
            $clientsCount = count($client['bgp']['interconexoes']); /*add '/clientesbgp'*/
            $equipmentCount = count($client['equipamentos']);
            $sondas = $client['sondas'];
            // $rr = $client['rr']; /*Current rr property is not here*/

            $clienteDatabase  = $this->database->getReference('clientes/' . $path)->getSnapshot();
            $clienteDatabase = $clienteDatabase->GetValue();
            $UsoBancoDeDados = mb_strlen(json_encode($clienteDatabase, JSON_NUMERIC_CHECK), '8bit');
            
            $UsoBancoDeDadosFormatado = 0;
            if($UsoBancoDeDados != null ) {
                $UsoBancoDeDadosFormatado = $this->formatBytes($UsoBancoDeDados);
            }
          
            $databaseInuse = $UsoBancoDeDadosFormatado;
            
            $license = $this->database->getReference('clientes/' . $path . '/license')->getSnapshot()->getValue();
            if($license == null) {
                $license = "starter";
            }

            $fraquiaBanco = $this->database->getReference('lib/license/'.$license.'/databasesize')->getSnapshot()->getValue();
            $usoDaFranquia = ($UsoBancoDeDados / $fraquiaBanco * 100);
            $usoDaFranquia = round($usoDaFranquia, 0);
          
            $databasePercent = $usoDaFranquia;

            $getOspfLsdbData = $this->database->getReference('clientes/' . $path . '/ospf-lsdb/data')->getSnapshot()->getValue();
            $getOspfLsdbVendor = $this->database->getReference('clientes/' . $path . '/ospf-lsdb/vendor')->getSnapshot()->getValue();

            if($getOspfLsdbData == null) $getOspfLsdbData = true;
            if($getOspfLsdbVendor == null) $getOspfLsdbVendor = false;


            $dashboardData = [
                'upstreamCount' => $upstreamsCount,
                'clientsCount' => $clientsCount,
                'equipmentCount' => $equipmentCount,
                'databaseInuse' => $databaseInuse,
                'databasePercent' => $databasePercent,
                'sondas' => $sondas,
                'getOspData' => $getOspfLsdbData,
                'getDspVendor' => $getOspfLsdbVendor,
                'rr' => ''
            ];
            dd($client);
            return view('admin.dashboard.index', compact('users', 'layout', 'dashboardData'));
        }
        else {
            return redirect()->to('client');
        }
    }
}
