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
                            <li class="breadcrumb-item active">Novo PE</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Novo PE</h4>
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
                            <label class="mb-1 font-weight-bold text-muted">RouterID</label>
                            <input type="text" name="routerid" id="routerid" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Group IBGP</label>
                            <select id="ibgps" class="form-control" >
                                <option>IBGP-PARCIAL</option>
                                <option>IBGP-FUll</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Template Vendor</label>
                            <select id="vendor" class="form-control mb-1" >
                                @foreach ($buscaTemplates as $indexTemp => $indexVal)
                                    <option value="{{$indexTemp}}">
                                        {{$indexTemp}}
                                    </option>
                                @endforeach
                            </select>
                            <label class="mb-1 font-weight-bold text-muted">Template Family</label>
                            <select id="family" class="form-control mb-1" >
                                @foreach ($buscaTemplates as $indexTemp => $indexVal)
                                    @foreach ( $indexVal as $vendorIndex => $vendorVal)
                                        <option value="{{$vendorIndex}}">
                                            {{$vendorIndex}}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            <label class="mb-1 font-weight-bold text-muted">Protocol</label>
                            <input type="text" name="protocol" id="protocol" class="form-control mb-1" placeholder="Protocol" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Porta</label>
                            <input type="text" name="porta" id="porta" class="form-control mb-1" placeholder="Porta" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">User</label>
                            <input type="text" name="user" id="user" class="form-control mb-1" placeholder="User" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Senha</label>
                            <input type="text" name="senha" id="senha" class="form-control mb-1" placeholder="Senha" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="row ml-2 mt-2">
                            <button class="btn btn-primary ml-2" onclick="saveData()" type="submit">Cadastrar</button>
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
            if( $("#hostname").val()  == "" || $("#routerid").val()  == "" || $("#ibgp").val()  == "" ||
                $("#vendor").val()  == "" || $("#family").val()  == "" || $("#protocol").val()  == "" ||
                $("#porta").val()  == "" || $("#user").val()  == "" || $("#senha").val()  == "" ) {
                    $.NotificationApp.send("Alarm!"
                        ,"This is required field!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                return;
            }
            elementBlock('square1', 'div.card-box');
            $.ajax({
                type: "POST",
                url: '{{ route("network-pe.store") }}',
                data: {
                    hostname : $("#hostname").val(),
                    routerid : $("#routerid").val(),
                    ibgp : $("#ibgp").val(),
                    vendor : $("#vendor").val(),
                    family : $("#family").val(),
                    protocol : $("#protocol").val(),
                    porta : $("#porta").val(),
                    user : $("#user").val(),
                    senha : $("#senha").val(),
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    $("#hostname").val("");
                    $("#routerid").val("");
                    $("#ibgp").val("");
                    $("#vendor").val("");
                    $("#family").val("");
                    $("#protocol").val("");
                    $("#porta").val("");
                    $("#user").val("");
                    $("#senha").val("");
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully added!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                } else {
                    $.NotificationApp.send("Alarm!"
                        ,"Faield"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('div.card-box');
            }).fail(function(xhr, textStatus, errorThrown) {
                $.NotificationApp.send("Alarm!"
                    ,"Failed updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                elementUnBlock('div.card-box'');
            });
        }
    </script>

@endsection