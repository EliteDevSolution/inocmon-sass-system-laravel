<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;


class LocalhostController extends Controller
{


    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }

    public function applyBaseConfig(Request $req)
    {
        if(request()->ajax()) {
            $status = '';
            $clientId = $req['clientId'];
            $proxyId = $req['proxyId'];

            $detailClientData = $this->database->getReference('clientes/' . $req['clientId'])->getSnapshot()->getValue();

            $hostName = $detailClientData['sondas'][$req['proxyId']]['hostname'];
            $proxyPortaSsh = $detailClientData['sondas'][$req['proxyId']]['portassh'];
            $proxyUser = $detailClientData['sondas'][$req['proxyId']]['user'];
            $proxyPwd = $detailClientData['sondas'][$req['proxyId']]['pwd'] ?? '';
            $userInocmon = $detailClientData['seguranca']['userinocmon'] ?? '';
            $senhaInocmon = $detailClientData['seguranca']['senhainocmon'] ?? '';

            $snmpCommunity = $detailClientData['seguranca']['snmpcommunity'];
            $proxyIp4 = $detailClientData['sondas'][$req['proxyId']]['ipv4'] ;
            $platform = $detailClientData['sondas'][$req['proxyId']]['plataforma'] ?? '';
            $so = $detailClientData['sondas'][$req['proxyId']]['so'] ?? '';
            $community0 = $detailClientData['bgp']['community0'] ?? '';
            $asn = $detailClientData['bgp']['asn'] ?? '';
            // $buscarRelators = $detailClientData['sondas'][$req['proxyId']]['relatorios'] ?? '';
            $buscaSondas = $detailClientData['sondas'];
            $buscaEquipmet = $detailClientData['equipamentos'];

            $debugDir = 'clientes/'.$clientId.'/sondas/'.$req['proxyId'].'/debug';
            $debugDir = $this->database->getReference($debugDir)->set('idle');

            $getTemplateBase = $this->database->getReference('lib/templates/proxy/proxy-base-config')->getSnapshot()->getValue();
            $getTemplateUpdate = $this->database->getReference('lib/templates/proxy/proxy-update-config')->getSnapshot()->getValue();

            $configBaseFinal = str_replace("%hostname%",$hostName,$getTemplateBase);
            $configBaseFinal = str_replace("%snmpcommunity%",$snmpCommunity, $configBaseFinal);
            $configBaseFinal = str_replace("%community0%",$community0, $configBaseFinal);
            $configBaseFinal = str_replace("%asn%",$asn, $configBaseFinal);
            $configBaseSalva = $detailClientData['sondas'][$req['proxyId']]['configs']['baseconfig'];

            $debugDir = 'clientes/'.$req['clientId'].'/sondas/'.$proxyId.'/debug';
            $debugFile = 'DEBUG-'.$req['clientId'];

            $this->database->getReference($debugDir)->set('preparando config base para proxy'.$proxyId.': '.$hostName);

            $configBaseFinal = str_replace("<br />","",$configBaseSalva);

            $configFileName = 'CONFIG-PROXY'.$proxyId.'-'.$hostName.'-'.$req['clientId'].'.sh';
            $uploadFilePath = 'public/configuracoes/'.$configFileName;

            try {
                if(!file_exists(public_path() . '/storage/configuracoes'))
                {
                    @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
                }
                Storage::disk('local')->put($uploadFilePath , $configBaseFinal);
                $status = "ok";
            } catch (\Throwable $th) {
                $status = 'failed';
            }

            $this->database->getReference($debugDir)->set('arquivo '.$configFileName.' gerado com sucesso! preparando transferência...');


	        $ssh = new SSH2($proxyIp4, $proxyPortaSsh);

            if (!$proxyUser) { $proxyUser = $userInocmon; }
	        if (!$proxyPwd) { $proxyPwd = $senhaInocmon; }

            $configToken = bin2hex(random_bytes(64));
            $configToken = substr($configToken,0, -85);
            $_SESSION['token_config_rr'] = $configToken;

            try {
                if (!$ssh->login($proxyUser, $proxyPwd)) {
                    $this->database->getReference($debugDir)->set('falha de login no proxy...');
                    $status = 'failed';
                } else {
                    $ssh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');

                    $scp = new SCP($ssh);
                    $this->database->getReference($debugDir)->set('inicindo copia do arquivo '.$configFileName);
                    $scp->put($configFileName, public_path() . '/storage/configuracoes/'.$configFileName, NET_SCP_LOCAL_FILE);


                    $lineCount = substr_count($configBaseFinal, "\n");
                    $this->database->getReference($debugDir)->set('iniciando config em PROXY '.$proxyId.' '.$hostName.'... tempo estimado: '.$lineCount.'s');
                    $comandoRemoto = $ssh->exec('sh '.$configFileName.' >> '.$debugFile.' & ');

                    for ($i = 0; $i < $lineCount; $i++){
                        $tempoEstimado = ($lineCount - $i);
                        $progresso = ($i / $lineCount  * 100);
                        $progresso = round($progresso, 0);
                        $this->database->getReference($debugDir)->set($progresso.'% aplicando config em '.$hostName.' tempo estimado: '.$tempoEstimado.'s ');
                    }
                    $status = 'ok';
                }
            } catch (\Throwable $th) {
                $status = 'failed';
            }

            $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...');

            $lineCount = 10;
            for ($i = 0; $i < $lineCount; $i++)
            {
                $tempoEstimado = ($lineCount - $i);
                $progresso = ($i / $lineCount  * 100);
                $progresso = round($progresso, 0);
                $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...'.$progresso.'%');
            }

            $relatorio = $ssh->exec('awk \'/config begin -> '.$configToken.'/ { f = 1 } f\' '.$debugFile.'.log');
            $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...100%');

            /*gravar relatorio */
            $now = date("h-i-sa").'-'.date("Y-M-d");
            $this->database->getReference('clientes/'.$clientId.'/sondas/'.$proxyId.'/relatorios/'.$now)->set($relatorio);

            $this->database->getReference($debugDir)->set('Configuração concluída!');
            sleep(5);
            $consoleData = $detailClientData['sondas'][$proxyId]['debug'];

            return response()->json([
                'status' => $status,
                'console_data' => $consoleData,
                'relatorio' => nl2br($relatorio),
            ]);
        }
    }

    public function updataIncoConfig(Request $req)
    {
        $status = '';
        $proxyId = $req['proxyId'];
        $clientId = $req['clientId'];
        $debugDir = 'clientes/'.$clientId.'/sondas/'.$proxyId.'/debug';
        $debugFile = 'DEBUG-'.$clientId;
        $getTemplateUpdate =  $this->database->getReference('lib/templates/proxy/proxy-update-config')->getSnapshot()->getValue();
        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);
        $_SESSION['token_config_rr'] = $configToken;
	    $proxyData = $this->database->getReference('clientes/' . $clientId . '/sondas/'.$proxyId)->getSnapshot()->getValue();
        $proxyHostName = $proxyData['hostname'];
        $proxyIpv4 = $proxyData['ipv4'];
        $proxyPortaSsh = $proxyData['portassh'];
        $proxyUser = $proxyData['user'];
        $proxyPwd = $proxyData['pwd'] ?? '';
	    $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $userInocmon = $detailClientData['seguranca']['userinocmon'];
        $senhaInocmon = $detailClientData['seguranca']['senhainocmon'];
        $configBaseSalva = $detailClientData['sondas'][$proxyId]['configs']['baseconfig'];
        $this->database->getReference($debugDir)->set('preparando atualização para proxy'.$proxyId.': '.$proxyHostName);
        if (!$proxyUser) { $user = $userInocmon; }
	    if (!$proxyPwd) { $pwd = $senhaInocmon; }
        $configUpdateFinal = str_replace("<br />","",$getTemplateUpdate);
        $configUpdateFinal = str_replace("%pwd%",$proxyPwd, $configUpdateFinal);
        $configUpdateFinal = str_replace("%user%",$proxyUser, $configUpdateFinal);
        $configBaseFinal = str_replace("<br />","",$configBaseSalva);

        $configFileName = 'CONFIG-PROXY'.$proxyId.'-'.$proxyHostName.'-'.$clientId.'.sh';
	    $uploadFilePath = 'public/configuracoes/'.$configFileName;

        try {
            if(!file_exists(public_path() . '/storage/configuracoes'))
            {
                @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
            }
            Storage::disk('local')->put($uploadFilePath , $configBaseFinal);
            $status = "ok";
        } catch (\Throwable $th) {
            $status = 'failed';
        }

	    $this->database->getReference($debugDir)->set('arquivo '.$configFileName.' gerado com sucesso! preparando transferência...');
	    $this->database->getReference($debugDir)->set('conectando ao proxy: '.$proxyHostName.' '.$proxyIpv4.' '.$proxyPortaSsh.' '.$proxyUser.' '.base64_encode($proxyPwd));
	    $ssh = new SSH2($proxyIpv4, $proxyPortaSsh);

        try {
            if (!$ssh->login($proxyUser, $proxyPwd)) {
                $this->database->getReference($debugDir)->set('falha de login no proxy...');
                $status = 'failed';
            } else {
                $output = shell_exec('echo \'config begin -> '.$configToken.'\' >> configuracoes/'.$debugFile.'.log');
                #echo "<pre>$output</pre>";
                $scp = new SCP($ssh);
                $this->database->getReference($debugDir)->set('iniciando copia dos arquivos...');
                $scp->put('inoc-config', '/usr/bin/inoc-config', 1);
                $scp->put('inoc-command', '/usr/bin/inoc-command', 1);

                $lineCount = substr_count($configUpdateFinal, "\n");
                $this->database->getReference($debugDir)->set('iniciando config em PROXY '.$proxyId.' '.$proxyHostName.'... tempo estimado: '.$lineCount.'s');
                $output = shell_exec('inoc-config '.$proxyIpv4.' '.$proxyUser.' \''.$proxyPwd.'\' '.$proxyPortaSsh.' configuracoes/'.$configFileName.' configuracoes/'.$debugFile.' &');
                sleep(5);

                for ($i = 0; $i < $lineCount; $i++){
                    $tempoEstimado = ($lineCount - $i);
                    $progresso = ($i / $lineCount  * 100);
                    $progresso = round($progresso, 0);
                    $this->database->getReference($debugDir)->set($progresso.'% aplicando config em '.$proxyHostName.' tempo estimado: '.$tempoEstimado.'s ');
                    sleep(1);
                }
                $status = 'ok';
            }
        } catch (\Throwable $th) {
            $status = 'failed';
        }

        $this->database->getReference($debugDir)->set('atualização finalizada! Gerando relatório...');

        $lineCount = 10;
        for ($i = 0; $i < $lineCount; $i++)
        {
            $tempoEstimado = ($lineCount - $i);
            $progresso = ($i / $lineCount  * 100);
            $progresso = round($progresso, 0);
            $this->database->getReference($debugDir)->set('atualização finalizada! Gerando relatório...'.$progresso.'%');
        }

        $relatorio = shell_exec('awk \'/config begin -> '.$configToken.'/ { f 1= 1 } f\' configuracoes/'.$debugFile.'.log');
        #echo "<pre>$relatorio</pre>";
        $this->database->getReference($debugDir)->set('atualização finalizada! Gerando relatório...100%');
        sleep(1);

        /*gravar relatorio */
        $now = date("h-i-sa").'-'.date("Y-M-d");
        $this->database->getReference('clientes/'.$clientId.'/sondas/'.$proxyId.'/relatorios/'.$now)->set($relatorio);

        $this->database->getReference($debugDir)->set('atualização concluída!');

        $consoleData = $detailClientData['sondas'][$proxyId]['debug'];
        return response()->json([
            'status' => $status,
            'console_data' => $consoleData,
            'relatorio' => nl2br($relatorio)
        ]);
    }

    /*--------console log automatically load. right?-------0*/


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {

        $clientId = $req->query()['client_id'];
        $proxyId = $req->query()['proxy_id'];
        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();

        $hostName = $detailClientData['sondas'][$proxyId]['hostname'];
        $snmpCommunity = $detailClientData['seguranca']['snmpcommunity'];
        $proxyIp4 = $detailClientData['sondas'][$proxyId]['ipv4'];
        $platform = $detailClientData['sondas'][$proxyId]['plataforma'] ?? '';
        $so = $detailClientData['sondas'][$proxyId]['so'] ?? '';
        $community0 = $detailClientData['bgp']['community0'];
        $asn = $detailClientData['bgp']['asn'];
        $buscarRelators = $detailClientData['sondas'][$proxyId]['relatorios'] ?? [];
        $buscaSondas = $detailClientData['sondas'];
        $buscaEquipmet = $detailClientData['equipamentos'];

		$debugDir = 'clientes/'.$clientId.'/sondas/'.$proxyId.'/debug';
        $debugDir = $this->database->getReference($debugDir)->set('idle');

        $getTemplateBase = $this->database->getReference('lib/templates/proxy/proxy-base-config')->getSnapshot()->getValue();
		$getTemplateUpdate = $this->database->getReference('lib/templates/proxy/proxy-update-config')->getSnapshot()->getValue();

        $configBaseFinal = str_replace("%hostname%",$hostName,$getTemplateBase);
        $configBaseFinal = str_replace("%snmpcommunity%",$snmpCommunity, $configBaseFinal);
        $configBaseFinal = str_replace("%community0%",$community0, $configBaseFinal);
        $configBaseFinal = str_replace("%asn%",$asn, $configBaseFinal);
        $configBaseSalva = $detailClientData['sondas'][$proxyId]['configs']['baseconfig'] ?? '';

        $configBaseFinal = nl2br($configBaseFinal);
        $this->database->getReference('clientes/'.$clientId.'/sondas/'.$proxyId.'/configs/baseconfig')->set($configBaseFinal);

        $consoleData = $detailClientData['sondas'][$proxyId]['debug'] ?? '';
        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);

        $toSendData = [
            'console_data' => $consoleData,
            'proxy_id' => $proxyId,
            'hostname' => $hostName,
            'proxyip4' => $proxyIp4,
            'platform' => $platform,
            'system' => '',
            'configBase' => $configBaseSalva,
            'buscarRelators' => $buscarRelators,
            'configToken' => $configToken
        ];

        return view('admin.assetmanagement.localhost', compact('clientId', 'toSendData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
