<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class PRTemplateController extends Controller
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


    public function applyBaseConfig(Request $req) {

        $clientId = $req['clientId'];
        $rrId = $req['rrId'];
        $sondaId = $req['sondaId'];
        $status = "";
        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $routerId = $detailClientData['rr'][$rrId]['routerid'] ?? '';
        $hostName = $detailClientData['rr'][$rrId]['hostname'] ?? '';
        $porta = $detailClientData['rr'][$rrId]['porta'] ?? '' ;
        $user = $detailClientData['rr'][$rrId]['user'] ?? '';
        $pwd = $detailClientData['rr'][$rrId]['pwd'] ?? '';
        $userInocmon = $detailClientData['seguranca']['userinocmon'];
        $senhaInocmon = $detailClientData['seguranca']['senhainocmon'];

        $debugDir = 'clientes/'.$clientId.'/rr/'.$rrId.'/debug';
        $debugFile = 'DEBUG-'.$clientId;
        $this->database->getReference($debugDir)->set('preparando config base para RR'.$rrId.': '.$hostName);

        if (!$user || $user == '') {
             $user = $userInocmon;
        }
        if (!$pwd || $pwd == '') {
            $pwd = $senhaInocmon;
        }

        $configRrFinal = '';
        if( array_key_exists('configs', $detailClientData['rr'][$rrId])) {
            $configRrFinal = $detailClientData['rr'][$rrId]['configs']['rrbaseconfig'] ?? '';
        } else {
            $configRrFinal = '';
        }

    	$configRrFinal = str_replace("<br />","",$configRrFinal);
    	$configFileNameRr = 'CONFIG-RR'.$rrId.'-'.$hostName.'-'.$clientId;
        $uploadFilePath = 'public/configuracoes/'.$configFileNameRr;

        try {

            if(!file_exists(public_path() . '/storage/configuracoes'))
            {
                @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
            }
            Storage::disk('local')->put($uploadFilePath , $configRrFinal);
            $status = "ok";
        } catch (\Throwable $th) {
            $status = 'failed ---';
        }

	    $this->database->getReference($debugDir)->set('arquivo '.$configFileNameRr.' gerado com sucesso! preparando transferência...');

        $sondaHostName = $detailClientData['sondas'][$sondaId]['hostname'] ?? '';
        $sondaIpv4 = $detailClientData['sondas'][$sondaId]['ipv4'] ?? '';
        $sondaPortaSsh = $detailClientData['sondas'][$sondaId]['portassh'] ?? '';
        $sondaUser = $detailClientData['sondas'][$sondaId]['user'] ?? '';
        $sondaPwd = $detailClientData['sondas'][$sondaId]['pwd'] ?? '';

        $this ->database->getReference($debugDir)->set('conectando ao proxy: '.$sondaHostName.' '.$sondaIpv4.' '.$sondaPortaSsh.' '.$sondaUser.' '.base64_encode($sondaPwd));
	    $ssh = new SSH2($sondaIpv4, $sondaPortaSsh);

        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);

        try {
            if (!$ssh->login($sondaUser, $sondaPwd)) {
                $status = "failed 000";
                $this->database->getReference($debugDir)->set('falha de login no proxy...');
            } else {
                $status = "ok";
                $ssh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');
                $scp = new SCP($ssh);

                $this->database->getReference($debugDir)->set('inicindo copia do arquivo '.$configFileNameRr);

                $scp->put($configFileNameRr, public_path() . '/storage/configuracoes/'.$configFileNameRr, 1);
                sleep(2);

                $lineCount = substr_count($configRrFinal, "\n");

                $this->database->getReference($debugDir)->set('iniciando config em RR'.$rrId.' '.$hostName.'... tempo estimado: '.$lineCount.'s');

                // $comandoRemoto = $ssh->exec('inoc-config '.$routerId.' '.$user.' \''.$pwd.'\' '.$porta.' '.public_path().'/storage/configuracoes/'.$configFileNameRr.' '.$debugFile.' & ');
                $comandoRemoto = $ssh->exec('inoc-config '.$routerId.' '.$user.' \''.$pwd.'\' '.$porta.' '.$configFileNameRr.' '.$debugFile.' & ');
                //dd($comandoRemoto,  $configFileNameRr, $configRrFinal);

                if (str_contains($comandoRemoto, 'Err')) {
                    $this->database->getReference($debugDir)->set('Erro de login em: '.$hostName.': '.$comandoRemoto);
                }else{
                    for ($i = 0; $i < $lineCount; $i++){
                        $tempoEstimado = ($lineCount - $i);
                        $progresso = ($i / $lineCount  * 100);
                        $progresso = round($progresso, 0);
                        $this->database->getReference($debugDir)->set($progresso.'% aplicando config em '.$hostName.' tempo estimado: '.$tempoEstimado.'s ');
                        sleep(1);
                    }
                }
            }
        } catch (\Throwable $th) {
            $status = 'failed 111';
        }

		$this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...');

        $lineCount = 15;
        for ($i = 0; $i < $lineCount; $i++){
            $tempoEstimado = ($lineCount - $i);
            $progresso = ($i / $lineCount  * 100);
            $progresso = round($progresso, 0);
            $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...'.$progresso.'%');
        }

	    $ssh = new SSH2($sondaIpv4, $sondaPortaSsh);

        $relatorio = 'new report';
        try {
            if (!$ssh->login($sondaUser, $sondaPwd))
            {
                $status = "failed 222";
                $this->database->getReference($debugDir)->set('falha de login no proxy...');
            } else {
                $relatorio = $ssh->exec('awk \'/config begin -> '.$configToken.'/ { f = 1 } f\' '.$debugFile.'.log');
                sleep(3);
                $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...100%');
                $now = date("h-i-sa").'-'.date("Y-M-d");
                $this->database->getReference('clientes/'.$clientId.'/rr/'.$rrId.'/relatorios/'.$now)->set($relatorio);
                $this->database->getReference($debugDir)->set('Configuração concluída!');
                $this->database->getReference($debugDir)->set('idle');
                $status = "ok";
            }
        } catch (\Throwable $th) {
            $status = "failed 333";
        }
        return response()->json([
            'status' => $status,
            'relatorio' => nl2br($relatorio),
        ]);
    }

    public function applyConfigPes(Request $req) {
        $clientId = $req['clientId'];
        $rrId = $req['rrId'];
        $sondaId = $req['sondaId'];
        $status = "";
        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();

        $debugDir = 'clientes/'.$clientId.'/rr/'.$rrId.'/debug';
        $debugFile = 'DEBUG-'.$clientId;
        $templateVendor = $detailClientData['rr'][$rrId]['template-vendor'];
        $templateFamily = $detailClientData['rr'][$rrId]['template-family'];
        $buscaEquipamentos = $detailClientData['equipamentos'];

    	$this->database->getReference($debugDir)->set('preparando config BGP com PES');

        $dir = 'lib/templates/rr/'.$templateVendor.'/'.$templateFamily.'/rr-novope-config';
	    $getTemplate = $this->database->getReference($dir)->getSnapshot()->getValue();
        $equipIds = json_decode($req['checkedEquipArray'], true);
        $asn = $detailClientData['bgp']['asn'];

        if(sizeof($equipIds) > 0){
            $this->database->getReference($debugDir)->set('preparando config BGP com');
        } else {
            return;
        }

        $configRrFinal = "";

        for ($i = 0; $i < count($equipIds); $i++) {
            $equipRouterId = $detailClientData['equipamentos'][$equipIds[$i]]['routerid'] ?? '';
            $equiphostName = $detailClientData['equipamentos'][$equipIds[$i]]['hostname'] ?? '';
            $equipgrupoIbgp = $detailClientData['equipamentos'][$equipIds[$i]]['grupo-ibgp'] ?? '';
            $configRr = str_replace("%asn%",$asn, $getTemplate);
			$configRr = str_replace("%routerid%",$equipRouterId, $configRr);
			$configRr = str_replace("%hostname%",$equiphostName, $configRr);
			$configRr = str_replace("%grupo-ibgp%",$equipgrupoIbgp, $configRr);
			$configRrFinal .= $configRr;
        }

        $routerId = $detailClientData['rr'][$rrId]['routerid'];
        $hostName = $detailClientData['rr'][$rrId]['hostname'];
        $porta = $detailClientData['rr'][$rrId]['porta'];
        $user = $detailClientData['rr'][$rrId]['user'];
        $pwd = $detailClientData['rr'][$rrId]['pwd'];
        $userInocmon = $detailClientData['seguranca']['userinocmon'];
        $senhaInocmon = $detailClientData['seguranca']['senhainocmon'];

        if (!$user || $user == '') { $user = $userInocmon; }
	    if (!$pwd || $pwd == '') { $pwd = $senhaInocmon; }

        $configFileNameRr = 'CONFIG-RR'.$rrId.'-'.$hostName.'-'.$clientId;
        $uploadFilePath = 'public/configuracoes/'.$configFileNameRr;

        try {

            if(!file_exists(public_path() . '/storage/configuracoes'))
            {
                @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
            }
            Storage::disk('local')->put($uploadFilePath , $configRrFinal);
            $status = "ok";
        } catch (\Throwable $th) {
            $status = 'failed';
        }
        $this->database->getReference($debugDir)->set('arquivo '.$configFileNameRr.' gerado com sucesso! preparando transferência...');
        $sondaHostName = $detailClientData['sondas'][$sondaId]['hostname'] ?? '';
        $sondaIpv4 = $detailClientData['sondas'][$sondaId]['ipv4'] ?? '';
        $sondaPortaSsh = $detailClientData['sondas'][$sondaId]['portassh'] ?? '';
        $sondaUser = $detailClientData['sondas'][$sondaId]['user'] ?? '';
        $sondaPwd = $detailClientData['sondas'][$sondaId]['pwd'] ?? '';

	    $this->database->getReference($debugDir)->set('conectando ao proxy: '.$sondaHostName.' '.$sondaIpv4.' '.$sondaPortaSsh.' '.$sondaUser.' '.base64_encode($sondaPwd));

        $ssh = new SSH2($sondaIpv4, $sondaPortaSsh);
        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);

        try {
            if (!$ssh->login($sondaUser, $sondaPwd)) {
                $status ='failed';
                $this->database->getReference($debugDir)->set('falha de login no proxy...');
            } else {
                $ssh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');
                $scp = new SCP($ssh);
                $this->database->getReference($debugDir)->set('inicindo copia do arquivo '.$configFileNameRr);
                $scp->put($configFileNameRr, public_path() . '/storage/configuracoes/'.$configFileNameRr, 1);
                sleep(2);
                $lineCount = substr_count($configRrFinal, "\n");

                $this->database->getReference($debugDir)->set('iniciando config em RR'.$rrId.' '.$hostName.'... tempo estimado: '.$lineCount.'s');
                $comandoRemoto = $ssh->exec('inoc-config '.$routerId.' '.$user.' \''.$pwd.'\' '.$porta.' '.$configFileNameRr.' '.$debugFile.' & ');
                if (str_contains($comandoRemoto, 'Err')) {
                    $this->database->getReference($debugDir)->set('Erro de login em: '.$hostName.': '.$comandoRemoto);
                } else {
                    for ($i = 0; $i < $lineCount; $i++){
                        $tempoEstimado = ($lineCount - $i);
                        $progresso = ($i / $lineCount  * 100);
                        $progresso = round($progresso, 0);
                        $this->database->getReference($debugDir)->set($progresso.'% aplicando config em '.$hostName.' tempo estimado: '.$tempoEstimado.'s ');
                        sleep(1);
                    }
                }
                $status = 'ok';
            }
        } catch (\Throwable $th) {
            $status = 'failed';
        }

		$this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...');
        $lineCount = 15;
        for ($i = 0; $i < $lineCount; $i++)
        {
            $tempoEstimado = ($lineCount - $i);
            $progresso = ($i / $lineCount  * 100);
            $progresso = round($progresso, 0);
            $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...'.$progresso.'%');
        }
    	$ssh = new SSH2($sondaIpv4, $sondaPortaSsh);
        $relatorio = 'New report';
        try {
            if (!$ssh->login($sondaUser, $sondaPwd))
            {
                $status = "failed";
                $this->database->getReference($debugDir)->set('falha de login no proxy...');
            } else {
                $relatorio = $ssh->exec('awk \'/config begin -> '.$configToken.'/ { f = 1 } f\' '.$debugFile.'.log');
                sleep(3);
                $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...100%');

                /*gravar relatorio */
                $now = date("h-i-sa").'-'.date("Y-M-d");
                $this->database->getReference('clientes/'.$clientId.'/rr/'.$rrId.'/relatorios/'.$now)->set($relatorio);

                $this->database->getReference($debugDir)->set('Configuração concluída!');
                $this->database->getReference($debugDir)->set('idle');
                $status = 'ok';
            }
        } catch (\Throwable $th) {
            $status = 'failed';
        }
        return response()->json([
            'status' => $status,
            'relatorio' => nl2br($relatorio)
        ]);
    }

    public function index(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $rrId = $req->query()['rr_id'];
        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();

        $debug = $detailClientData['rr'][$rrId]['debug'] ?? '';
        $hostName = $detailClientData['rr'][$rrId]['hostname'];
        $routerId = $detailClientData['rr'][$rrId]['routerid'];

        $templateVendor = $detailClientData['rr'][$rrId]['template-vendor'] ?? '';
        $templateFamily = $detailClientData['rr'][$rrId]['template-family'] ?? '';

        $snmpCommunity = $detailClientData['seguranca']['snmpcommunity'];
        $ipv4BgpMultiHop = $detailClientData['bgp']['ipv4bgpmultihop'];
        $ipv6BgpMultiHop = $detailClientData['bgp']['ipv6bgpmultihop'];
        $community0 = $detailClientData['bgp']['community0'];
        $asn = $detailClientData['bgp']['asn'];

        $getTemplateRr =  $this->database->getReference('lib/templates/rr/'.$templateVendor.'/'.$templateFamily.'/rr-base-config')->getSnapshot()->getValue() ?? '';

        $configBaseFinal = str_replace("%hostname%",$hostName,$getTemplateRr);
        $configBaseFinal = str_replace("%snmpcommunity%",$snmpCommunity, $configBaseFinal);
        $configBaseFinal = str_replace("%routerid%",$routerId, $configBaseFinal);
        $configBaseFinal = str_replace("%ipv4bgpmultihop%",$ipv4BgpMultiHop, $configBaseFinal);
        $configBaseFinal = str_replace("%ipv6bgpmultihop%",$ipv6BgpMultiHop, $configBaseFinal);
        $configBaseFinal = str_replace("%community0%",$community0, $configBaseFinal);
        $configBaseFinal = str_replace("%asn%",$asn, $configBaseFinal);
	    $configBaseFinal = nl2br($configBaseFinal);

    	$this->database->getReference('clientes/'.$clientId.'/rr/'.$rrId.'/configs/rrbaseconfig')->set($configBaseFinal);

        $configBaseRr = $detailClientData['rr'][$rrId]['configs']['rrbaseconfig'] ?? '';

        $buscaSondas = $detailClientData['sondas'] ?? '';
        $buscaEquipamentos = $detailClientData['equipamentos']  ?? '';
        $buscaRelatorios = $detailClientData['rr'][$rrId]['relatorios']  ?? '';
        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);

        if(!is_array($buscaRelatorios)) {
            $buscaRelatorios = [];
        }

        $toSendData = [
            'rrId' => $rrId,
            'consoleData' => $debug,
            'hostName' => $hostName,
            'routerId' => $routerId,
            'templateVendor' => $templateVendor,
            'templateFamily' => $templateFamily,
            'configBaseRr' => $configBaseRr,
            'buscaSondas' => $buscaSondas,
            'buscaEquipamentos' => $buscaEquipamentos,
            'buscaRelatorios' => $buscaRelatorios,
            'configToken' => $configToken
        ];

        return view('admin.assetmanagement.pr_template', compact('clientId', 'toSendData'));
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
