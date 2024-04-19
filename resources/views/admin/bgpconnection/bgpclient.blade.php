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
                            <label class="mb-1 font-weight-bold text-muted" >Nome do Client</label>
                            <input type="text" name="nome" id="nome" class="form-control mb-1" maxlength="14"/>
                            <label class="mb-1 font-weight-bold text-muted">ASN do cliente</label>
                            <input type="text" name="asn" id="asn" class="form-control mb-1" />
                            <label class="mb-1 font-weight-bold text-muted">POP DO ACESSO</label>
                            <input type="text" name="pop" id="pop" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">blocos-ipv4</label>
                            <input type="text" name="blocosipv4" id="blocosipv4" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">blocos-ipv6</label>
                            <input type="text" name="blocosipv6" id="blocosipv6" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 lado provedor</label>
                            <input type="text" name="ipv4pro" id="ipv4pro" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 lado cliente</label>
                            <input type="text" name="ipv4client" id="ipv4client" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV6 lado provedor</label>
                            <input type="text" name="ipv6pro" id="ipv6pro" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">IPV6 lado cliente</label>
                            <input type="text" name="ipv6client" id="ipv6client" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">ASN de clientes recursivos</label>
                            <input type="text" name="recursivos" id="recursivos" class="form-control mb-1" placeholder="IPV6 remoto gbp 01" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Equipamento PE</label>
                            <select class="form-control" id="equip" data-toggle="select2">
                                @if(is_array($buscaEquipamentos))
                                    @foreach ( $buscaEquipamentos as $equipIndex => $equipVal )
                                        <option value="{{$equipIndex}}">{{$equipVal['hostname'] ?? ''}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button class="btn btn-primary mt-4 " type="" onclick="saveData()">Cadastrar</button>
                            <button class="btn btn-blue mt-4" type="" onclick="goBack()">Volt</button>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="accordion">
                        <div class="card mb-1">
                            <div class="card-header" id="headingOne">
                                <h5 class="m-0">
                                    <a class="text-dark" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
                                        <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                        Mostrar/ocultar modo avançado
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <div id="accordion1">
                                        <div class="card mb-1">
                                            <div class="card-header" id="headingTwo">
                                                <h5 class="m-0">
                                                    <a class="text-dark" data-toggle="collapse" href="#collapseTwo" aria-expanded="true">
                                                        <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                                        Mostrar/ocultar communities globaix
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseTwo" class="collapse hide" aria-labelledby="headingTwo" data-parent="#accordion1">
                                                <div class="card-body">
                                                    <ul>
                                                        <div class="p-1">
                                                            <input type="checkbox" checked id="global" value="{{$community}}:999"/> EXPORT-GLOBAL ({{$community}}:999)
                                                        </div>
                                                        <div class="p-1">
                                                            <input type="checkbox" id="transito" value="{{$community}}:910"/> NO-EXPORT-ALL-TRANSIT ({{$community}}:910)
                                                        </div>
                                                        <div class="p-1">
                                                            <input type="checkbox" id="ix" value="{{$community}}:920"/> NO-EXPORT-ALL-IX ({{$community}}:920)
                                                        </div>
                                                        <div class="p-1">
                                                            <input type="checkbox" id="peering" value="{{$community}}:930"/> NO-EXPORT-ALL-PEERING ({{$community}}:930)
                                                        </div>
                                                        <div class="p-1">
                                                            <input type="checkbox" id="cdn" value="{{$community}}:940"/> NO-EXPORT-ALL-CDN ({{$community}}:940)
                                                        </div>
                                                        <div class="p-1">
                                                            <input type="checkbox" id = "no-export" value="no-export"/> NO-EXPORT-GLOBAL (no-export)
                                                        </div>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="accordion2">
                                        <div class="card mb-1">
                                            <div class="card-header" id="heading3">
                                                <h5 class="m-0">
                                                    <a class="text-dark" data-toggle="collapse" href="#collapse3" aria-expanded="true">
                                                        <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                                        Mostrar/ocultar communities para trânsito
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapse3" class="collapse hide" aria-labelledby="heading3" data-parent="#accordion2">
                                                <div class="card-body">
                                                    @foreach ($buscaCommunitiesTransito as $transitoIndex => $transitoValue)
                                                        <p class="p-1 text-success font-15 mb-0">
                                                            {{$transitoValue['nomedogrupo']}}
                                                        </p>
                                                        @foreach ($transitoValue['communities'] as $communityIndex => $communitValue)
                                                            <div  class="p-1">
                                                                <input type="checkbox" class="checkbox" name="community" value="{{$communitValue}}"/> {{$communityIndex}}
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="accordion3">
                                        <div class="card mb-1">
                                            <div class="card-header" id="heading4">
                                                <h5 class="m-0">
                                                    <a class="text-dark" data-toggle="collapse" href="#collapse4" aria-expanded="true">
                                                        <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                                        Mostrar/ocultar communities para IX
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapse4" class="collapse hide" aria-labelledby="heading4" data-parent="#accordion3">
                                                <div class="card-body">
                                                    @foreach ($buscaCommunitiesIx as $ixIndex => $ixValue)
                                                        <p class="p-1 text-success font-15 mb-0">
                                                            {{$ixValue['nomedogrupo']}}
                                                        </p>
                                                        @foreach ($ixValue['communities'] as $communityIndex => $communitValue)
                                                            <div  class="p-1">
                                                                <input type="checkbox" name="community" class="checkbox" value="{{$communitValue}}"/> {{$communityIndex}}
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    function goBack() {
        location.href = "downstreams-clients?client_id={{$clientId}}";
    }
    function saveData(){
        var communityArray = [];
        $('input:checkbox[name=community]').each(function()
        {
            if($(this).is(':checked'))
                communityArray.push($(this).val());
        });
        if($("#nome").val() == "" || $("#asn").val() == "" || $("#pop").val() == "" ||
            $("#blocosipv4").val() == "" || $("#blocosipv6").val() == "" || $("#ipv4pro").val() == "" ||
            $("#ipv4client").val() == "" || $("#ipv6pro").val() == "" ||  $("#ipv6client").val() == "" ||
             $("#recursivos").val() == "" ||  $("#equip").val() == ""
        ) {
            $.NotificationApp.send("Alarm!"
                ,"This is required field!"
                ,"top-right"
                ,"#2ebbdb"
                ,"error",
            );
            return;
        }
        elementBlock('square1', 'body');
        $.ajax({
            type: "POST",
            url: '{{ route("bgpconnection-client.store") }}',
            data: {
                nome : $("#nome").val(),
                asn : $("#asn").val(),
                pop : $("#pop").val(),
                blocosipv4 : $("#blocosipv4").val(),
                blocosipv6 : $("#blocosipv6").val(),
                ipv4pro : $("#ipv4pro").val(),
                ipv4client : $("#ipv4client").val(),
                ipv6pro : $("#ipv6pro").val(),
                ipv6client : $("#ipv6client").val(),
                recursivos : $("#recursivos").val(),
                equip : $("#equip").val(),
                global : $("#global").val(),
                ix : $("#ix").val(),
                peering : $("#peering").val(),
                cdn : $("#cdn").val(),
                transito : $("#transito").val(),
                noexporter : $("#no-export").val(),
                community : communityArray,
                clientId : '{{$clientId}}',
                _token : '{{ csrf_token() }}'
            }
        }).done(function( msg ) {
            console.log(msg);
            if(msg['status'] == 'ok') {
                console.log(msg['status']);
                var ele=document.getElementsByClassName('checkbox');
                for(var i=0; i<ele.length; i++){
                    if(ele[i].type=='checkbox')
                        ele[i].checked=false;
                }
                $("#nome").val("");
                $("#asn").val("");
                $("#pop").val("");
                $("#blocosipv4").val("");
                $("#blocosipv6").val("");
                $("#ipv4pro").val("");
                $("#ipv4client").val("");
                $("#ipv6pro").val("");
                $("#ipv6client").val("");
                $("#recursivos").val("");
                $.NotificationApp.send("Alarm!"
                    ,"Successfully updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
            } else {
                $.NotificationApp.send("Alarm!"
                    ,"Failed updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
            }
            elementUnBlock('body');
        }).fail(function(xhr, textStatus, errorThrown) {
            $.NotificationApp.send("Alarm!"
                ,"Failed updated!"
                ,"top-right"
                ,"#2ebbdb"
                ,"error",
            );
            elementUnBlock('body');
        });
    }
</script>
@endsection