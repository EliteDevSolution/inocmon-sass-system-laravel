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
                            <li class="breadcrumb-item active">Novo Ix</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Novo Ix</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p>IX cadastrados no momento:</p>
                <p>IX-01-CE-123</p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card-box p-1">
            <div class="card-body">
                <h2 class="header-title text-blue text-center">Novo ix</h2>
                <div class="row">
                    <div class="col-md-4">
                        <label class="mb-1 font-weight-bold text-muted">ID do IX</label>
                        <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="02" style=" z-index: 2; background: transparent;"/>
                        <label class="mb-1 font-weight-bold text-muted">Nome do IXBR</label>
                        <select class="form-control" data-toggle="select2">
                            <option>Selecione a localidade</option>
                            <option>Salvador-BA</option>
                            <option>Fortaleza-CE</option>
                            <option>Campina Grande-PB</option>
                            <option>Brasília-DF</option>
                            <option>Recife-PE</option>
                            <option>Curitiba-PR</option>
                            <option>Rio de Janeiro-RJ</option>
                            <option>Porto Alegre-RS</option>
                            <option>Florianópolis-SC</option>
                            <option>São Paulo-SP</option>
                        </select>

                    </div>
                    <div class="col-md-4">
                        <label class="mb-1 font-weight-bold text-muted">POP onde chega o acesso</label>
                        <input type="text" name="country" id="autocomplete-ajax" class="form-control mb-1" placeholder="ASN do transito" style=" z-index: 2; background: transparent;"/>
                        <label class="mb-1 font-weight-bold text-muted">Equip onde chega o acesso</label>
                        <select class="form-control" data-toggle="select2">
                            <option>Selecione a localidade</option>
                            <option>Salvador-BA</option>
                            <option>Fortaleza-CE</option>
                            <option>Campina Grande-PB</option>
                            <option>Brasília-DF</option>
                            <option>Recife-PE</option>
                            <option>Curitiba-PR</option>
                            <option>Rio de Janeiro-RJ</option>
                            <option>Porto Alegre-RS</option>
                            <option>Florianópolis-SC</option>
                            <option>São Paulo-SP</option>
                        </select>
                    </div>
                    <div class="col-md-1 ml-3 mt-3">
                        <button class="btn btn-primary ml-2 " type="submit">Cadastrar</button>
                    </div>
                </div>
            </div> <!-- end row -->
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