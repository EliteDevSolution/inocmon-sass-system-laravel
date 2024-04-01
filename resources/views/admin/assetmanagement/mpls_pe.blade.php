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
                            <li class="breadcrumb-item active">MPLS PE's and P's</li>
                        </ol>
                    </div>
                    <h4 class="page-title">MPLS PE's and P's</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-success mt-0 mb-2">P's e PEs  <span class="badge badge-success ml-2">Adicionar Novo</span></h4>
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th>
                                    Id
                                </th>
                                <th>
                                    Hostname
                                </th>
                                <th>
                                    RouterId
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
                                    &nbsp;User
                                </th>
                                <th>
                                    Senha
                                </th>
                                <th>
                                    &nbsp;Gerenciar
                                </th>
                                <th>
                                    &nbsp;Editar
                                </th>
                                <th>
                                    &nbsp;Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equipments as $index => $value)
                                <tr>
                                    <td>{{$index}}</td>
                                    <td>{{$value['hostname']}}</td>
                                    <td>{{$value['routerid']}}</td>
                                    <td>{{$value['template-vendor']}}</td>
                                    <td>{{$value['template-family']}}</td>
                                    <td>{{$value['protocolo']}}</td>
                                    <td>{{$value['user']}}</td>
                                    <td>{{$value['senha']}}</td>

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