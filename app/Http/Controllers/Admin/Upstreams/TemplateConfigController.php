<?php

namespace App\Http\Controllers\Admin\Upstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class TemplateConfigController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->database = \App\Http\Controllers\Helpers\FirebaseHelper::connect();
    }

    public function applyConfig(Request $request)
    {
        $status = "";
        $clientId = $request['clientId'];
        $proxyId = $request['proxyId'];
        $tipoConexao = $request['tipoConexao'];
        $id = $request['id'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();
        $diretorioConexaoBgp = 'clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao.'/'.$id;
		$buscaDadosDaConexao = $detailClientData['bgp']['interconexoes'][$tipoConexao][$id];
        $debugDir = $diretorioConexaoBgp.'/debug';
        $debugFile = 'DEBUG-'.$clientId;
        $nomeDoGrupo = $buscaDadosDaConexao['nomedogrupo'];
		$targetPeId = $buscaDadosDaConexao['peid'];
        $templateVendor = $detailClientData['equipamentos'][$targetPeId]['template-vendor'];
        $templateFamily = $detailClientData['equipamentos'][$targetPeId]['template-family'];
        $buscaTemplate = $this->database->getReference('lib/templates/bgp/'.$tipoConexao.'/'.$templateVendor.'/'.$templateFamily)->getSnapshot();
        $targetPeRouterId = $detailClientData['equipamentos'][$targetPeId]['routerid'];
        $targetPeName = $detailClientData['equipamentos'][$targetPeId]['hostname'];
        $userInform = $detailClientData['seguranca'];
        $userInocmon = $userInform['userinocmon'];
        $senhaInocmon = $userInform['senhainocmon'];

        $this->database->getReference($debugDir)->set('preparando config base para '.$nomeDoGrupo);

        if(! $buscaTemplate->getValue()) {
            $this->database->getReference($debugDir)->set('# não foram encontradas templates para '.$templateVendor.' '.$templateFamily);
        }
        else {
            $routerId = $targetPeRouterId;
            $hostName = $targetPeName;
            $porta = $detailClientData['equipamentos'][$targetPeId]['porta'] ?? '';
            $user = $detailClientData['equipamentos'][$targetPeId]['user'] ?? '';
            $pwd = $detailClientData['equipamentos'][$targetPeId]['pwd'] ?? '';

            if (!$user) { $user = $userInocmon; }
            if (!$pwd) { $pwd = $senhaInocmon; }

            $configFinal = $buscaDadosDaConexao['config'];
            $configFinal = str_replace("<br />","",$configFinal);
            $configFileName = 'CONFIG-BGP-'.$tipoConexao.'-'.$id.'-'.$nomeDoGrupo.'-'.$clientId;
            $uploadFile = 'public/configuracoes/'.$configFileName;

            try {
                if(!file_exists(public_path() . '/storage/configuracoes'))
                {
                    @mkdir(public_path() . '/storage/configuracoes' , 0777, true);
                }
                Storage::disk('local')->put($uploadFile , $configFinal);
                $status = "ok";
            } catch (\Throwable $th) {
                $status = 'failed';
            }

            $this->database->getReference($debugDir)->set('arquivo '.$configFileName.' gerado com sucesso! preparando transferência...');
            $proxyHostName = $detailClientData['sondas'][$proxyId]['hostname'];
            $proxyIpv4 = $detailClientData['sondas'][$proxyId]['ipv4'];
            $proxyPortaSsh = $detailClientData['sondas'][$proxyId]['portassh'];
            $proxyUser = $detailClientData['sondas'][$proxyId]['user'];
            $proxyPwd = $detailClientData['sondas'][$proxyId]['pwd'];
            $configToken = bin2hex(random_bytes(64));
            $configToken = substr($configToken,0, -85);

            $this->database->getReference($debugDir)->set('conectando ao proxy: '.$proxyHostName.' '.$proxyIpv4.' '.$proxyPortaSsh.' '.$proxyUser.' '.base64_encode($proxyPwd));

            $ssh = new SSH2($proxyIpv4, $proxyPortaSsh);

            try {
                if (!$ssh->login($proxyUser, $proxyPwd)) {
                    $this->database->getReference($debugDir)->set('falha de login no proxy...');
                    $status = 'failed';
                } else {
                    $ssh->exec('echo \'config begin -> '.$configToken.'\' >> '.$debugFile.'.log');
                    $scp = new SCP($ssh);
                    $this->database->getReference($debugDir)->set('inicindo copia do arquivo '.$configFileName);
                    $scp->put($configFileName, public_path() . '/storage/configuracoes/'.$configFileName, 1);
                    $lineCount = substr_count($configFinal, "\n");
                    $this->database->getReference($debugDir)->set('iniciando config em PE '.$targetPeName.'... tempo estimado: '.$lineCount.'s');
                    $comandoRemoto = $ssh->exec('inoc-config '.$routerId.' '.$user.' \''.$pwd.'\' '.$porta.' '.$configFileName.' '.$debugFile.' & ');
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
                    $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...');
                    $lineCount = 15;
                    for ($i = 0; $i < $lineCount; $i++)
                    {
                        $tempoEstimado = ($lineCount - $i);
                        $progresso = ($i / $lineCount  * 100);
                        $progresso = round($progresso, 0);
                        $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...'.$progresso.'%');
                    }

                    $ssh = new SSH2($proxyIpv4, $proxyPortaSsh);
                    if (!$ssh->login($proxyUser, $proxyPwd)) {
                        $this->database->getReference($debugDir)->set('falha de login no proxy...');
                        $status = 'failed';
                    } else {
                        $relatorio = $ssh->exec('awk \'/config begin -> '.$configToken.'/ { f = 1 } f\' '.$debugFile.'.log');
                        $this->database->getReference($debugDir)->set('configuração finalizada! Gerando relatório...100%');
                        $now = date("Y-M-d").'-'.date("h-i-sa");
                        $this->database->getReference($diretorioConexaoBgp.'/relatorios/'.$now)->set($relatorio);
                        $this->database->getReference($debugDir)->set('Configuração concluída!');
                        $this->database->getReference($debugDir)->set('idle');
                    }
                    $status = "ok";
                }
           } catch (\Throwable $th) {
                $status = $th->getMessage();
           }
        }

        $updatedDebug = $this->database->getReference($debugDir)->getSnapshot()->getValue();

        return response()->json([
            'status' => $status,
            'updatedDebug' => $updatedDebug
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $clientId = $req->query()['client_id'];
        $communityGroup = $req['groupKey'];
        $tipoConexao = $req['key'];
        $id = $req['indexId'];
        $detailClientData = $this->database->getReference('clientes/' .$clientId)->getSnapshot()->getValue();

        if(!$clientId){
			die('essa página nao pode ser carregada por falta de parâmetros essenciais');
		}
		if(!$id){
			die('essa página nao pode ser carregada por falta de parâmetros essenciais');
		}

        $configToken = bin2hex(random_bytes(64));
        $configToken = substr($configToken,0, -85);
		$_SESSION['token_config_rr'] = $configToken;
        $diretorioConexaoBgp = 'clientes/'.$clientId.'/bgp/interconexoes/'.$tipoConexao.'/'.$id;
		$buscaDadosDaConexao = $detailClientData['bgp']['interconexoes'][$tipoConexao][$id];
        $buscaProxy = $detailClientData['sondas'] ?? [];
		$buscaEquipamentos = $detailClientData['equipamentos'];
        $community0 = $detailClientData['bgp']['community0'];
        $asn = $detailClientData['bgp']['asn'];

        if(isset($buscaDadosDaConexao['relatorios'])) {
            $buscaRelatorios = $buscaDadosDaConexao['relatorios'];
        } else {
            $buscaRelatorios = "not data";
        }

        $targetPeId = $buscaDadosDaConexao['peid'];
		$debugDir = $diretorioConexaoBgp.'/debug';
		$this->database->getReference($debugDir)->set('idle');

        if($targetPeId){
            $templateVendor = $detailClientData['equipamentos'][$targetPeId]['template-vendor'] ?? '';
            $templateFamily = $detailClientData['equipamentos'][$targetPeId]['template-family'] ?? '';
            $targetPeName = $detailClientData['equipamentos'][$targetPeId]['hostname'] ?? '';
            $targetPeRouterId = $detailClientData['equipamentos'][$targetPeId]['routerid'] ?? '';
            $buscaTemplate = $this->database->getReference('lib/templates/bgp/'.$tipoConexao.'/'.$templateVendor.'/'.$templateFamily)->getSnapshot();
		}else{
            $buscaTemplate = '';
		}
        $localpref = 110;
        $medIn = 0;
        $denyCustomerIn = 'yes';
        if($tipoConexao == 'ix'){
            $nomeDoGrupo = $buscaDadosDaConexao['nomedogrupo'];
            $remoteAs = $buscaDadosDaConexao['remoteas'];
            $ipv401 = $buscaDadosDaConexao['rs1v4'];
            $ipv402 = $buscaDadosDaConexao['rs2v4'];
            $ipv601 = $buscaDadosDaConexao['rs1v6'];
            $ipv602 = $buscaDadosDaConexao['rs2v6'];
            $lgv4 = $buscaDadosDaConexao['lgv4'];
            $lgv6 = $buscaDadosDaConexao['lgv6'];
            $localPref = $buscaDadosDaConexao['localpref'] ?? '';
            if(!$localPref){
                $localPref = 110;
            }
            $medIn = $buscaDadosDaConexao['medin'] ?? '';
            if( !$medIn ) {
                $medIn = 0;
            }
            $localpref = 110;
            $medIn = 0;
            $denyCustomerIn = 'yes';
		} else {
            $nomeDoGrupo = $buscaDadosDaConexao['nomedogrupo'];
            $remoteAs = $buscaDadosDaConexao['remoteas'];
            $ipv401 = $buscaDadosDaConexao['ipv4-01'];
            $ipv402 = $buscaDadosDaConexao['ipv4-02'];
            $ipv601 = $buscaDadosDaConexao['ipv6-01'];
            $ipv602 = $buscaDadosDaConexao['ipv6-02'];

            $denyCustomerIn = $buscaDadosDaConexao['denycustomerin'] ?? '';
            $denyCustomerIn = 'yes';
            $localpref = 110;
            $medIn = 0;
            $localPref = $buscaDadosDaConexao['localpref'] ?? '';
            $medIn = $buscaDadosDaConexao['medin'] ?? '';
        }

        $img = 'img/'.$remoteAs.'.jpg';
		if(!file_exists(public_path($img))) {$img = 'img/undefined.jpg'; }

		$configFinal ='';
		if($buscaTemplate->getValue())  {
			if($denyCustomerIn == 'yes'){
				$getAspathTemplate = $this->database->getReference('lib/templates/bgp/deny-customer-in/activate/'.$templateVendor.'/'.$templateFamily.'/aspath')->getSnapshot()->getValue();
                $buscaClientesBgp = $detailClientData['bgp']['interconexoes']['clientesbgp'];
				foreach($buscaClientesBgp as $index => $x) {
					if(isset($x['remoteas'])){
						$configFinal .= str_replace("%customerasn%",$x['remoteas'],$getAspathTemplate);
					}
					$getRecursiveAsn = explode(',', $x['recursive-asn'] ?? '');
					if($getRecursiveAsn){
						foreach($getRecursiveAsn as $i => $y) {
							if($y) {
                                $configFinal .= str_replace("%customerasn%",$y,$getAspathTemplate);
							}
						}
					}
				}
				$getPolicyTemplate = $this->database->getReference('lib/templates/bgp/deny-customer-in/activate/'.$templateVendor.'/'.$templateFamily.'/policy')->getSnapshot()->getValue();
				$configFinal .= str_replace("%nomedogrupo%",$nomeDoGrupo,$getPolicyTemplate);
			}

			foreach ($buscaTemplate->getValue() as $index => $x) {
                if(is_string($x)) {
                    $configFinal .= str_replace("%community0%",$community0,$x);
                    $configFinal = str_replace("%tipoconexao%",$tipoConexao, $configFinal);
                    $configFinal = str_replace("%communitygroup%",$communityGroup, $configFinal);
                    $configFinal = str_replace("%nomedogrupo%",$nomeDoGrupo, $configFinal);
                    $configFinal = str_replace("%id%",$id, $configFinal);
                    $configFinal = str_replace("%asn%",$asn, $configFinal);
                    $configFinal = str_replace("%ipv4-01%",$ipv401, $configFinal);
                    $configFinal = str_replace("%ipv4-02%",$ipv402, $configFinal);
                    $configFinal = str_replace("%ipv6-01%",$ipv601, $configFinal);
                    $configFinal = str_replace("%ipv6-02%",$ipv602, $configFinal);
                    if($tipoConexao == 'ix') {
                        $configFinal = str_replace("%lgv4%",$lgv4, $configFinal);
                        $configFinal = str_replace("%lgv6%",$lgv4, $configFinal);
                    }
                    $configFinal = str_replace("%remoteas%",$remoteAs, $configFinal);
                    $configFinal = str_replace("%medin%",$medIn, $configFinal);
                    $localPref = 110;
                    $configFinal = str_replace("%localpref%",(string)$localPref, $configFinal);
                }
			}
		} else {
			$configFinal = '#não foram encontradas templates para '.$templateVendor.' '.$templateFamily;
		}
        $this->database->getReference($diretorioConexaoBgp.'/config')->set($configFinal);
	    $configSalva = $this->database->getReference($diretorioConexaoBgp.'/config')->getSnapshot()->getValue();

        $debug = $detailClientData['bgp']['interconexoes'][$tipoConexao][$id]['debug'] ?? '';

        $toSendData = [
            'clientId' => $clientId,
            'console-data' => $debug,
            'nomeDoGrupo' => $nomeDoGrupo,
            'img' => $img,
            'targetPeName' =>$targetPeName,
            'configSalva' => $configSalva,
            'buscaProxy' => $buscaProxy,
            'targetPeRouterId' => $targetPeRouterId,
            'targetPeId' => $targetPeId,
            'id' => $id,
            'communityGroup' =>$communityGroup,
            'tipoConexao' => $tipoConexao,
            'remoteAs' => $remoteAs,
            'ipv401' => $ipv401,
            'ipv402' => $ipv402,
            'ipv601' => $ipv601,
            'ipv602' => $ipv602,
            'templateVendor' => $templateVendor,
            'templateFamily' => $templateFamily,
            'targetPeName' => $targetPeName,
            'buscaRelatorios' => $buscaRelatorios,
            'configToken' => $configToken
        ];

        return view('admin.upstreams.template-generall-config', compact('clientId', 'toSendData'));
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
