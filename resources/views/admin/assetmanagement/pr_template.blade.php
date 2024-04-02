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
                    <div class="card-body">
                        <p class="header-title mb-4 text-success  mt-0">Console</p>
                        <p class="ml-2 text-danger font-12">{{$toSendData['consoleData']}}</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4 text-success  mt-0">Gerenciar {{$toSendData['hostName']}}</h4>
                        <div class="col-6">
                            <h5 class="header-title mb-2 text-blue mt-0">Dados do equip</h5>
                            <p class="mb-0">Equip Id: {{$toSendData['rrId']}}</p>
                            <p class="mb-0">hostName: {{$toSendData['hostName']}}</p>
                            <p class="mb-0">RouterId: {{$toSendData['routerId']}}</p>
                            <p class="mb-0">Template Vendor: {{$toSendData['templateVendor']}}</p>
                            <p class="mb-0">Template Family : {{$toSendData['templateFamily']}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
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
                        <div class="tab-pane" id="configuration">
                            <p class="mb-3">Essa é a configuração obrigatória para este RR. É mandatório que seja aplicada antes te estebalecerem sessões com os routers PE
                            </p>

                            <div class="row">
                                <div class="col-6">
                                    <div class="card-box p-2">
                                        <h4 class="header-title mb-2">
                                            A configuração candidata pode ser revisada no botão abaixo:
                                        </h4>
                                        <!-- sample modal content -->
                                        <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
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
                                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-info waves-effect waves-light">Fetchar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.modal -->
                                        <div class="button-list">
                                            <!-- Responsive modal -->
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#con-close-modal">Visual Config Base</button>
                                        </div>
                                    </div>
                                    <div class="card-box mb-0">
                                        <table class="table nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>Sonda remota</th>
                                                <th>
                                                    <select name="sondaRemote" class="form-control" data-toggle="select2" >
                                                        @foreach ($toSendData['buscaSondas'] as $index => $value)
                                                            <option value="{{$index}}">{{$value['hostname']}}</option>
                                                        @endforeach
                                                </select>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Aplicar</th>
                                                <th><button onclick="" class="btn btn-info waves-effect waves-light">aplicar config base</button></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div> <!-- end card-box-->
                            </div>
                        </div>
                        <div class="tab-pane show active" id="bgp">
                                <h4 class="header-title mb-2">
                                    Selecione para quais PE's a configuração para nova sessão BGP deve ser aplicada
                                </h4>
                            <div class="card-box col-10">
                                <table class="table nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="button" onclick='selectAll()' value="Select All"/>
                                                <input type="button" onclick='deSelectAll()' value="Deselect All"/>
                                                <label for="sonda">Proxy:</label>
                                                <select name="sondaid" id="sondaid" required >
                                                    @foreach ($toSendData['buscaSondas'] as $index => $y)
                                                        <option value="{{$index}}">{{$y['hostname']}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="button" onclick="applyConfigPes()" name="aplicar config pes" value="Aplicar confg BGP com PEs" />
                                            </td>
                                        </tr>
                                        @foreach ($toSendData['buscaEquipamentos'] as $equipIndex => $equipVal)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="pe" id="pe" name="equip" value="{{$equipIndex}}">
                                                    <label for="base"> {{$equipVal['hostname']}}</label><br>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane show active" id="relator">
                                <h4 class="header-title mb-2"> Registro das últimas configurações aplicadas em :{{$toSendData['hostName']}}
                                </h4>
                            <div class="card-box col-9">
                                @if (true)
                                    <div class="button-list">
                                        <ul>
                                            @foreach ($toSendData['buscaRelatorios'] as $relatorIndex => $relatorVal )
                                                <li>
                                                    <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#modal{{$relatorIndex}}">{{$relatorIndex}}</button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                     @foreach ($toSendData['buscaRelatorios'] as $relatorIndex => $relatorVal )
                                        <div id="modal{{$relatorIndex}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
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
                                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-info waves-effect waves-light">Fetchar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.modal -->
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-box-->
            </div> <!-- end col -->

        </div>
    </div>
@endsection

@push("js")
<script>
    let applyBaseConfig = () => {
        var sondaId = $("#sonda").val();
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
                console.log( msg );
            });
        }

    }
</script>
@endpush
