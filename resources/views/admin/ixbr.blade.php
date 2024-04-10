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
                            <li class="breadcrumb-item active">Casatrar novo IX no banco de dados</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Casatrar novo IX no banco de dados</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12" id="operator-panel">
            <div class="card-box p-1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">SIGLA:</label>
                            <input type="text" name="" id="sigla" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Cidade-UF</label>
                            <input type="text" name="" id="cidade" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">RS1 IPV4</label>
                            <input type="text" name="" id="rs1" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">RS2 IPV4</label>
                            <input type="text" name="" id="rs2" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">LG IPV4</label>
                            <input type="text" name="" id="lg4" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">RS1 IPV6</label>
                            <input type="text" name="" id="rs16" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">RS2 IPV6</label>
                            <input type="text" name="" id="rs26" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">LG IPV6</label>
                            <input type="text" name="" id="lg6" class="form-control mb-1"/>
                            <button class="mt-3 btn btn-primary ml-2" onclick="saveData()" type="submit">Cadastrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
                @if (is_array($buscaLibIxbr))
                    @foreach ( $buscaLibIxbr as $index => $val )
                        <table class="table nowrap">
                            <tr class="text-blue font-15 text-center" style="background-color: yellow">
                                <td colspan="2">
                                    {{$val['cidade']}}
                                </td>
                            </tr>
                            <tr class="font-14">
                                <td>'SIGLA: {{$index}}</td>
                                <td>ASN RS {{$val['remoteas']}} LG : {{$val['lgremoteas']}}</td>
                            </tr>
                            <tr class="font-14">
                                <td>RS2 ipv4</td>
                                <td>{{$val['rs1v4']}}</td>
                            </tr>
                            <tr class="font-14">
                                <td>RS2 ipv4</td>
                                <td>{{$val['rs2v4']}}</td>
                            </tr>
                            <tr class="font-14">
                                <td>Looking Glass ipv4</td>
                                <td>{{$val['lgv4']}}</td>
                            </tr>
                            <tr class="font-14">
                                <td>RS1 ipv6</td>
                                <td>{{$val['rs1v6']}}</td>
                            </tr>
                            <tr class="font-14">
                                <td>RS2 ipv6</td>
                                <td>{{$val['rs2v6']}}</td>
                            </tr>
                            <tr class="font-14">
                                <td>Looking Glass ipv6</td>
                                <td>{{$val['lgv6']}}</td>
                            </tr>
                        </table>
                    @endforeach
                @endif

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

    <script>

        function saveData(){
            var sigla = $("#sigla").val();
            var cidade = $("#cidade").val();
            var rs1 = $("#rs1").val();
            var rs2 = $("#rs2").val();
            var lg4 = $("#lg4").val();
            var rs16 = $("#rs16").val();
            var rs26 = $("#rs26").val();
            var lg6 = $("#lg6").val();

            if( sigla == ""  || cidade == "" || rs1 == "" || rs2 == "" || lg4 == "" || rs16 == "" || rs26 == "" || lg6 == "" ) {
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
                url: '{{ route("ixbr.store") }}',
                data: {
                    sigla : sigla,
                    cidade : cidade,
                    rs1 : rs1,
                    rs2 : rs2,
                    lg4 : lg4,
                    rs16 : rs16,
                    rs26 : rs26,
                    lg6 : lg6,
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    $("#sigla").val("");
                    $("#cidade").val("");
                    $("#rs1").val("");
                    $("#rs2").val("");
                    $("#lg4").val("");
                    $("#rs16").val("");
                    $("#rs26").val("");
                    $("#lg6").val("");
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully added!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                    setTimeout(() => {
                        location.reload();
                    }, 2500);
                } else {
                    $.NotificationApp.send("Alarm!"
                        ,"The operation faield!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('div.card-box');
            });
        }

    </script>

@endsection