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
                <div class="card-body">
                    <p class="header-title mb-4 text-success  mt-0">Console</p>
                    <p class="ml-2 text-danger font-12" id="console_data">
                        {{$toSendData['console-data']}}
                    </p>
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
                        <a href="#connect" data-toggle="tab" aria-expanded="false" class="nav-link active">
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
                                                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-info waves-effect waves-light">Fetchar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.modal -->
                                    <div class="button-list">
                                        <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#con-close-modal">Visual Config Base</button>
                                    </div>
                                </div>
                                <div class="card-box mb-0">
                                    <table class="table nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>Proxy</th>
                                            <th>
                                                 <select name="proxyid" id="proxyid" required >
                                                    @foreach ($toSendData['buscaProxy'] as $index => $y)
                                                        <option value="{{$index}}">{{$y['hostname']}}</option>
                                                    @endforeach
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
                                            <th><button onclick="" class="btn btn-info waves-effect waves-light">autalizar inoc-config</button></th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="connect">
                            <h4 class="header-title mb-2"> DETAIL
                            </h4>
                        <div class="card-box col-6">

                        </div>
                    </div>
                    <div class="tab-pane show active" id="relator">
                            <h4 class="header-title mb-2"> Registro das últimas configurações aplicadas em :
                            </h4>
                        <div class="card-box col-6">

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

    {{-- <script>

        function saveData(){

            var nomeVal = $('#nome').val();
            var popVal = $('#pop').val();
            var asnVal = $('#asn').val();
            var bgp1Val = $('#bgp1').val();
            var bgp2Val = $('#bgp2').val();
            var bgp01Val = $('#bgp01').val();
            var bgp02Val = $('#bgp02').val();
            var equipVal = $('#equip').val();
            var checkVal = $('#check').val();
            $.ajax({
                type: "POST",
                url: '{{ route("upstreams.store") }}',
                data: {
                    nomeVal :nomeVal,
                    popVal :popVal,
                    asnVal :asnVal,
                    bgp1Val :bgp1Val,
                    bgp2Val :bgp2Val,
                    bgp01Val :bgp01Val,
                    bgp02Val :bgp02Val,
                    equipVal :equipVal,
                    checkVal :checkVal,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    $('#nome').val("");
                    $('#pop').val("");
                    $('#asn').val("");
                    $('#bgp1').val("");
                    $('#bgp2').val("");
                    $('#bgp01').val("");
                    $('#bgp02').val("");
                    $('#equip').val("");
                    $('#check').val("");
                }
            });
        }
    </script> --}}
@endsection