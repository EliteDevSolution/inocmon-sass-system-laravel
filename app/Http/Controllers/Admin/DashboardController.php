<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class DashboardController extends Controller
{
    protected $database;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
        // $this->clientId = $request->query();
        /*Beafore go in dashboard check id*/
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
    */

    public function executeSshCommand(Request $request)
    {
        $status = '';
        $proxyId = $request['proxy'];
        $targetRrId = $request['rr'];
        $clientId = $request['clientId'];

        $proxyData = $this->database->getReference('clientes/' . $clientId . '/sondas/'.$proxyId)->getSnapshot()->getValue();
        $targetData = $this->database->getReference('clientes/' . $clientId . '/rr/'.$targetRrId)->getSnapshot()->getValue();

        $ssh = new SSH2($proxyData['ipv4'], $proxyData['portassh']);

        $proxyUser = $proxyData['user'] ?? '';
        $proxyPwd = $proxyData['pwd'] ?? '';

        $userInform = $this->database->getReference('clientes/' . $clientId . '/seguranca/')->getSnapshot()->getValue();
        $userInocmon = $userInform['userinocmon'];
        $senhaInocmon = $userInform['senhainocmon'];

        if (!$proxyUser) { $proxyUser = $userInocmon; }
        if (!$proxyPwd) { $proxyPwd = $senhaInocmon; }  /*reference this part*/

        try {
            if ($ssh->login($proxyUser, $proxyPwd)) {
                $status = 'ok';
                $output = $ssh->exec('ls -l');
            } else {
                $status = 'failed';
            }
        } catch (\Throwable $th) {
            $status = 'failed';
        }

        $targetRrIp = $targetData['routerid'];
        $targetRrPort = $targetData['porta'];
        $targetRrUser = $targetData['user'];
        $targetRrPwd = $targetData['pwd'];
        $targetRrVendor = $targetData['template-vendor'];
        $targetRrFamily = $targetData['template-family'];

        if (!$targetRrUser) { $targetRrUser = $userInocmon; }
        if (!$targetRrPwd) { $targetRrPwd = $senhaInocmon; }

        $finalCommand = $this->database->getReference('lib/commands/getospflsdb/'.$targetRrVendor.'/'.$targetRrFamily)->getSnapshot()->getValue();
        $getOspfLsdb = $ssh->exec('inoc-command '.$targetRrIp.' '. $targetRrUser.' \''.$targetRrPwd.'\' '.$targetRrPort.' \''.$finalCommand.'\' &');
        $this->database->getReference('clientes/'.$clientId.'/ospf-lsdb/vendor')->set($targetRrVendor);
        $this->database->getReference('clientes/'.$clientId.'/ospf-lsdb/data')->set($getOspfLsdb);
        $layout = true;

        return response()->json([
            'status' => $status
        ]);
    }

    public function index(Request $request)
    {
        if(isset($request->query()['client_id'])) {
            $clientId = $request->query()['client_id'];
            $path = 'clientes/'.$clientId;

            $client = $this->database->getReference($path)->getValue();

            $bgpInterConectors = $this->database->getReference($path)->getSnapshot()->GetValue()['bgp']['interconexoes'] ?? '';
            $buscaBgpTransito = $bgpInterConectors['transito'] ?? '';
            $buscaBgpIx = $bgpInterConectors['ix'] ?? '';
            $buscaBgpPeering = $bgpInterConectors['peering'] ?? '';
            $buscaBgpCdn = $bgpInterConectors['cdn'] ?? '';
            $buscaBgpClientes = $bgpInterConectors['clientesbgp'] ?? '';

            if(!is_array($buscaBgpTransito)) {
                $countBgpTransito = 0;
            } else {
                $countBgpTransito = count($buscaBgpTransito);
            }

            if(!is_array($buscaBgpIx)) {
                $countBgpIx = 0;
            } else {
                $countBgpIx = count($buscaBgpIx);
            }

            if(!is_array($buscaBgpPeering)) {
                $countBgpPeering = 0;
            } else {
                $countBgpPeering = count($buscaBgpPeering);
            }

            if(!is_array($buscaBgpCdn)) {
                $countBgpCdn = 0;
            } else {
                $countBgpCdn = count($buscaBgpCdn);
            }

            if(!is_array($buscaBgpClientes)){
                $clientsCount = 0; }
            else {
                $clientsCount = count($buscaBgpClientes);
            }

            $upstreamsCount = ($countBgpTransito + $countBgpIx + $countBgpPeering + $countBgpCdn);
            $equipmentCount = 0;
            if(is_array($client['equipamentos'])) {
                $equipmentCount = count($client['equipamentos']);
            }
            $sondas = $client['sondas'] ?? [];

            $rr = $client['rr'] ?? []; /*Current rr property is not here*/

            $clienteDatabase  = $client;

            $UsoBancoDeDados = mb_strlen(json_encode($clienteDatabase, JSON_NUMERIC_CHECK), '8bit');
            $databaseInuse = formatBytes($UsoBancoDeDados);

            if( array_key_exists('license', $client) ) {
                $license = $client['license'];
            }
            else {
                $license = 'starter';
            }

            $license = 'starter';
            $fraquiaBanco = $this->database->getReference('lib/license/'.$license.'/databasesize')->getSnapshot()->getValue();
            if($fraquiaBanco != 0) {
                $usoDaFranquia = ($UsoBancoDeDados / $fraquiaBanco * 100);
                $usoDaFranquia = round($usoDaFranquia, 0);
            } else {
                $usoDaFranquia = 0;
            }

            $databasePercent = $usoDaFranquia;

            $getOspfLsdbData = $this->database->getReference($path . '/ospf-lsdb/data')->getSnapshot()->getValue() ?? '';
            $getOspfLsdbVendor = $this->database->getReference($path . '/ospf-lsdb/vendor')->getSnapshot()->getValue() ?? '';

            $getOspfLsdbData = $client['ospf-lsdb']['data'] ?? '';
            $getOspfLsdbVendor = $client['ospf-lsdb']['vendor'] ?? '';

            if($getOspfLsdbData == null) $getOspfLsdbData = "";
            if($getOspfLsdbVendor == null) $getOspfLsdbVendor = "";

            $toDownloadFileName = 'ospf-lsdb-'.$getOspfLsdbVendor.'-'.$clientId;

            if($getOspfLsdbData) {

                $targetPath = 'configuracoes';
                if(!file_exists(public_path() . '/storage/' . $targetPath))
                {
                    @mkdir(public_path() . '/storage/' . $targetPath, 0777, true);
                    chmod($directoryPath, 0777); // Set the directory permissions explicitly
                }
                Storage::disk('local')->put('public/configuracoes/'.$toDownloadFileName, $getOspfLsdbData);

            }
            $buscaRr = $client['rr'];
            $dashboardData = [
                'upstreamCount' => $upstreamsCount,
                'clientsCount' => $clientsCount,
                'equipmentCount' => $equipmentCount,
                'databaseInuse' => $databaseInuse,
                'databasePercent' => $databasePercent,
                'sondas' => $sondas,
                'ospData' => $getOspfLsdbData,
                'dspVendor' => $getOspfLsdbVendor,
                'rr' => $buscaRr,
                'fileName' => $toDownloadFileName
            ];

            return view('admin.dashboard.index', compact('dashboardData', 'clientId'));
        } else {
            return redirect()->to('client');
        }
    }
    public function downloadFile (Request $request) {
        if(isset($request->filename)) {
            return response()->download(storage_path("app/configuracoes/{$request->filename}"), $request->filename);
        } else {
            abort(404);
        }
    }

}
