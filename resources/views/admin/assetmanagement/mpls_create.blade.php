@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
                            <li class="breadcrumb-item active">MPLS</li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">MPLS Create</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <form action="{{ route("mpls_pe.store", array('client_id' => $clientId)) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Hostname</label>
                                <input type="text" id ="hostname" name="hostname" value="{{old('hostname')}}" required class="form-control mb-1"   style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">RouterId</label>
                                <input type="text" id="routerId" name="routerId" value="{{old('routerId')}}" class="form-control mb-1" required placeholder="Asn" style=" z-index: 2; background: transparent;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Vendor</label>
                                <input type="text" name="vendor" id="vendor" value="{{old('vendor')}}" required class="form-control mb-1"  placeholder="Community" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Family</label>
                                <input type="text" name="family" id="family" required value="{{old('family')}}" class="form-control mb-1"  placeholder="Ipv4 remoto bgp 01" required style=" z-index: 2; background: transparent;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Protocolo</label>
                                <input type="text" name="protocolo" id="protocolo" required value="{{old('protocolo')}}" class="form-control mb-1"  placeholder="Userinocmon" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Porta</label>
                                <input type="text" name="porta" id="porta" required value="{{old('porta')}}" class="form-control mb-1"  placeholder="inocmon" style=" z-index: 2; background: transparent;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">User</label>
                                <input type="text" name="user" id="user" required value="{{old('user')}}" class="form-control mb-1"  placeholder="Community SNMP:	" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Senha</label>
                                <input type="text" name="senha" id="senha" required value="{{old('senha')}}" class="form-control mb-1"  placeholder="Nome do group" style=" z-index: 2; background: transparent;"/>
                            </div>
                            <button class="btn btn-primary ml-2 mt-1" type="submit">atualizar</button>
                            <a class="btn btn-primary ml-2 mt-1" href="{{route("mpls_pe.index", array('client_id' => $clientId))}}" style="color : white" type="submit">go back</a>
                        </div>
                    </form>
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

            "@if(session('success') == 'success')"
                $.NotificationApp.send("Alert!"
                    ,"Successfully created!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
            "@elseif (session('success') == 'failed')"
                $.NotificationApp.send("Alert!"
                    ,"Failed created!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
            "@endif"
        });
    </script>

@endsection