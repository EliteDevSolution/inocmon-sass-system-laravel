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
                            <li class="breadcrumb-item active">Client</li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Clients Create</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <form action="{{ route("client.store") }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Nome do Client</label>
                                <input type="text" id ="nome" name="nome" value="{{old('nome')}}" required class="form-control mb-1"   style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">ASN</label>
                                <input type="text" id="asn" name="asn" value="{{old('asn')}}" class="form-control mb-1" required placeholder="Asn" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weisght-bold text-muted">Email</label>
                                <input type="text" name="email" id="email"  value="{{old('email')}}" class="form-control mb-1" required placeholder="Email" style=" z-index: 2; background: transparent;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Community</label>
                                <input type="text" name="community0" id="community0" value="{{old('community0')}}" required class="form-control mb-1"  placeholder="Community" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Ipv4bgpmultihop</label>
                                <input type="text" name="ipv4bgpmultihop" id="ipv4bgpmultihop" required value="{{old('ipv4bgpmultihop')}}" class="form-control mb-1"  placeholder="Ipv4 remoto bgp 01" required style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Ipv6bgpmultihop</label>
                                <input type="text" name="ipv6bgpmultihop" id="ipv6bgpmultihop" required value="{{old('ipv6bgpmultihop')}}" class="form-control mb-1"  placeholder="Ipv6bgpmultihop" style=" z-index: 2; background: transparent;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Userinocmon</label>
                                <input type="text" name="userinocmon" id="userinocmon" required value="{{old('userinocmon')}}" class="form-control mb-1"  placeholder="Userinocmon" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Senha inocmon</label>
                                <input type="text" name="senhainocmon" id="senhainocmon" required value="{{old('senhainocmon')}}" class="form-control mb-1"  placeholder="inocmon" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Senha inocmon Crptografada</label>
                                <input type="text" name="senhainocmoncifrada" id="senhainocmoncifrada" required value="{{old('senhainocmoncifrada')}}" class="form-control mb-1"  placeholder="Senha inocmon Crptografada" style=" z-index: 2; background: transparent;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Community SNMP</label>
                                <input type="text" name="snmpcommunity" id="snmpcommunity" required value="{{old('snmpcommunity')}}" class="form-control mb-1"  placeholder="Community SNMP:	" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Nome do Grupo :	</label>
                                <input type="text" name="nomedogrupo" id="nomedogrupo" required value="{{old('nomedogrupo')}}" class="form-control mb-1"  placeholder="Nome do group" style=" z-index: 2; background: transparent;"/>
                                <label class="mb-1 font-weight-bold text-muted">Stauts</label>
                                <select  name="status" value="{{old('status')}}" class="form-control" data-toggle="select2" >
                                    <option value="activo">activo</option>
                                    <option value="inactivo">inactivo</option>
                                </select>
                            </div>
                            <button class="btn btn-primary ml-2 mt-1" type="submit">atualizar</button>
                            <a class="btn btn-primary ml-2 mt-1" href="{{route("client.index")}}" style="color : white" type="submit">go back</a>
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


        });
    </script>

@endsection