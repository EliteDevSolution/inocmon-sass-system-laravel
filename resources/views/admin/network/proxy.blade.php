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
                            <li class="breadcrumb-item active">Novo Proxy</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Novo Proxy</h4>
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
                            <input type="text" name="hostname" id="hostname" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">POP</label>
                            <input type="text" name="pop" id="pop" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPv4</label>
                            <input type="text" name="ipv4" id="ipv4" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">IPv6</label>
                            <input type="text" name="ipv6" id="ipv6" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Porta Ssh</label>
                            <input type="text" name="ssh" id="ssh" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Porta HTTP</label>
                            <input type="text" name="http" id="http" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">usuario</label>
                            <input type="text" name="user" id="user" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">senha</label>
                            <input type="text" name="senha" id="senha" class="form-control mb-1"/>
                            <button class="mt-3 btn btn-primary ml-2" onclick="saveData()" type="submit">Cadastrar</button>
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
    <script src="{{ asset('admin_assets/js/pages/datatables.init.js') }}"></script>


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

    <script>
        function saveData(){

            $.ajax({
                type: "POST",
                url: '{{ route("network-proxy.store") }}',
                data: {
                    hostname : $("#hostname").val(),
                    pop : $("#pop").val(),
                    ipv4 : $("#ipv4").val(),
                    ipv6 : $("#ipv6").val(),
                    ssh : $("#ssh").val(),
                    http : $("#http").val(),
                    user : $("#user").val(),
                    senha : $("#senha").val(),
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    $("#hostname").val("");
                    $("#ipv4").val("");
                    $("#ipv6").val("");
                    $("#ssh").val("");
                    $("#http").val("");
                    $("#porta").val("");
                    $("#user").val("");
                    $("#senha").val("");
                }
            });
        }
    </script>
@endsection