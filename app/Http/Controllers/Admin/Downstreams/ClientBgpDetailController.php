<?php

namespace App\Http\Controllers\Admin\Downstreams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SCP;

class ClientBgpDetailController extends Controller
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
    public function index(Request $request)
    {
        $clientId = $request['client_id'];
        $bgpClientId = $request['clientbgp'];

        $clientDetailData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $bgpClient = $clientDetailData['bgp']['interconexoes']['clientesbgp'] ?? [];
        $remoteAs =  $bgpClient[$bgpClientId]['remoteas'];
        $nomeDoClienteBgp = $bgpClient[$bgpClientId]['nomedoclientebgp'];
        $nomeDoGrupo = 'CLIENTE-ASN-'.$remoteAs.'-'.$nomeDoClienteBgp;
        $blocosIpv6 = $bgpClient[$bgpClientId]['blocosipv6'];
        $blocosIpv4 = $bgpClient[$bgpClientId]['blocosipv4'];
        $communitySet = $bgpClient[$bgpClientId]['communityset'];
        $ipv4remoto01 = $bgpClient[$bgpClientId]['ipv4-01'];
        $ipv4remoto02 = $bgpClient[$bgpClientId]['ipv4-02'];
        $ipv6remoto01 = $bgpClient[$bgpClientId]['ipv6-01'];
        $ipv6remoto02 = $bgpClient[$bgpClientId]['ipv6-02'];
        $buscaRr = $clientDetailData['rr'];
        $asn = $clientDetailData['bgp']['asn'];
        $buscaSondas = $clientDetailData['sondas'];
        $idDoPe = $bgpClient[$bgpClientId]['peid'];
        $nomeCliente = $clientDetailData['nome'];
        $ipv4bgpmultihop = $clientDetailData['bgp']['ipv4bgpmultihop'];
        $ipv6bgpmultihop = $clientDetailData['bgp']['ipv6bgpmultihop'];

        $nomeDoPe = '';
        $ipDoPe = '';

        if(isset($idDoPe) || $idDoPe == null) {
            $nomeDoPe = $clientDetailData['equipamentos'][$idDoPe]['hostname'];
            $ipDoPe = $clientDetailData['equipamentos'][$idDoPe]['routerid'];
        }

        $blocosIpv4SemBarra = preg_replace('/[ \/]+/', ' ', trim($blocosIpv4));
        $blocosIpv6SemBarra = preg_replace('/[ \/]+/', ' ', trim($blocosIpv6));

        $loopBlocosIpv4 = explode(',', $blocosIpv4SemBarra);
        $loopBlocosIpv6 = explode(',', $blocosIpv6SemBarra);

        $configIpPrefix = nl2br("!bgp_config_begin \n system-view \n");
        if(isset($loopBlocosIpv4)) {
            foreach ($loopBlocosIpv4 as $block4Index => $block4Value) {
                $configIpPrefix .=nl2br('ip ip-prefix '.$nomeDoGrupo.'-IPV4 permit '.$block4Value."  \n ");
                $configIpPrefix .=nl2br('ip ip-prefix '.$nomeDoGrupo.'-IPV4 permit '.$block4Value." less-equal 24 \n ");
            }
        }

        if (isset($blocosIpv6)){
            foreach ($loopBlocosIpv6 as $block6Index => $block6Value) {
                $configIpPrefix .=nl2br('ip ipv6-prefix '.$nomeDoGrupo.'-IPV6 permit '.$block6Value."  \n ");
                $configIpPrefix .=nl2br('ip ipv6-prefix '.$nomeDoGrupo.'-IPV6 permit '.$block6Value." less-equal 48 \n ");
            }
        }

	    $getTemplate = $this->database->getReference('/lib/templates/huawei-6700/bgp-cliente/peconfig')->getSnapshot()->getValue() ?? '';
        $configFinal = $configIpPrefix;
	    $configFinal .= str_replace("%nomedogrupo%",$nomeDoGrupo,$getTemplate);
        $configFinal = str_replace("%localas%",$asn,$configFinal);
        $configFinal = str_replace("%remoteas%",$remoteAs,$configFinal);
        $configFinal = str_replace("%communityset%",$communitySet,$configFinal);
        $configFinal = str_replace("%ipv401%",$ipv4remoto01,$configFinal);
        $configFinal = str_replace("%ipv402%",$ipv4remoto02,$configFinal);
        $configFinal = str_replace("%ipv601%",$ipv6remoto01,$configFinal);
        $configFinal = str_replace("%ipv602%",$ipv6remoto02,$configFinal);
	    $configFinal = nl2br($configFinal);

    	$this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/clientesbgp/'.$bgpClientId.'/configs/pe')->set($configFinal);

        $configPeFirebase = $bgpClient[$bgpClientId]['configs']['pe'] ?? '';
        $configPe = nl2br($configPeFirebase);

        $token = bin2hex(random_bytes(64));
        $token = '!'.$token;

        $getTemplateRr = $this->database->getReference('/lib/templates/huawei-6700/bgp-cliente/rrconfig')->getSnapshot()->getValue() ?? '';

        $configRrFinal = str_replace("%nomedogrupo%",$nomeDoGrupo,$getTemplateRr);
        $configRrFinal = str_replace("%localas%",$asn,$configRrFinal);
        $configRrFinal = str_replace("%remoteas%",$remoteAs,$configRrFinal);
        $configRrFinal = str_replace("%ipv401%",$ipv4remoto01,$configRrFinal);
        $configRrFinal = str_replace("%ipv402%",$ipv4remoto02,$configRrFinal);
        $configRrFinal = str_replace("%ipv601%",$ipv6remoto01,$configRrFinal);
        $configRrFinal = str_replace("%ipv602%",$ipv6remoto02,$configRrFinal);
        $configRrFinal = nl2br($configRrFinal);
	    $configRrFinal .= $token;

        $this->database->getReference('clientes/'.$clientId.'/bgp/interconexoes/clientesbgp/'.$bgpClientId.'/configs/rr')->set($configRrFinal);

        $configRrFirebase = $bgpClient[$bgpClientId]['configs']['rr'] ?? '';

        $configRr = nl2br($configRrFirebase);

        $toSendData = [
            'nomeDoClienteBgp' => $nomeDoClienteBgp,
            'remoteAs' => $remoteAs,
            'nomeDoGrupo' => $nomeDoGrupo,
            'blocosIpv4' => $blocosIpv4,
            'blocosIpv6' => $blocosIpv6,
            'configPeFirebase' => $configPeFirebase,
            'configRrFirebase' => $configRrFirebase,
            'buscaSondas' => $buscaSondas,
            'idDoPe' => $idDoPe,
            'nomeDoPe' => $nomeDoPe,
            'ipDoPe' => $ipDoPe,
            'buscaRr' => $buscaRr,
            'nomeCliente' => $nomeCliente,
            'ipv4bgpmultihop' => $ipv4bgpmultihop,
            'ipv6bgpmultihop' => $ipv6bgpmultihop,
            'ipv4remoto01' => $ipv4remoto01,
            'ipv6remoto01' => $ipv6remoto01,
            'ipv4remoto02' => $ipv4remoto02,
            'ipv6remoto02' => $ipv6remoto02,
            'asn' => $asn,
            'clientId' => $clientId,
            'bgpClientId' => $bgpClientId
        ];

        return view('admin.downstreams.client_bgp_detail', compact('clientId', 'toSendData'));
    }

    public function aplicarConfig(Request $request) {



        $clientId = $request['clientid'];
        $bgpclienteId = $request['bgpclienteid'];

        $diretorioBgpath = 'clientes/' . $clientId . '/bgp';
	    $diretorioStatusConfig = $diretorioBgpath.'/interconexoes/clientesbgp/'.$bgpclienteId.'/configs/peconfigstatus';

        $sondaId = $request['sondaId'];
        $targetpeid = $request['targetpeid'];

        $tokenPeConfig = bin2hex(random_bytes(64));
        $tokenPeConfig = substr($tokenPeConfig,0, -85);
    	$tokenPeConfig = substr($tokenPeConfig, 2, -1);

	    $debugRemoteFile = 'DEBUG-'.$clientId;
        $clientDetailData = $this->database->getReference('clientes/' . $clientId)->getSnapshot()->getValue();
        $sondaIpv4 = $clientDetailData['sondas'][$sondaId]['ipv4'] ?? '';
        $sondaPortaSsh = $clientDetailData['sondas'][$sondaId]['portassh'] ?? '';
        $sondaUser = $clientDetailData['sondas'][$sondaId]['user'] ?? '';
        $sondaPwd = $clientDetailData['sondas'][$sondaId]['pwd'] ?? '';
        $diretorioBgp = $clientDetailData['bgp'] ?? [];
        $statusConfigBgpClientePe = $diretorioBgp['interconexoes']['clientesbgp'][$bgpclienteId]['configs']['peconfigstatus'] ?? '';
        $debug = 'arquivo copiado! iniciando comandos...';

        $this->database->getReference($diretorioStatusConfig)->set('iniciando...');

        $ssh = new SSH2($sondaIpv4, $sondaPortaSsh);
        $checkConfigSucess = $ssh->exec('cat '.$debugRemoteFile.'.log | grep '.$tokenPeConfig);
        $ssh->exec('ls -la 2>&1 &');
        $checkConfigSucess = 'resultado do check config: '.$checkConfigSucess;

        $statusConfig = '';
        if ($checkConfigSucess) {
            $statusConfig = 'config aplicada com sucesso';
            $this->database->getReference($diretorioBgpath.'/interconexoes/clientesbgp/'.$bgpclienteId.'/configs/peconfigstatus')->set($statusConfig);
        }
        else {
            $statusConfig = 'houve um problema!';
            $this->database->getReference($diretorioBgpath.'/interconexoes/clientesbgp/'.$bgpclienteId.'/configs/peconfigstatus')->set($statusConfig);
        }

        $firstPanelData = [
            'debug' => $debug,
            'token' => $tokenPeConfig,
            'statusConfigBgpClientePe' => $statusConfigBgpClientePe,
            'sondaIpv4' => $sondaIpv4,
            'sondaId' => $sondaId,
            'sondaPortaSsh' => $sondaPortaSsh
        ];

        $nomeCliente = $clientDetailData['nome'];

        $targetPePort = $clientDetailData['equipamentos'][$targetpeid]['hostname'] ?? '';
        $targetPeIpv4 = $clientDetailData['equipamentos'][$targetpeid]['routerid'] ?? '';
        $senhaInocmon = $clientDetailData['seguranca']['senhainocmon'] ?? '';

        $nomeDoClienteBgp = $clientDetailData['bgp']['interconexoes']['clientesbgp'][$bgpclienteId]['nomedoclientebgp'];
        $configFileNamePe = 'CONFIG-PE-BGP-'.$nomeDoClienteBgp.'-'.$bgpclienteId.'.cfg';
        $configFileNameRr = 'CONFIG-RR-BGP-CLIENTE-'.$nomeDoClienteBgp.'-'.$bgpclienteId.'.cfg';


        $secondPanelData = [
            'nomeClient' => $nomeCliente,
            'targetpeid' => $targetpeid,
            'targetPePort' => $targetPePort,
            'targetPeIpv4' => $targetPeIpv4,
            'senhaInocmon' => $senhaInocmon,
            'sondaUser' => $sondaUser,
            'sondaPwd' => $sondaPwd,
            'configFileNamePe' => $configFileNamePe,
            'configFileNameRr' => $configFileNameRr
        ];


        return view('admin.downstreams.aplicar_config_bgp', compact('clientId', 'bgpclienteId', 'firstPanelData', 'secondPanelData'));
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
