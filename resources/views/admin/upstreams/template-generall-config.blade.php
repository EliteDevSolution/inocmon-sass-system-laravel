@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>
        .select2-container{
            width: 100% !important;
        }
        .select2-selection--single{
            height: 32px !important;
            border-color: #ced4da !important;
        }
        .select2-selection__rendered{
            /*line-height: 32px !important;*/
        }
    </style>
@endsection

@section('content')
    <div class="columns">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Upstreams</li>
                            <li class="breadcrumb-item active">Traffic</li>
                            <li class="breadcrumb-item active">Generate Config</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Generate Config</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-blue py-3 text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#console" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h5 class="card-title mb-0 text-white">Console</h5>
                </div>
                <div id="console" class="card-body collapse show">
                    <p class="text-danger mb-0 font-12" id="console_data">{{$toSendData['console-data']}}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card-box">
                <div class="mb-1">
                    <img style="width : 30px; height : 30px" src="{{ asset($toSendData['img']) }}"/>
                    {{$toSendData['nomeDoGrupo']}}
                </div>
                <ul class="nav nav-tabs nav-bordered">
                    <li class="nav-item">
                        <a href="#configuration" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Gonfiguracao Base
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#connect" data-toggle="tab" aria-expanded="false" class="nav-link">
                            Detalhes da conexaos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#relator" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            Relators
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" id="configuration">
                        <p class="mb-3">
                            Essa é a configuração obrigatória para este RR. É mandatório que seja aplicada antes te estebalecerem sessões com os routers PE
                        </p>
                        <div class="row">
                            <div class="col-12">
                                <div class="p-2">
                                    <h4 class="header-title mb-2">
                                        A configuração candidata pode ser revisada no botão abaixo:
                                    </h4>

                                    <!-- sample modal content -->
                                    <div id="con-close-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">{{$toSendData['nomeDoGrupo']}} {{$toSendData['targetPeName']}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col">
                                                        <p class="header-title mb-2">Config global</p>
                                                        <p>{{$toSendData['configSalva']}}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="button-list">
                                        <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#con-close-modal">Visual Config Base</button>
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <table class="table nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>Proxy</th>
                                            <th>
                                                 <select name="proxyid" id="proxyid" required class="form-control">
                                                    @if(is_array($toSendData['buscaProxy']))
                                                        @foreach ($toSendData['buscaProxy'] as $index => $y)
                                                            <option value="{{$index}}">{{$y['hostname']}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Target PE</th>
                                            <th>
                                                <a href="{{ route("mpls-detail.index", array('client_id' => $toSendData['clientId']
                                                         , 'equip_id' => $toSendData['targetPeId'] ) ) }}">
                                                    {{$toSendData['targetPeName']}} : ({{$toSendData['targetPeRouterId']}})
                                                </a>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Atualizar inoc-config</th>
                                            <th><button onclick="applyConfig()" class="btn btn-info waves-effect waves-light">autalizar inoc-config</button></th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="connect">
                        <h4 class="header-title mb-2"> DETAIL INFROMATION
                        </h4>
                        <div class="col-12">
                            <p>ID da conexao: {{$toSendData['id']}}</p>
                            <p>ASN remoto: {{$toSendData['remoteAs']}}</p>
                            <p>Ipv4 remoto 01: {{$toSendData['ipv401']}}</p>
                            <p>Ipv4 remoto 02: {{$toSendData['ipv402']}}</p>
                            <p>Ipv6 remoto 01: {{$toSendData['ipv601']}}</p>
                            <p> Ipv6 remoto 02: {{$toSendData['ipv602']}}</p>
                            <p>Template Vendor: {{$toSendData['templateVendor']}}</p>
                            <p> Template Family: {{$toSendData['templateFamily']}}</p>
                            <p>Target PE: {{$toSendData['targetPeName']}}</p>
                        </div>
                    </div>
                    <div class="tab-pane show active" id="relator">
                            <h4 class="header-title mb-2"> Registro das últimas configurações aplicadas em : {{$toSendData['targetPeName']}}
                            </h4>
                        <div class="col-12">
                            <div class="button-list">
                                @if (is_array($toSendData['buscaRelatorios']))
                                    @foreach ($toSendData['buscaRelatorios'] as $relatorIndex => $relatorVal )
                                        <button type="button" class="btn btn-success waves-effect waves-light d-block" data-toggle="modal" data-target="#modal{{$relatorIndex}}">{{$relatorIndex}}</button>
                                    @endforeach
                                @endif
                            </div>
                            @if (is_array($toSendData['buscaRelatorios']))
                                @foreach ($toSendData['buscaRelatorios'] as $relatorIndex => $relatorVal )
                                    <div id="modal{{$relatorIndex}}" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Config: : {{$toSendData['nomeDoGrupo']}}</h4>
                                                    <h4 class="modal-title">Token : {{$toSendData['configToken']}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col">
                                                        <p class="header-title mb-2">Relatório de configuração:</p>
                                                        <p>{{$relatorVal}}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.modal -->
                                @endforeach
                            @else
                                <div class="p-2 card-box m-4" style="text-align: center">
                                    <p class="header text-danger">
                                        sem dados
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-database.js"></script>
    <script src="{{ asset('admin_assets/js/firebase_config.js') }}"></script>

    <script>

        $(document).ready(function(){
            firebase.initializeApp(firebaseConfig);
            var ref = firebase.database().ref("/clientes/{{$clientId}}/bgp/interconexoes/{{$toSendData['tipoConexao']}}/{{$toSendData['id']}}/debug");
            ref.on("value", function (snapshot) {
                const data = snapshot.val();
                $('#console').find('p').text(data);
            }, function (error) {
                console.log("Error: " + error.code);
            });
        });

        function applyConfig(){
            var proxyId = $("#proxyid").val();
            console.log(proxyId);
            if(proxyId == null) {
                $.NotificationApp.send("Alarm!"
                    ,"Plese select the proxy!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
                return;
            }
            elementBlock('square1', '.card-box');
            $.ajax({
                type: "POST",
                url: "{{route('template-generate-config.applyConfig')}}",
                data: {
                    tipoConexao : "{{$toSendData['tipoConexao']}}",
                    communityGroup : "{{$toSendData['communityGroup']}}",
                    id : "{{$toSendData['id']}}",
                    proxyId : proxyId,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully updated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                } else {
                    $.NotificationApp.send("Alarm!"
                        ,"Failed updated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('.card-box');
            }).fail(function(xhr, textStatus, errorThrown) {
                $.NotificationApp.send("Alarm!"
                    ,"Failed updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                elementUnBlock('card-box');
            });
        }
    </script>
@endsection