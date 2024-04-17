@extends('layouts.admin')
@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Gerencia de activos</li>
                            <li class="breadcrumb-item active">Proxies</li>
                            <li class="breadcrumb-item active">Localhost</li>

                        </ol>
                    </div>
                    <h4 class="page-title">Localhost</h4>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-blue py-3 text-white">
                        <div class="card-widgets">
                            <a data-toggle="collapse" href="#console" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                        </div>
                        <h5 class="card-title mb-0 text-white">Console</h5>
                    </div>
                    <div id="console" class="card-body collapse show">
                        <p class="text-danger mb-0 font-12" id="console_data">{{$toSendData['console_data']}}</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-blue py-3 text-white">
                        <div class="card-widgets">
                            <a data-toggle="collapse" href="#gerenciar" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                        </div>
                        <h5 class="card-title mb-0 text-white">Gerenciar {{$toSendData['hostname']}}</h5>
                    </div>
                    <div id="gerenciar" class="card-body collapse show">
                        <h5 class="header-title mb-2 text-blue mt-0">Dados do equip</h5>
                        <p class="mb-0">Equip Id: {{$toSendData['proxy_id']}}</p>
                        <p class="mb-0">Hostname: {{$toSendData['hostname']}}</p>
                        <p class="mb-0">RouterId: {{$toSendData['proxyip4']}}</p>
                        <p class="mb-0">Plataforma: {{$toSendData['platform']}}</p>
                        <p class="mb-0">Sistema Operacional : {{$toSendData['system']}}</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card-box">

                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#configuration" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                Gonfiguracao Base
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#relator" data-toggle="tab" aria-expanded="true" class="nav-link">
                                Relators
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="configuration">
                            <p class="mb-3">
                                Essa é a configuração obrigatória para este RR. É mandatório que seja aplicada antes te estebalecerem sessões com os routers PE
                            </p>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <h4 class="header-title mb-2">
                                            A configuração candidata pode ser revisada no botão abaixo:
                                        </h4>

                                        <div id="con-close-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">PR Hostname : {{$toSendData['hostname']}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col">
                                                            <p class="header-title mb-2">Config global</p>
                                                            <p>{!! $toSendData['configBase'] !!}</p>
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
                                                    <td>Configuração base</td>
                                                    <td>
                                                        <button onclick="applyBaseConfig()" class="btn btn-info waves-effect waves-light">aplicar config base</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Atualizar inoc-config</td>
                                                    <td><button onclick="updateIncoConfig()" class="btn btn-info waves-effect waves-light">autalizar inoc-config</button></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div id="new-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">PR Hostname : {{$toSendData['hostname']}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col">
                                                            <p class="header-title mb-2">Config aplicada</p>
                                                            <p id="report"></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-list">
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#new-modal">Visualizar relatório</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="relator">
                            <h4 class="header-title mb-2">
                                Registro das últimas configurações aplicadas em :{{$toSendData['hostname']}}
                            </h4>
                            <div class="card-box col-6">
                                @if(is_array($toSendData['buscarRelators']))
                                    @foreach ( array_reverse($toSendData['buscarRelators']) as $indexRe => $valueRe )
                                        <div class="button-list mb-1">
                                            <button type="button" class="btn btn-primary waves-effect" data-toggle="modal" data-target="#relators-modal-{{$indexRe}}">{{ $indexRe }}</button>
                                        </div>
                                        <div id="relators-modal-{{ $indexRe }}" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">
                                                            PR Hostname : {{$toSendData['hostname']}}<br>
                                                            Config Token : {{$toSendData['configToken']}}
                                                        </h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col">
                                                            <p class="header-title mb-2">Relatório de configuração:</p>
                                                            <p>{!! colorReport(nl2br($valueRe)) !!}</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    sem dados
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("js")
<script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-database.js"></script>
<script src="{{ asset('admin_assets/js/firebase_config.js') }}"></script>

<script>

    $(document).ready(function(){
        firebase.initializeApp(firebaseConfig);
        var ref = firebase.database().ref("/clientes/{{$clientId}}/sondas/{{$toSendData['proxy_id']}}/debug");
        ref.on("value", function (snapshot) {
            const data = snapshot.val();
            $('#console').find('p').text(data);
        }, function (error) {
            console.log("Error: " + error.code);
        });
    });

    let applyBaseConfig = () => {
        elementBlock('square1', '.card-box');
        $.ajax({
            type: "POST",
            url: 'proxy-localhost/applyconfig', // Not sure what to add as URL here
            data: {
                proxyId : "{{$toSendData['proxy_id']}}",
                clientId : "{{ $clientId }}",
                _token : '{{ csrf_token() }}'
            }
        }).done(function( msg ) {
            if(msg['status'] == 'ok') {
                console.log( msg );
                $("#report").html(msg['relatorio']);
                $.NotificationApp.send("Alarm!"
                    ,"Successfully updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
            } else {
                $.NotificationApp.send("Alarm!"
                    ,'The operation failed!'
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
            elementUnBlock('.card-box');
        });
    }
    let updateIncoConfig = () => {
        elementBlock('square1', '.card-box');
        $.ajax({
            type: "POST",
            url: 'proxy-localhost/update-inco-config', // Not sure what to add as URL here
            data: {
                proxyId : "{{$toSendData['proxy_id']}}",
                clientId : "{{ $clientId }}",
                _token : '{{ csrf_token() }}'
            }
        }).done(function( msg ) {
            if(msg['status'] == 'ok') {
                console.log( msg );
                $("#report").html(msg['relatorio']);
                $.NotificationApp.send("Alarm!"
                    ,"Successfully updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
            } else {
                $.NotificationApp.send("Alarm!"
                    ,'The operation failed!'
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
            elementUnBlock('.card-box');
        });
    }
</script>
@endpush
