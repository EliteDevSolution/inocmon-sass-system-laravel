<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class MplsDetailController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }

    public function applyConfig(Request $req)
    {
        $status = '';
        $clientId = $req['clientId'];
        $equipId = $req['equipId'];
        $buscaConfigIds = $req['buscaConfigIds'] ?? [];
        $buscaRrIds = $req['buscaRrIds'] ?? [];
        $sondaId = $req['sondaId'];
        $inputConfigToken = $req['inputConfigToken'];

        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();

        $debugDir = 'clientes/'.$clientId.'/equipamentos/'.$equipId.'/debug';
        $debugFile = 'DEBUG-'.$clientId;
        if(array_key_exists('sondas', $detailClientData)) {
            $sondaIpv4 = $detailClientData['sondas'][$sondaId]['ipv4'];
            $sondaPortaSsh = $detailClientData['sondas'][$sondaId]['portassh'];
            $sondaUser = $detailClientData['sondas'][$sondaId]['user'];
            $sondaPwd = $detailClientData['sondas'][$sondaId]['pwd'];
        } else {
            $sondaIpv4 = '';
            $sondaPortaSsh = '';
            $sondaUser = '';
            $sondaPwd = '';
        }


        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);

        $this->database->getReference($debugDir)->set('preparando config...');

        $realIndexConfig = "";
        $configFinal = "";
        if(is_array($buscaConfigIds)) {
            for ($i=0; $i < count($buscaConfigIds); $i++) {
                $realIndexConfig = $buscaConfigIds[$i];
                $getParcialConfig = $detailClientData['equipamentos'][$equipId]['configs'][$realIndexConfig];
                $configFinal .= str_replace("<br />","",$getParcialConfig);
            }
        }
        $this->database->getReference($debugDir)->set('preparando:'.$realIndexConfig.'...');

        $hostName = $detailClientData['equipamentos'][$equipId]['hostname'] ?? '';

        if(is_array($buscaConfigIds) || is_array($buscaRrIds)) {
            if( count($buscaConfigIds) > 0 || count($buscaRrIds) > 0 ){
                $configFileNamePe = 'CONFIG-PE-'.$clientId.'-'.$equipId;
                $uploadFilePe = 'public/configuracoes/'.$configFileNamePe;

                try {
                    if(!file_exists(public_path() . '/storage/configuracoes'))
                    {
                        @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
                    }
                    Storage::disk('local')->put($uploadFilePe , $configFinal);
                    $status = "ok";
                } catch (\Throwable $th) {
                    $status = 'failed';
                }
                $this->database->getReference($debugDir)->set('arquivo '.$configFileNamePe.' gerado com sucesso! Iniciando transferência...');

                $ssh = new SSH2($sondaIpv4, $sondaPortaSsh);

                try {
                    if (!$ssh->login($sondaUser, $sondaPwd)) {
                        $status = 'failed';
                        $this->database->getReference($debugDir)->set('falha de login na sonda...');
                        die('falha de login na sonda...');
                    } else {
                        $status = 'ok';
                    }
                } catch (\Throwable $th) {
                    $status = 'failed';
                }

                $scp = new SCP($ssh);

                $scp->put($configFileNamePe, public_path() . '/storage/configuracoes/'.$configFileNamePe, 1);
                $hostName = $detailClientData['equipamentos'][$equipId]['hostname'] ?? '';
                $routerId = $detailClientData['equipamentos'][$equipId]['routerid'] ?? '';
                $porta = $detailClientData['equipamentos'][$equipId]['porta'] ?? '';
                $user = $detailClientData['equipamentos'][$equipId]['user'] ?? '';
                $pwd = $detailClientData['equipamentos'][$equipId]['pwd'] ?? '';
                $userInocmon = $detailClientData['equipamentos'][$equipId]['userinocmon'] ?? '';
                $senhaInocmon = $detailClientData['equipamentos'][$equipId]['senhainocmon'] ?? '';

                $this->database->getReference($debugDir)->set('usuario carregado: '.$user.' senha: '.base64_encode($pwd));
                if (!$user) { $user = $userInocmon; }
                if (!$pwd) { $pwd = $senhaInocmon; }

                $ssh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');

                $this->database->getReference($debugDir)->set('assinando token de configuração: '.$configToken);
                $this->database->getReference($debugDir)->set('aplicando configurações em '.$hostName.'...');
                $lineCount = substr_count($configFinal, "\n");
                $this->database->getReference($debugDir)->set('aplicando config em '.$hostName.' tempo estimado: '.$lineCount.'s');
                $comandoRemoto = $ssh->exec('inoc-config '.$routerId.' '.$user.' \''.$pwd.'\' '.$porta.' '.$configFileNamePe.' '.$debugFile.' & ');

                if (str_contains($comandoRemoto, 'Err')) {
                    $this->database->getReference($debugDir)->set('Erro de login em: '.$hostName.': '.$comandoRemoto);
                }else{
                    for ($i = 0; $i < $lineCount; $i++){
                        $tempoEstimado = ($lineCount - $i);
                        $progresso = ($i / $lineCount  * 100);
                        $progresso = round($progresso, 0);
                        $this->database->getReference($debugDir)->set($progresso.'% aplicando config em '.$hostName.' tempo estimado: '.$tempoEstimado.'s ');
                    }
                }
                $this->database->getReference($debugDir)->set('config do PE '.$hostName.' FINALIZADA!');
            } else{
                $this->database->getReference($debugDir)->set('Nenhuma config selecionada para '.$hostName.'!');
            }
        }

        if(is_array($buscaRrIds)) {
            for ($j = 0; $j < count($buscaRrIds); $j++) {
                $getRrConfig = $detailClientData['equipamentos'][$equipId]['configs']['rr'.$buscaRrIds[$j]];
                $configRrFinal = str_replace("<br />","",$getRrConfig);
                $this->database->getReference($debugDir)->set('Iniciando config em : RR'.$buscaRrIds[$j].'...');
                $rrHostName = $detailClientData['rr'][$buscaRrIds[$j]]['hostname'];
                $rrRouterId = $detailClientData['rr'][$buscaRrIds[$j]]['routerid'];
                $rrPorta = $detailClientData['rr'][$buscaRrIds[$j]]['porta'];
                $rrUser = $detailClientData['rr'][$buscaRrIds[$j]]['user'];
                $rrPwd = $detailClientData['rr'][$buscaRrIds[$j]]['pwd'];

                $this->database->getReference($debugDir)->set('Iniciando config em : RR'.$buscaRrIds[$j].'-'.$rrHostName.'-'.$rrRouterId.'...');

                if (!$rrUser) { $user = $userInocmon; }
                if (!$rrPwd) { $pwd = $senhaInocmon;}

                $configFileNameRr = 'CONFIG-RR'.$buscaRrIds[$j].'-'.$clientId.'-'.$equipId;
                $uploadFileRr = 'public/configuracoes/'.$configFileNameRr;

                try {
                    if(!file_exists(public_path() . '/storage/configuracoes'))
                    {
                        @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
                    }
                    Storage::disk('local')->put($uploadFileRr , $configRrFinal);
                    $status = "ok";
                } catch (\Throwable $th) {
                    $status = 'failed';
                }

                $this->database->getReference($debugDir)->set('arquivo '.$configFileNameRr.' gerado com sucesso! Iniciando transferência...');
                $this->database->getReference($debugDir)->set('conectando ao proxy: '.$sondaIpv4.' '.$sondaPortaSsh.' '.$sondaUser.' '.base64_encode($sondaPwd));

                $rrSsh = new SSH2($sondaIpv4, $sondaPortaSsh);

                try {
                    if (!$rrSsh->login($sondaUser, $sondaPwd)) {
                        $status = 'failed';
                        $this->database->getReference($debugDir)->set('falha de login no proxy...');
                    } else {
                        $status = 'ok';
                    }
                } catch (\Throwable $th) {
                    $status = 'failed';
                }
                if(is_array($buscaConfigIds)) {
                    if (count($buscaConfigIds) == 0) {
                        $rrSsh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');
                    }
                }


                $rrScp = new SCP($rrSsh);

                $this->database->getReference($debugDir)->set('inicindo copia do arquivo '.$configFileNameRr);
                $rrScp->put($configFileNameRr, public_path() . '/storage/configuracoes/'.$configFileNameRr, 1);


                $rrLineCount = substr_count($configRrFinal, "\n");
                $this->database->getReference($debugDir)->set('aplicando config em RR'.$buscaRrIds[$j].' '.$rrHostName.'... tempo estimado: '.$rrLineCount.'s');

                $rrSsh->exec('inoc-config '.$rrRouterId.' '.$rrUser.' \''.$rrPwd.'\' '.$rrPorta.' '.$configFileNameRr.' '.$debugFile.' &');

                for ($i = 0; $i < $rrLineCount; $i++){
                    $tempoEstimado = ($rrLineCount - $i);
                    $progresso = ($i / $rrLineCount  * 100);
                    $progresso = round($progresso, 0);
                    $this->database->getReference($debugDir)->set($progresso.'%... aplicando config em '.$hostName.' tempo estimado: '.$tempoEstimado.'s');
                }
            }
        }

        $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...');

        $rrLineCount = 15;
        for ($i = 0; $i < $rrLineCount; $i++){
            $tempoEstimado = ($rrLineCount - $i);
            $progresso = ($i / $rrLineCount  * 100);
            $progresso = round($progresso, 0);
            $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...'.$progresso.'%');
        }

        $rrSsh = new SSH2($sondaIpv4, $sondaPortaSsh);

        try {
           if (!$rrSsh->login($sondaUser, $sondaPwd)) {
                $this->database->getReference($debugDir)->set('falha de login no proxy...');
                $status ="failed";
            } else {
                $status = 'ok';
            }
        } catch (\Throwable $th) {
            $status = 'failed';
        }

        $relatorio = $rrSsh->exec('awk \'/config begin -> '.$configToken.'/ { f = 1 } f\' '.$debugFile.'.log');
        $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...100%');
        $this->database->getReference($debugDir)->set('Configuração concluída!');
        $this->database->getReference($debugDir)->set('idle');

        $lunchData = $this->database->getReference($debugDir)->getSnapshot()->getValue();
        return response()->json(
            [
                'message' => 'Custom function called successfully',
                'status' => $status,
                'debugData' => $lunchData,
                'relatorio' => nl2br($relatorio)
            ]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $equipId = $req->query()['equip_id'];

        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $debug = $detailClientData['equipamentos'][$equipId]['debug'] ?? '';
        $hostName = $detailClientData['equipamentos'][$equipId]['hostname'] ?? '';
        $routerId = $detailClientData['equipamentos'][$equipId]['routerid'] ?? '';
        $buscaSondas = $detailClientData['sondas'] ?? [];
        $senhaInocmonCifrada = $detailClientData['seguranca']['senhainocmoncifrada'] ?? '';
        $snmpCommunity = $detailClientData['seguranca']['snmpcommunity'] ?? '';
        $community0 = $detailClientData['bgp']['community0'] ?? '';
        $asn = $detailClientData['bgp']['asn'];
        $buscaRr = $detailClientData['rr'] ?? [];
        $templateVendor = $detailClientData['equipamentos'][$equipId]['template-vendor'] ??  '';
        $templateFamily = $detailClientData['equipamentos'][$equipId]['template-family'] ?? '';
        $grupoIbgp = $detailClientData['equipamentos'][$equipId]['grupo-ibgp'] ?? '';

        $dir = 'lib/templates/pe/'.$templateVendor.'/'.$templateFamily;
        $buscaConfigs = $this->database->getReference($dir)->getSnapshot()->GetValue();
        $configGlobal = "";
        $config = "";
        $configs = [];
        $configBgpRrX = "";
        $configBgpRrFinal = "";
        if(is_array($buscaConfigs)) {
            foreach($buscaConfigs as $indexConfig => &$configVal) {
                $getTemplate = $buscaConfigs[$indexConfig];
                $config = str_replace("%hostname%",$hostName,$getTemplate);
                $config = str_replace("%senhainocmoncifrada%",$senhaInocmonCifrada, $config);
                $config = str_replace("%snmpcommunity%",$snmpCommunity, $config);
                $config = str_replace("%community0%",$community0, $config);
                $config = str_replace("%routerid%",$routerId, $config);
                $config = str_replace("%asn%",$asn, $config);

                if(str_contains($indexConfig, 'rr')) {
                    foreach ($buscaRr as $buscaRrIds => $rrVal) {
                        if ($buscaRrIds != 0) {
                            $configBgpRrX = str_replace("%rrip%",$rrVal['routerid'], $getTemplate);
                            $configBgpRrX = str_replace("%asn%",$asn, $configBgpRrX);
                            $configBgpRrX = str_replace("%rrhostname%",$rrVal['hostname'], $configBgpRrX);
                            $configBgpRrFinal .= $configBgpRrX;
                        }
                    }
                    $config = $configBgpRrFinal;
                }
                $configGlobal .= $config;
                $this->database->getReference('clientes/'.$clientId.'/equipamentos/'.$equipId.'/configs/'.$indexConfig)->set($config);
                $buscaConfigs[$indexConfig] = $config;
            }
        }
        $rrTemplateData = $this->database->getReference('lib/templates/rr')->getSnapshot()->getValue();
        foreach ($buscaRr as $buscaRrIds => $rrVal) {
            if($buscaRrIds != 0) {
                $rrTemplateVendor = $detailClientData['rr'][$buscaRrIds]['template-vendor'] ?? '';
                $rrTemplateFamily = $detailClientData['rr'][$buscaRrIds]['template-family'] ?? '';
                $rrDir = 'lib/templates/rr/'.$rrTemplateVendor.'/'.$rrTemplateFamily.'/rr-novope-config';
                $getTemplate = $rrTemplateData[$rrTemplateVendor][$rrTemplateFamily]['rr-novope-config'] ?? '';
                $configRr = str_replace("%routerid%",$routerId, $getTemplate);
                $configRr = str_replace("%asn%",$asn, $configRr);
                $configRr = str_replace("%hostname%",$hostName, $configRr);
                $configRr = str_replace("%grupo-ibgp%",$grupoIbgp, $configRr);
                $buscaRr[$buscaRrIds]['toLunchConfig'] = $configRr;
                $this->database->getReference('clientes/'.$clientId.'/equipamentos/'.$equipId.'/configs/rr'.$buscaRrIds)->set($configRr);
            }
        }
        // dd($buscaRr[1]['toLunchConfig']);
        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);
        $toSendData = [
            'buscaConfigs' => $buscaConfigs,
            'buscaRr' => $buscaRr,
            'debug' => $debug,
            'routerId' => $routerId,
            'hostName' => $hostName,
            'equipId' => $equipId,
            'buscaSondas' => $buscaSondas,
            'configs' => $configs,
            'configToken' => $configToken,
            'configGlobal' => $configGlobal,
        ];
        return view('admin.assetmanagement.mpls_detail', compact('clientId', 'toSendData'));
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
