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
                        <p class="text-danger mb-0 font-12">{{$toSendData['consoleData']}}</p>
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
                        <h5 class="header-title mb-2 text-blue mt-0">Dados do equip</h5>
                        <p class="mb-0">Equip Id: {{$toSendData['rrId']}}</p>
                        <p class="mb-0">hostName: {{$toSendData['hostName']}}</p>
                        <p class="mb-0">RouterId: {{$toSendData['routerId']}}</p>
                        <p class="mb-0">Template Vendor: {{$toSendData['templateVendor']}}</p>
                        <p class="mb-0">Template Family : {{$toSendData['templateFamily']}}</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card-box">
                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#configuration" data-toggle="tab" aria-expanded="true " class="nav-link active">
                                Gonfiguracao Base
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#bgp" data-toggle="tab" aria-expanded="false" class="nav-link">
                                BGP com os routers PE
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#relator" data-toggle="tab" aria-expanded="false" class="nav-link">
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
                                                        <h4 class="modal-title">PR hostName : {{$toSendData['hostName']}}</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col">
                                                            <p class="header-title mb-2">Config global</p>
                                                            <p>{!! $toSendData['configBaseRr'] !!}</p>
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
                                                <td>Sonda remota</td>
                                                <td>
                                                    <select name="sondaRemote" class="form-control" data-toggle="select2" >
                                                        @foreach ($toSendData['buscaSondas'] as $index => $value)
                                                            <option value="{{$index}}">{{$value['hostname']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Aplicar</td>
                                                <td><button onclick="applyBaseConfig()" class="btn btn-info waves-effect waves-light">aplicar config base</button></td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bgp">
                            <h4 class="header-title mb-2">
                                {{ "Selecione para quais PE's a configuração para nova sessão BGP deve ser aplicada" }}
                            </h4>
                            <table class="table nowrap mb-0">
                                <tbody>
                                    <tr>
                                        <td class="d-flex align-items-center" style="gap: 5px">
                                            <button type="button" onclick='selectAll()' class="btn btn-success waves-effect waves-light">
                                                Select All
                                            </button>
                                            <button type="button" onclick='deSelectAll()' class="btn btn-success waves-effect waves-light">
                                                Deselect All
                                            </button>
                                            <label for="sondaid" class="mb-0">Proxy:</label>
                                            <select name="sondaid" id="sondaid" required class="form-control w-auto" data-toggle="select2" >
                                                @foreach ($toSendData['buscaSondas'] as $index => $value)
                                                    <option value="{{$index}}">{{$value['hostname']}}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" onclick='applyConfigPes()' class="btn btn-success waves-effect waves-light">
                                                Aplicar confg BGP com PEs
                                            </button>
                                        </td>
                                    </tr>
                                    @foreach ($toSendData['buscaEquipamentos'] as $equipIndex => $equipVal)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="pe" id="pe" name="equip" value="{{$equipIndex}}">
                                                <label for="base"> {{$equipVal['hostname'] ?? ''}}</label><br>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="relator">
                            <h4 class="header-title mb-2">
                                Registro das últimas configurações aplicadas em :{{$toSendData['hostName']}}
                            </h4>
                            <div>
                                <div class="button-list">
                                    @foreach ($toSendData['buscaRelatorios'] as $relatorIndex => $relatorVal )
                                        <button type="button" class="btn btn-success waves-effect waves-light d-block" data-toggle="modal" data-target="#modal{{$relatorIndex}}">{{$relatorIndex}}</button>
                                    @endforeach
                                </div>
                                @foreach ($toSendData['buscaRelatorios'] as $relatorIndex => $relatorVal )
                                    <div id="modal{{$relatorIndex}}" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Host : {{$toSendData['hostName']}}</h4>
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
        var ref = firebase.database().ref("/clientes/{{$clientId}}/rr/{{$toSendData['rrId']}}/debug");
        ref.on("value", function (snapshot) {
            const data = snapshot.val();
            $('#console').find('p').text(data);
        }, function (error) {
            console.log("Error: " + error.code);
        });
    });

    let applyBaseConfig = () => {

        var sondaId = $("#sondaid").val();
        elementBlock('square1', '.card-box');
        $.ajax({
            type: "POST",
            url: 'proxy-template/applyconfig', // Not sure what to add as URL here
            data: {
                rrId : "{{$toSendData['rrId']}}",
                clientId : "{{ $clientId }}",
                sondaId : sondaId,
                _token : '{{ csrf_token() }}'
            }
        }).done(function( msg ) {
            console.log( msg );
            if(msg['status'] == 'ok') {
                $.NotificationApp.send("Alarm!"
                    ,"Successfully updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
            } else {
                $.NotificationApp.send("Alarm!"
                    ,"Failed"
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

    function selectAll(){
        var ele=document.getElementsByClassName('pe');
        for(var i=0; i<ele.length; i++){
            if(ele[i].type=='checkbox')
                ele[i].checked=true;
        }
    }

    function deSelectAll(){
        var ele=document.getElementsByClassName('pe');
        for(var i=0; i<ele.length; i++){
            if(ele[i].type=='checkbox')
                ele[i].checked=false;
        }
    }

    function applyConfigPes() {
        var sondaId = $("#sondaid").val();
        var checkedEquipArray =[];

        $('input:checkbox[name=equip]').each(function()
        {
            if($(this).is(':checked'))
                checkedEquipArray.push($(this).val());
        });
        if(checkedEquipArray.length == "") {
            $.NotificationApp.send("Alert!"
                ,"Plese click check box!"
                ,"top-right"
                ,"#2ebbdb"
                ,"success",
            );
            return;
        }
        elementBlock('square1', '.card-box');
        if(checkedEquipArray!=null) {
            $.ajax({
            type: "POST",
            url: 'proxy-template/applyconfigPes', // Not sure what to add as URL here
            data: {
                rrId : "{{$toSendData['rrId']}}",
                clientId : "{{ $clientId }}",
                sondaId : sondaId,
                checkedEquipArray : checkedEquipArray,
                _token : '{{ csrf_token() }}'
            }
            }).done(function( msg ) {
                if(msg['status'] === 'ok') {
                    $.NotificationApp.send("Alert!"
                        ,"Successfull operated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                } else {
                    $.NotificationApp.send("Alert!"
                        ,"The operation failed!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }

                elementUnBlock('.card-box');

            });
        }

    }
</script>
@endpush
