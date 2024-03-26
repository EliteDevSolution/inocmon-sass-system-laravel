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
                            <li class="breadcrumb-item active">ACN Clients</li>
                        </ol>
                    </div>
                    <h4 class="page-title">ACN Clients</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Detalhes do cliente</p>
                    <p class="mb-0">ID : Ns0BiNZb34Avpo-6TD0</p>
                    <p class="mb-0">Nome:teste</p>
                    <p class="mb-0">Senha Inocomon</p>
                    <p class="mb-0">ASN : 1234</p>
                    <p class="mb-0">Nome do grupo : teste</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-left text-blue mb-2">Deafault Ordering</h4>
                    <table id="datatable" class="table nowrap">
                        <thead>
                        <tr>
                            <th width=50>
                                Id
                            </th>
                            <th>
                                Nome do cliente
                            </th>
                            <th>
                                POP
                            </th>
                            <th>
                                PE
                            </th>
                            <th>
                                IPV4 LOCAL
                            </th>
                            <th>
                                &nbsp;IPV4 REMOTO
                            </th>
                            <th>
                                &nbsp;IPV6 LOCAL
                            </th>
                            <th>
                                &nbsp;IPV6 REMOTO
                            </th>
                            <th>
                                &nbsp;Gerenciar
                            </th>
                            <th>
                                &nbsp;Edit
                            </th>
                            <th>
                                &nbsp;Delete
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