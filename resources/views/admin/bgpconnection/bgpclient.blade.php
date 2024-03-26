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
                            <li class="breadcrumb-item active">Conexao BGP</li>
                            <li class="breadcrumb-item active">Novo BGP client</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Novo BGP Client</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <h2 class="header-title text-blue text-center">Novo cdn</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">ID do cdn</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="02" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Nome cdn</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="Nome transito" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">PoP de acesso</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="PoP de accesso" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">ASN do cdn</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="ASN do transito" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 remoto gbp 01</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="IPV4 remoto gbp 01" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 remoto gbp 02</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="IPV4 remoto gbp 01" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">IPV6 remoto gbp 01</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="IPV6 remoto gbp 01" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV6 remoto gbp 02</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="IPV6 remoto gbp 02" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Equipamento PE</label>
                            <select class="form-control" data-toggle="select2">
                                <option>Selecione o PE</option>
                                <option>SW3-PE-SIS-DTCL-01</option>
                            </select>
                        </div>
                        <div class="row ml-2 mt-2">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox form-check">
                                    <input type="checkbox" class="custom-control-input" id="invalidCheck" required>
                                    <label class="custom-control-label" for="invalidCheck">Bloquear clientes de trânsito</label>
                                    <div class="invalid-feedback">
                                        You must agree before submitting.
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary ml-2 " type="submit">Cadastrar</button>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table nowrap">
                        <thead>
                        <tr>
                            <th>
                                NOME DO GRUPO
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
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pages/datatables.init.js') }}"></script>

    <script>
        $(document).ready(function(){
            $('[data-toggle="select2"]').select2()
        });
    </script>

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