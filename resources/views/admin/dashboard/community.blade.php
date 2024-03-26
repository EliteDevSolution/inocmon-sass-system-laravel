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

        <div>
            <div class="col-xl-12">
                <div class="card-box">
                    <h4 class="header-title mb-4 text-success  mt-0">COMMUNITIES DE EFEITO GLOBAL</h4>

                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#global" data-toggle="tab" aria-expanded="false" class="nav-link">
                              GLOBAL
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#transtors" data-toggle="tab" aria-expanded="true" class="nav-link active">
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
                        <div class="tab-pane" id="global">
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
                                        <th>1234:999</th>
                                    </tr>
                                    <tr>
                                        <th>NO-EXPORT-GLOBAL</th>
                                        <th>(no-export))</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane show active" id="transtors">
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
                                        <th>1234:911</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-PADRAO-TRANSIT</th>
                                        <th>1234-911</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-2X-PADRAO-TRANSIT</th>
                                        <th>1234-912</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-3X-PADRAO-TRANSIT</th>
                                        <th>1234-913</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-4X-PADRAO-TRANSIT</th>
                                        <th>1234-914</th>
                                    </tr>
                                    <tr>
                                        <th>EXPORT-5X-PADRAO-TRANSIT</th>
                                        <th>1234-915</th>
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
                                    <th>1234:911</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-PADRAO-IX</th>
                                    <th>1234-920</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-2X-PADRAO-IX</th>
                                    <th>1234-921</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-3X-PADRAO-IX</th>
                                    <th>1234-922</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-4X-PADRAO-IX</th>
                                    <th>1234-923</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-5X-PADRAO-IX</th>
                                    <th>1234-924</th>
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
                                    <th>1234:930</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-PADRAO-PEERING</th>
                                    <th>1234-931</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-2X-PADRAO-PEERING</th>
                                    <th>1234-932</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-3X-PADRAO-PEERING</th>
                                    <th>1234-933</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-4X-PADRAO-PEERING</th>
                                    <th>1234-934</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-5X-PADRAO-PEERING</th>
                                    <th>1234-935</th>
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
                                    <th>1234:940</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-PADRAO-CDN</th>
                                    <th>1234-941</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-2X-PREPEND-ALL-CDN</th>
                                    <th>1234-942</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-3X-PREPEND-ALL-CDN</th>
                                    <th>1234-943</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-4X-PREPEND-ALL-CDN</th>
                                    <th>1234-944</th>
                                </tr>
                                <tr>
                                    <th>EXPORT-5X-PREPEND-ALL-CDN</th>
                                    <th>1234-945</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- end card-box-->
            </div> <!-- end col -->
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-4 text-success  mt-0">TRANSITOS</h4>

                    <table id="datatable" class="table nowrap">
                        <thead>
                        <tr>
                            <th width=40>
                                ID
                            </th>
                            <th>
                                Hostname
                            </th>
                            <th>
                                RouterID
                            </th>
                            <th>
                                Vendor
                            </th>
                            <th>
                                Family
                            </th>
                            <th>
                                &nbsp;Protocolo
                            </th>
                            <th>
                                &nbsp;Porta
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title mb-4 text-success  mt-0">IX</h4>

                    <table id="datatable" class="table nowrap">
                        <thead>
                        <tr>
                            <th width=40>
                                IX
                            </th>
                            <th>
                                ASN
                            </th>
                            <th>
                                POP
                            </th>
                            <th>
                                PE
                            </th>
                            <th>
                                Family
                            </th>
                            <th>
                                &nbsp;ID
                            </th>
                            <th>
                                &nbsp;COMMUNITIES
                            </th>
                        </tr>
                        </thead>
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
