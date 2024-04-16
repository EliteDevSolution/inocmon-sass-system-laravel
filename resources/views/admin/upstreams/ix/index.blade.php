@extends('layouts.admin')

@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>
        #edit {
            display:none;
        }
        th, td {
            text-align : center
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
                            <li class="breadcrumb-item active">Upstreams</li>
                            <li class="breadcrumb-item active">Ix</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Ix</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="header-title text-success mt-0 mb-2">IX NO BANCO DE DADOS
                        <a class="btn btn-success btn-rounded" href="{{ route('upstreams-ix.create',array('client_id'=>$clientId)) }}">NOVO IX</a>
                    </h3>
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th>NOME DO GRUPO</th>
                                <th>ASN</th>
                                <th>POP</th>
                                <th>PE</th>
                                <th>GERENCIAR CONFIG</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($toSendData['buscaBgp'] as $index => $value )
                                <tr id="{{$index}}" localpref="{{ $value['localpref'] ?? "" }}" medin="{{ $value['medin'] ?? "" }}">
                                    <td style="text-align: left">
                                        @if (!file_exists(public_path("img/".$value['remoteas'].".jpg")))
                                            <div>
                                                <img style="width : 30px; height : 30px" src="{{ asset('img/undefined.jpg') }}"/>
                                                {{$value['nomedogrupo']}}{{$index}}
                                            </div>
                                        @else
                                            <div>
                                                <img style="width : 30px; height : 30px" src="{{ asset("img/".$value['remoteas'].".jpg") }}" />
                                                {{$value['nomedogrupo']}}{{$index}}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{$value['remoteas']}}</td>
                                    <td>{{$value['pop']}}</td>
                                    <td id="{{$value['peid']}}">{{$toSendData['buscaEquip'][$value['peid']]['hostname'] ?? ''}}</td>
                                    <td>
                                        <a href="{{ route('template-generate-config.index',
                                        array('client_id'=>$clientId, 'indexId' => $index, 'key' => "ix", 'groupKey' => '02')) }}">
                                            <i class="fe-user"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="showEdit('{{$index}}')" class="getRow">
                                            <i class="fe-edit"></i>
                                        </a>
                                    </td>
                                    <th>
                                        <i class="fe-trash" onclick="deleteIx(this, '{{$index}}')"></i>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12" id="edit">
            <div class="card-box p-1">
                <label class="mt-2 ml-3 mb-1 font-weight-bold text-muted">Edit</label>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Nome do IXBR</label>
                            <select class="mb-1 form-control" id="nome">
                                <option id="ownVal" selected="selected">
                                </option>
                                @foreach ( $toSendData['optionVal'] as $index => $value )
                                    <option value="{{$index}}">
                                        {{$value['cidade']}}
                                    </option>
                                @endforeach
                            </select>
                            <label class="mb-1 font-weight-bold text-muted">POP</label>
                            <input type="text"  id="popVal"  required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Local Pref</label>
                            <input type="text"  id="localpref"  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Med IN</label>
                            <input type="text"  id="medin"  required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Equipmento PE</label>
                            <select class="form-control" id="peVal">
                                @foreach ( $toSendData['buscaEquip'] as $equipIndex => $equipVal )
                                    <option value="{{$equipIndex}}">
                                        {{$equipVal['hostname']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary ml-2 mt-1" onclick="saveData()" >editar</button>
                        <button class="btn btn-primary ml-2 mt-1" onclick="closeEdit()">close</button>
                    </div>
                </div> <!-- end row -->
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
        let datatable;
        let buscaBgp = @json($toSendData['buscaBgp']);
        $(document).ready(function(){
            datatable = $('#datatable').DataTable({
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

        var row;

        function showEdit(buscarSondaId) {
            var editPage = document.getElementById("edit");
            editPage.style.display ="block";
            // row = $(`#${buscarSondaId}`);
            row = document.getElementById(buscarSondaId);

            var ixId  =row.id;
            console.log(ixId);
            $("#ownVal").val(buscaBgp[ixId]['sigla']);
            $("#ownVal").text(buscaBgp[ixId]['sigla']);

            var pop = row.querySelector('td:nth-child(3)').innerText;
            console.log(pop);
            var pe = row.querySelector('td:nth-child(4)').id;
            var localpref = $(row).attr('localpref');
            var medin = $(row).attr('medin');

            console.log(row);
            console.log(localpref);

            $('#popVal').val(pop);
            $('#peVal').val(pe);
            $('#localpref').val(localpref);
            $('#medin').val(medin);

        }

        let saveData = () => {

            var popVal  = $('#popVal').val();
            var peVal  = $('#peVal').val();
            var localpref = $('#localpref').val();
            var medin = $('#medin').val();
            var sigla = $('#nome').val();

            if(localpref == "" || popVal == "" || peVal == "" || medin == "" || sigla == "") {
                $.NotificationApp.send("Alarm!"
                    ,"This is required field!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                return;
            }

            elementBlock('square1', 'body');
            var ixId  = $(row).prop('id');
            $.ajax({
                type: "PUT",
                url: '{{ route("upstreams-ix.update", 1) }}',
                data: {
                    sigla : sigla,
                    localpref : localpref,
                    medin : medin,
                    popVal : popVal,
                    peVal : peVal,
                    ixId : ixId,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(1)').textContent = $("#ownVal option:selected").text();
                    row.querySelector('td:nth-child(3)').textContent = popVal;
                    row.querySelector('td:nth-child(4)').innerText = $("#peVal option:selected").text();
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully updated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                } else {
                    $.NotificationApp.send("Alarm!"
                        ,msg['status']
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

        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }

          function deleteIx(current, ixId) {
            console.log(current);
                $.confirm({
                    title: 'Alert',
                    content: 'Are you sure to delete?',
                    draggable: true,
                    type: 'red',
                    closeIcon: false,
                    icon: 'fa fa-exclamation-triangle',
                    closeAnimation: 'top',
                    buttons: {
                        somethingElse: {
                            text: "Ok",
                            btnClass: 'btn-danger',
                            keys: ['shift'],
                            action: function()
                            {
                                $.ajax({
                                    type: "DELETE",
                                    url: '{{ route("upstreams-ix.destroy", 1) }}',
                                    data: {
                                        clientId : '{{$clientId}}',
                                        toDeleteId : ixId,
                                        _token : '{{ csrf_token() }}'
                                    }
                                }).done(function( msg ) {
                                    if(msg?.status === 'success')
                                    {
                                        datatable.row($(current).parents('tr')).remove().draw();
                                        $.NotificationApp.send("Alert!"
                                            ,"Successfully deleted!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"success",
                                        );
                                    } else {
                                        $.NotificationApp.send("Alert!"
                                            ,"Failed deleted!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"error",
                                        );
                                    }
                                });
                            }
                        },
                    cancel: function () {
                        return true;
                    },
                }
            });
        }
    </script>
@endsection