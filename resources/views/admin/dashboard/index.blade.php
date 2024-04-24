@extends('layouts.admin')
@section('content')
<div class="column">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                        <li class="breadcrumb-item active">Summary</li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card-box">
                <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                <h4 class="mt-0 font-16">Upstreams</h4>
                <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{$dashboardData['upstreamCount']}}</span></h2>
                <p class="text-muted mb-1 text-truncate text-center">UPSTREAMS cadastradoss</p>
                <p class="text-muted mb-0 text-center">
                    <a href="{{route("mpls_pe.index", array('client_id' => $clientId))}}">ver detalhes</a>
                </p>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card-box">
                <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                <h4 class="mt-0 font-16">Clients</h4>
                <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">{{$dashboardData['clientsCount']}}</span></h2>
                <p class="text-muted mb-1 text-truncate text-center">sessões BGP de clientes</p>
                <p class="text-muted mb-0 text-center">
                    <a href="{{route("mpls_pe.index", array('client_id' => $clientId))}}">
                        ver detalhes
                    </a>
                </p>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card-box">
                <div class="row">
                    <div class="col-6">
                        <div class="avatar-sm bg-blue rounded">
                            <i class="fe-aperture avatar-title font-22 text-white"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-right">
                            <h3 class="text-dark my-1"><span data-plugin="counterup"></span></h3>
                            <p class="text-muted mb-1 text-truncate">{{$dashboardData['databaseInuse']}}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h6 class="text-uppercase">Target <span class="float-right">{{$dashboardData['databasePercent']}}%</span></h6>
                    <div class="progress progress-sm m-0">
                        <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="{{$dashboardData['databasePercent']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$dashboardData['databasePercent']}}%">
                            <span class="sr-only">{{$dashboardData['databasePercent']}}% Complete</span>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-0 text-center" style="margin:7px">
                    <a href="">ver detalhes</a>
                </p>
            </div> <!-- end card-box-->
        </div> <!-- end col -->
    </div>
    <div class="row>
        <div class="col-12">
            <div class="card-box ribbon-box">
                <div class="ribbon ribbon-success float-left"><i class="mdi mdi-access-point mr-1"></i> Rede MPLS</div>
                <div class="ribbon-content" style="text-align: center">
                    <h2 class="text-success display-title-tour mt-0">
                        {{$dashboardData['equipmentCount']}} PEs cadastrados
                    </h2>
                    <p class="font-16 text-blue"> Arquivo lsdb </p>
                    @if ($dashboardData['ospData'] == '')
                        <div id="no-data">
                            <p class="font-16 text-blue">
                                Ainda não existe um arquivo!
                            </p>
                        </div>
                    @else
                        <div id="data-download">
                            <a href="{{ route('downloadFile') }}?filename={{ urlencode($dashboardData['fileName']) }}"> DOWNLOAD {{$dashboardData['dspVendor']}}</a>
                            <br>
                            <a href="https://topolograph.com/upload-ospf-isis-lsdb" target="_blank">TOPOLOGRAPH</a>
                            <hr>
                            <p class="font-14 text-blue">Sobreescrever Existente</p>
                        </div>
                    @endif
                    <div class="col-12 m-3">
                        <div style="display: flex; width : max-content ; margin : auto; gap : 10px">
                            <select name="targetproxyid" id="proxy" class="form-control">
                                @if(is_array($dashboardData['sondas']))
                                    @foreach ($dashboardData['sondas'] as $index => $value)
                                        <option value="{{$index}}">{{$value['hostname']}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <select name="targetrr" class="form-control" id="rr">
                                @foreach ($dashboardData['rr'] as $buscaIndex => $buscarVal)
                                    @if ($buscarVal != null)
                                        <option value="{{$buscaIndex}}">{{$buscarVal['hostname']}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button onclick="excuteCommand()" class="btn btn-info dropdown-toggle">
                                Gerar Lsdb
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script>
        function excuteCommand() {
            var proxy = $("#proxy").val();
            var rr = $("#rr").val();
            if( proxy == null && rr == null ) {
                $.NotificationApp.send("Alarm!"
                    ,"The field is empty!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                return;
            }
            elementBlock('square1', '.ribbon-box');
            $.ajax({
                type: "post",
                url: "{{ route('dashboard.executeSshCommand') }}",
                data: {
                    proxy : proxy,
                    rr : rr,
                    clientId : "{{$clientId}}",
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    console.log(msg);
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully added!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                } else {
                    $("#no-data").show();
                    $("#data-download").hide();
                    $.NotificationApp.send("Alarm!"
                        ,"Failed updated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('.ribbon-box');
            }).fail(function(xhr, textStatus, errorThrown) {
                $.NotificationApp.send("Alarm!"
                    ,"The operation failed!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                elementUnBlock('.ribbon-box');
                $("#no-data").show();
                $("#data-download").hide();
            });
        }
    </script>
@endsection

