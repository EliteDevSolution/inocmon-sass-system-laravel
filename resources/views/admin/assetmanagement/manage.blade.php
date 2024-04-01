@extends('layouts.admin')

@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
@endsection

@section('content')

    <div class="columns">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Asset management</li>
                            <li class="breadcrumb-item active">Router Reflectors</li>
                            <li class="breadcrumb-item active">PR sumary</li>
                        </ol>
                    </div>
                    <h4 class="page-title">PR sumary</h4>
        			<span class="">Lets say you want to sort the fourth column (3) descending and the first column (0) ascending: your order: would look like this: order: [[ 3, 'desc' ], [ 0, 'asc' ]]</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mt-1">
                <div class="card-body">
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>HostName</th>
                                <th>RouterId</th>
                                <th>Vendor</th>
                                <th>Family</th>
                                <th>Protocolo</th>
                                <th>User</th>
                                <th>Senha</th>
                                <th>Gerencia</th>
                                <th>Editar</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buscarRr as $index => $value)
                                @if ($value != null)
                                    <tr>
                                        <td>{{$index}}</td>
                                        <td>{{$value['hostname']}}</td>
                                        <td>{{$value['routerid']}}</td>
                                        <td>{{$value['template-vendor']}}</td>
                                        <td>{{$value['template-family']}}</td>
                                        <td>{{$value['protocolo']}}</td>
                                        <td>{{$value['porta']}}</td>
                                        <td>{{$value['user']}}</td>
                                        <td>
                                            <a href={{ route("proxy-localhost.index",array('client_id' =>
                                        request()->query()['client_id'], 'proxy_id' => $index ) ) }}>gerenciar config</a>
                                        </td>
                                        <td>
                                            <a href="gerenciar-config-rr.php?clienteid={{$clientId}}.'&rrid='{{$index}}.'">editar</a>
                                        </td>
                                        <td>
                                            <a href="gerenciar-config-rr.php?clienteid={{$clientId}}.'&rrid='{{$index}}.'">delete</a>
                                        </td>
                                    </tr>
                                @endif
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
    <!-- Datatables init -->
    <script>
        $(document).ready(function(){
            $('#datatable').DataTable({
                responsive: false,
                stateSave: true,
                stateDuration: 60 * 60 * 24 * 60 * 60,
                autoWidth: false,
                scrollCollapse: true,
                scrollX: true,
                bProcessing: true,
                lengthMenu: [
                    [ 10, 50, 100, 500],
                    [ '10', '50', '100', '500' ]
                ],
                columnDefs: [
                    { "width": "10%", "targets": 1 }
                ],
                pageLength: 50,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only"></span>',
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                order: [[ 0, "asc" ]]
            });


        });
    </script>

@endsection