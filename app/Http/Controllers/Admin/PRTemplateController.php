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

        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $hostName = $detailClientData['rr'][$rrId]['hostname'];
        $porta = $detailClientData['rr'][$rrId]['porta'];
        $user = $detailClientData['rr'][$rrId]['porta'];
        $pwd = $detailClientData['rr'][$rrId]['pwd'];
        $userInocmon = $detailClientData['seguranca']['userinocmon'];
        $senhaInocmon = $detailClientData['seguranca']['senhainocmon'];

        $debugDir = 'clientes/'.$clientId.'/rr/'.$rrId.'/debug';
        $debugFile = 'DEBUG-'.$clientId;
        $this->database->getReference($debugDir)->set('preparando config base para RR'.$rrId.': '.$hostName);

        if (!$user) {
             $user = $userInocmon;
        }
        if (!$pwd) {
            $pwd = $senhaInocmon;
        }
        $configRrFinal = $detailClientData['rr'][$rrId]['configs']['rrbaseconfig'];
    	$configRrFinal = str_replace("<br />","",$configRrFinal);
    	$configFileNameRr = 'CONFIG-RR'.$rrId.'-'.$hostName.'-'.$clientId;
        $uploadFilePath = 'configuracoes/'.$configFileNameRr;
        Storage::put($uploadFilePath, $configRrFinal);
	    $this->database->getReference($debugDir)->set('arquivo '.$configFileNameRr.' gerado com sucesso! preparando transferência...');

        $sondaHostName = $detailClientData['sondas'][$sondaId]['hostname'];
        $sondaIpv4 = $detailClientData['sondas'][$sondaId]['ipv4'];
        $sondaPortaSsh = $detailClientData['sondas'][$sondaId]['portassh'];
        $sondaUser = $detailClientData['sondas'][$sondaId]['user'];
        $sondaPwd = $detailClientData['sondas'][$sondaId]['pwd'];
        $routerId = $detailClientData['rr'][$rrId]['routerid'];

        $this ->database->getReference($debugDir)->set('conectando ao proxy: '.$sondaHostName.' '.$sondaIpv4.' '.$sondaPortaSsh.' '.$sondaUser.' '.base64_encode($sondaPwd));
	    $ssh = new SSH2($sondaIpv4, $sondaPortaSsh);

        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);
        $ssh->login($sondaUser, $sondaPwd);
        if (false) {
			$this->database->getReference($debugDir)->set('falha de login no proxy...');
	    } else {
            $ssh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');
            $scp = new SCP($ssh);
            $this->database->getReference($debugDir)->set('inicindo copia do arquivo '.$configFileNameRr);
            $scp->put($configFileNameRr, 'configuracoes/'.$configFileNameRr, 1);

            $lineCount = substr_count($configRrFinal, "\n");
            $this->database->getReference($debugDir)->set('iniciando config em RR'.$rrId.' '.$hostName.'... tempo estimado: '.$lineCount.'s');
            $comandoRemoto = $ssh->exec('inoc-config '.$routerId.' '.$user.' \''.$pwd.'\' '.$porta.' '.$configFileNameRr.' '.$debugFile.' & ');

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
        if (!$ssh->login($sondaUser, $sondaPwd))
		{
			$this->database->getReference($debugDir)->set('falha de login no proxy...');
		} else {
            $relatorio = $ssh->exec('awk \'/config begin -> '.$configToken.'/ { f = 1 } f\' '.$debugFile.'.log');
            $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...100%');
            $now = date("h-i-sa").'-'.date("Y-M-d");
            $this->database->getReference('clientes/'.$clientId.'/rr/'.$rrId.'/relatorios/'.$now)->set($relatorio);
            $this->database->getReference($debugDir)->set('Configuração concluída!');
            $this->database->getReference($debugDir)->set('idle');
        }
        return response()->json([
            'status' => 'ok'
        ]);
    }

    public function applyConfigPes(Request $req) {

    }

    public function index(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $rrId = $req->query()['rr_id'];
        $layout = true;
        $clients = $this->database->getReference('clientes')->getValue();
        $detailClientData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();

        $debug = $detailClientData['rr'][$rrId]['debug'];
        $hostName = $detailClientData['rr'][$rrId]['hostname'];
        $routerId = $detailClientData['rr'][$rrId]['routerid'];
        $templateVendor = $detailClientData['rr'][$rrId]['template-vendor'];
        $templateFamily = $detailClientData['rr'][$rrId]['template-family'];
        $configBaseRr = $detailClientData['rr'][$rrId]['configs']['rrbaseconfig'];
        $buscaSondas = $detailClientData['sondas'];
        $buscaEquipamentos = $detailClientData['equipamentos'];

        $toSendData = [
            'rrId' => $rrId,
            'consoleData' => $debug,
            'hostName' => $hostName,
            'routerId' => $routerId,
            'templateVendor' => $templateVendor,
            'templateFamily' => $templateFamily,
            'configBaseRr' => $configBaseRr,
            'buscaSondas' => $buscaSondas,
            'buscaEquipamentos' => $buscaEquipamentos
        ];

        return view('admin.assetmanagement.pr_template', compact('layout','clientId', 'clients', 'toSendData'));
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
