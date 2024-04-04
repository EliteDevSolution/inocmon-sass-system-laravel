@extends('layouts.admin')
@section('content')
<div class="column">
    <div class="row">
        <div class="col-12">
            {{-- {{dd($dashboardData)}} --}}
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
                    <a href="">ver detalhes</a>
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
                    <a href="{{route("mpls_pe.index", array('client_id' => $clientId))}}">ver detalhes</a>
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
                            <h3 class="text-dark my-1"><span data-plugin="counterup">Database em uso</span></h3>
                            <p class="text-muted mb-1 text-truncate">{{$dashboardData['databaseInuse']}}KBytes</p>
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
                <p class="text-muted mb-0 text-center">
                    <a href="">ver detalhes</a>
                </p>
            </div> <!-- end card-box-->
        </div> <!-- end col -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box ribbon-box">
                <div class="ribbon ribbon-success float-left"><i class="mdi mdi-access-point mr-1"></i> Rede MPLS</div>
                <div class="ribbon-content" style="text-align: center">
                    <h4 class="text-success  mt-0">{{$dashboardData['equipmentCount']}} PEs cadastrados</h4>
                    <h5 class="mt-3 text-dark"> <span> Arquivo lsdb </span></h5>
                    @if (!$dashboardData['ospData'])
                        <h5 class="text-muted p-1"> <span> Ainda não existe um arquivo! </span></h5>
                    @else
                        <a href="storage/configuracoes/ospf-lsdb-{{$dashboardData['dspVendor'].'-'.$clientId}}"> download {{$dashboardData['dspVendor']}}</a>
                        <br>
                        Abrir <a href="https://topolograph.com/upload-ospf-isis-lsdb" target="_blank">topolograph</a>
                        <br><hr>
                        Sobreescrever existente:
                    @endif
                    <div class="col-12 mt-2">
                        <form style="display: flex; width : max-content ; margin : auto; gap : 10px" action="{{ route('dashboard.executeSshCommand', array('client_id' => $clientId)) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <select name="targetproxyid" class="form-control" data-toggle="select2" >
                                @foreach ($dashboardData['sondas'] as $index => $value)
                                    <option value="{{$index}}">{{$value['hostname']}}</option>
                                @endforeach
                            </select>
                            <select name="targetrrid" class="form-control" data-toggle="select2" >
                                @foreach ($dashboardData['rr'] as $buscaIndex => $buscarVal)
                                    @if ($buscarVal != null)
                                        <option value="{{$buscaIndex}}">{{$buscarVal['hostname']}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-info dropdown-toggle">Gerar Lsdb</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


