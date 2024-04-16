@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                        <li class="breadcrumb-item active">Gerencia de activos</li>
                        <li class="breadcrumb-item active">MPLS Detail</li>
                    </ol>
                </div>
                <h4 class="page-title">MPLS Detail</h4>
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
                    <p class="text-danger mb-0 font-12">{{$toSendData['debug']}}</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header bg-blue py-3 text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#gerenciar" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h5 class="card-title mb-0 text-white">Gerenciar {{$toSendData['hostName']}}</h5>
                </div>
                <div id="gerenciar" class="card-body collapse show">
                    <h5 class="header-title mb-2 text-blue mt-0">Dados do PE</h5>
                    <p class="mb-0">Equip Id: {{$toSendData['equipId']}}</p>
                    <p class="mb-0">Hostname: {{$toSendData['hostName']}}</p>
                    <p class="mb-0">RouterId: {{$toSendData['routerId']}}</p>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class = "table nowrap">
                        <thead>
                            <tr>
                                <td style="width:80%; text-algin:center" class="text-success">
                                    <div class="d-flex">
                                        <h4 class="text-success">Proxy: &nbsp</h4>
                                        <select class="form-control" id="sondaId" name="sondaId"  data-toggle="select2">
                                            @if(is_array($toSendData['buscaSondas']))
                                                @foreach ($toSendData['buscaSondas'] as $sondaIndex => $sondaVal)
                                                    <option value="{{$sondaIndex}}">{{$sondaVal['hostname']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @if($toSendData['buscaConfigs'])
                                @foreach ($toSendData['buscaConfigs'] as $indexConfig => $configVal)
                                    <tr>
                                        <td>
                                            <div id="accordion">
                                                <div class="card mb-1">
                                                    <div class="card-header" id="headingOne{{$indexConfig}}">
                                                        <h5 class="m-0">
                                                            <a class="text-dark" data-toggle="collapse" href="#collapseOne{{$indexConfig}}" aria-expanded="true">
                                                                <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                                                Mostrar/ocultar config {{$indexConfig}}
                                                            </a>
                                                        </h5>
                                                    </div>

                                                    <div id="collapseOne{{$indexConfig}}" class="collapse hide" aria-labelledby="headingOne{{$indexConfig}}" data-parent="#accordion">
                                                        <div class="card-body">
                                                            {!! nl2br($configVal) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="text-align : center">
                                            <input class="mt-1" type="checkbox" id="" name="buscaConfig" value="{{$indexConfig}}">
                                            <label for="base"> selecionar config</label>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td>
                                    <h4 class="text-success">Route reflectors</h4>
                                </td>
                            </tr>
                            @if(is_array($toSendData['buscaRr']))
                                @foreach ($toSendData['buscaRr'] as $indexRr => $rrVal)
                                    @if($rrVal != null)
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div id="accordion">
                                                            <div class="card mb-1">
                                                                <div class="card-header" id="headingOne{{$indexRr}}">
                                                                    <h5 class="m-0">
                                                                        <a class="text-dark" data-toggle="collapse" href="#collapseOne{{$indexRr}}" aria-expanded="true">
                                                                            <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                                                            Mostrar/ocultar config RR {{$indexRr}}
                                                                        </a>
                                                                    </h5>
                                                                </div>

                                                                <div id="collapseOne{{$indexRr}}" class="collapse hide" aria-labelledby="headingOne{{$indexRr}}" data-parent="#accordion">
                                                                    <div class="card-body">
                                                                        @if(isset($rrVal['toLunchConfig']))
                                                                            {!! nl2br($rrVal['toLunchConfig']) !!}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="text-align : center">
                                                <input class="mt-1" type="checkbox" id="" name="buscaRr" value="{{$indexRr}}">
                                                <label for="base"> selecionar RR </label>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td> <label for="id">Token: {{$toSendData['configToken'] ?? ''}}</label></td>
                                <td><input class="form-control" type="text" name="configtoken" id="configtoken" /></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="button" onclick="applyConfig()" class="btn btn-primary" value="aplicar config">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-2">
                        A configuração candidata pode ser revisada no botão abaixo:
                    </h4>
                    <div id="con-close-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Hostname : {{$toSendData['hostName']}}</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <div class="modal-body p-3">
                                    <div class="col">
                                        <p class="header-title mb-2">Config global</p>
                                        <p id="modal-value">{!! nl2br($toSendData['configGlobal']) !!}</p>
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
        var ref = firebase.database().ref("/clientes/{{$clientId}}/equipamentos/{{$toSendData['equipId']}}/debug");
        ref.on("value", function (snapshot) {
            const data = snapshot.val();
            $('#console').find('p').text(data);
        }, function (error) {
        });
    });
    let applyConfig = () => {
        var buscaConfigIds = [];
        var buscaRrIds = [];
        var sondaId = $("#sondaId").val();
        var inputConfigToken = $("#configtoken").val();

        $('input:checkbox[name=buscaRr]').each(function()
        {
            if($(this).is(':checked'))
                buscaRrIds.push($(this).val());
        });
        $('input:checkbox[name=buscaConfig]').each(function()
        {
            if($(this).is(':checked'))
                buscaConfigIds.push($(this).val());
        });

        if( buscaConfigIds?.length == 0 && buscaRrIds?.length == 0 ) {
            // if(inputConfigToken == '') {
            //     $.NotificationApp.send("Alarm!"
            //         ,"Plese type token information!"
            //         ,"top-right"
            //         ,"#2ebbdb"
            //         ,"success",
            //     );
            //     $("#configtoken").focus();
            //     return;
            // }
            $.NotificationApp.send("Alarm!"
                ,"Plese click checkbox!"
                ,"top-right"
                ,"#2ebbdb"
                ,"success",
            );
            return;
        }

        elementBlock('square1', 'table');

        $.ajax({
            type: "POST",
            url: "{{route('mpls-detail.applyConfig')}}", // Not sure what to add as URL here
            data: {
                equipId : "{{ $toSendData['equipId']}}",
                buscaConfigIds : buscaConfigIds,
                buscaRrIds : buscaRrIds,
                sondaId : sondaId,
                inputConfigToken : inputConfigToken,
                clientId : "{{ $clientId }}",
                _token : '{{ csrf_token() }}'
            }
        }).done(function( msg ) {
            console.log(msg);
            if(msg['status'] == 'ok') {
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
            elementUnBlock('table');
            $("#modal-value").text(msg['relatorio']);
        }).fail(function(xhr, textStatus, errorThrown) {
            $.NotificationApp.send("Alarm!"
                ,"The operation failed!"
                ,"top-right"
                ,"#2ebbdb"
                ,"error",
            );
            elementUnBlock('table');
        });
    }
</script>
@endpush
