@extends('layouts.admin')

@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
@endsection


@section('content')

    <div class="column">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Dashbaords</li>
                            <li class="breadcrumb-item active">Communities BGP</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Communities BGP</h4>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header bg-blue py-3 text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#communities" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h5 class="card-title mb-0 text-white">COMMUNITIES DE EFEITO GLOBAL</h5>
                </div>
                <div id="communities" class="collapse show">
                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#global" data-toggle="tab" aria-expanded="false" class="nav-link active">
                              GLOBAL
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#transtors" data-toggle="tab" aria-expanded="true" class="nav-link">
                                TRANSITOS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#ix" data-toggle="tab" aria-expanded="false" class="nav-link">
                                IX
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#peering" data-toggle="tab" aria-expanded="false" class="nav-link">
                                PEERING
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#cdn" data-toggle="tab" aria-expanded="false" class="nav-link">
                                CDN
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="global">
                            <table id="datatable" class="table nowrap">
                                <thead>
                                    <tr>
                                        <th width=400>
                                            EFEITO PARA CDN
                                        </th>
                                        <th>
                                            COMMUNITY
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>EXPORT-GLOBAL</th>
                                        <th>{{$toSendData["community"]}}:999</th>
                                    </tr>
                                    <tr>
                                        <th>NO-EXPORT-GLOBAL</th>
                                        <th>(no-export)</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="transtors">
                            <table id="datatable" class="table nowrap">
                                <thead>
                                    <tr>
                                        <th width=400>
                                            EFEITO PARA OS TRANSITOS
                                        </th>
                                        <th>
                                            COMMUNITY
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>NO-EXPORT-ALL-TRANSIT</th>
                                        <th>{{$toSendData["community"]}}:911</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-PADRAO-TRANSIT</th>
                                        <th>{{$toSendData["community"]}}-911</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-2X-PADRAO-TRANSIT</th>
                                        <th>{{$toSendData["community"]}}-912</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-3X-PADRAO-TRANSIT</th>
                                        <th>{{$toSendData["community"]}}-913</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-4X-PADRAO-TRANSIT</th>
                                        <th>{{$toSendData["community"]}}-914</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-5X-PADRAO-TRANSIT</th>
                                        <th>{{$toSendData["community"]}}-915</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="ix">
                            <table id="datatable" class="table nowrap">
                                <thead>
                                <tr>
                                    <th width=400>
                                        EFEITO PARA OS IX
                                    </th>
                                    <th>
                                        COMMUNITY
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>NO-EXPORT-ALL-IX</th>
                                    <th>{{$toSendData["community"]}}:911</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-PADRAO-IX</th>
                                    <th>{{$toSendData["community"]}}-920</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-2X-PADRAO-IX</th>
                                    <th>{{$toSendData["community"]}}-921</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-3X-PADRAO-IX</th>
                                    <th>{{$toSendData["community"]}}-922</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-4X-PADRAO-IX</th>
                                    <th>{{$toSendData["community"]}}-923</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-5X-PADRAO-IX</th>
                                    <th>{{$toSendData["community"]}}-924</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="peering">
                            <table id="datatable" class="table nowrap">
                                <thead>
                                <tr>
                                    <th width=400>
                                        EFEITO PARA OS PEERING
                                    </th>
                                    <th>
                                        COMMUNITY
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>NO-EXPORT-ALL-PEERING</th>
                                    <th>{{$toSendData["community"]}}:930</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-PADRAO-PEERING</th>
                                    <th>{{$toSendData["community"]}}-931</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-2X-PADRAO-PEERING</th>
                                    <th>{{$toSendData["community"]}}-932</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-3X-PADRAO-PEERING</th>
                                    <th>{{$toSendData["community"]}}-933</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-4X-PADRAO-PEERING</th>
                                    <th>{{$toSendData["community"]}}-934</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-5X-PADRAO-PEERING</th>
                                    <th>{{$toSendData["community"]}}-935</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="cdn">
                            <table id="datatable" class="table nowrap">
                                <thead>
                                <tr>
                                    <th width=400>
                                        EFEITO PARA OS CDN
                                    </th>
                                    <th>
                                        COMMUNITY
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>NO-EXPORT-ALL-CDN</th>
                                    <th>{{$toSendData["community"]}}:940</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-PADRAO-CDN</th>
                                    <th>{{$toSendData["community"]}}-941</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-2X-PREPEND-ALL-CDN</th>
                                    <th>{{$toSendData["community"]}}-942</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-3X-PREPEND-ALL-CDN</th>
                                    <th>{{$toSendData["community"]}}-943</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-4X-PREPEND-ALL-CDN</th>
                                    <th>{{$toSendData["community"]}}-944</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-5X-PREPEND-ALL-CDN</th>
                                    <th>{{$toSendData["community"]}}-945</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header bg-blue py-3 text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#transitos" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h5 class="card-title mb-0 text-white">TRANSITOS</h5>
                </div>
                <div id="transitos" class="collapse show">
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th width="10%">
                                    IX
                                </th>
                                <th width="10%">
                                    ASN
                                </th>
                                <th width="20%">
                                    POP
                                </th>
                                <th width="20%">
                                    PE
                                </th>
                                <th width="20%">
                                    ID
                                </th>
                                <th width="20%">
                                    COMMUNITIES
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $toSendData['transito'] as $index => $value)
                                <tr>
                                    <td>{{$value['provedor']}}</td>
                                    <td>{{$value['remoteas']}}</td>
                                    <td>{{$value['pop']}}</td>
                                    <td>
                                        <a href="{{ route('mpls-detail.index', array('client_id' => $toSendData['client_id'], 'equip_id' => $value['peid'] ) ) }}">
                                            {{$toSendData['equipment'][$value['peid']]['hostname']}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$index}}
                                    </td>
                                    <td>
                                        <details>
                                            <summary>
                                                Comminities
                                            </summary>
                                            @foreach ($value['communities'] as $y => $z )
                                                <i class="fas fa-caret-down m-r-10 f-18 text-c-red"></i>
                                                {{substr($y, 0 , 10).':'.$z}}
                                                <p></p>
                                            @endforeach
                                        </details>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header bg-blue py-3 text-white">
                    <div class="card-widgets">
                        <a data-toggle="collapse" href="#ixtab" role="button" aria-expanded="false" aria-controls="cardCollpase2"><i class="mdi mdi-minus"></i></a>
                    </div>
                    <h5 class="card-title mb-0 text-white">IX</h5>
                </div>
                <div id="ixtab" class="collapse show">
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th width="10%">
                                    IX
                                </th>
                                <th width="10%">
                                    ASN
                                </th>
                                <th width="20%">
                                    POP
                                </th>
                                <th width="20%">
                                    PE
                                </th>
                                <th width="20%">
                                    ID
                                </th>
                                <th width="20%">
                                    COMMUNITIES
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($toSendData['ix'] as $index => $value )
                                <tr>
                                    <td>
                                        {{$value['sigla']}}
                                    </td>
                                    <td>
                                        {{$value['remoteas']}}
                                    </td>
                                    <td>
                                        {{$value['pop']}}
                                    </td>
                                    <td>
                                        <a href="{{ route('mpls-detail.index', array('client_id' => $toSendData['client_id'], 'equip_id' => $value['peid'] ) ) }}">
                                            {{$toSendData['equipment'][$value['peid']]['hostname']}}
                                        </a>
                                    </td>
                                    <td>
                                        {{$index}}
                                    </td>
                                    <td>
                                        <details>
                                            <summary>
                                                Comminities
                                            </summary>
                                            @foreach ($value['communities'] as $y => $z )
                                                <i class="fas fa-caret-down m-r-10 f-18 text-c-red"></i>
                                                {{substr($y, 0 , 10).':'.$z}}
                                                <p></p>
                                            @endforeach
                                        </details>
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection


@section('scripts')
    @parent
    <!-- third party js -->
    <script src="{{ asset('admin_assets/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <!-- third party js ends -->
@endsection
