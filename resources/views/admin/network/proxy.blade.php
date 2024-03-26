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
                            <li class="breadcrumb-item active">Network assets</li>
                            <li class="breadcrumb-item active">Proxy</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Proxy</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Hostname</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="02" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">POP</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="POP" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">IPv4</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="IPv4" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">IPv6</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="IPv6" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Porta SSH</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="Pota SSH" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Porta HTTP</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="Porta HTTP" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">usuario</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="usuario" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">senha</label>
                            <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="Porta" style=" z-index: 2; background: transparent;"/>
                            <button class="mt-3 btn btn-primary ml-2 " type="submit">Cadastrar</button>
                        </div>
                    </div>
                </div> <!-- end row -->
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