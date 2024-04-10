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
            text-align : center;
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
                            <li class="breadcrumb-item active">Traffic</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Traffic</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="header-title text-success mt-0 mb-2">TRANSITOS NO BANCO DE DADOS
                        <a class="btn btn-success btn-rounded" href="{{ route('upstreams.create', array('client_id'=>$clientId)) }}">NOVO TRANSITO</a>
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
                                <tr id="transito{{$index}}">
                                    <td style="text-align: left">
                                        @if (!file_exists(public_path("img/".$value['remoteas'].".jpg")))
                                            <div>
                                                <img style="width : 30px; height : 30px" src="{{ asset('img/undefined.jpg') }}"/>
                                                {{$value['nomedogrupo']}}-{{$index}}
                                            </div>
                                        @else
                                            <div>
                                                <img style="width : 30px; height : 30px" src="{{ asset("img/".$value['remoteas'].".jpg") }}" />
                                                {{$value['nomedogrupo']}}-{{$index}}
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{$value['remoteas']}}</td>
                                    <td>{{$value['pop']}}</td>
                                    <td>{{$toSendData['buscaEquip'][$value['peid']]['hostname']}}</td>
                                    <td>
                                        <a href="{{ route('template-generate-config.index',
                                        array('client_id'=>$clientId, 'indexId' => $index, 'key' => "transito", 'groupKey' => '01')) }}">
                                            <i class="fe-user" data-tippy data-original-title="I'm a Tippy tooltip!"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="showEdit('transito{{$index}}')" class="getRow">
                                            <i class="fe-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fe-trash" onclick="deleteTraffic(this, '{{$index}}')"></i>
                                    </td>
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
                            <label class="mb-1 font-weight-bold text-muted">ASN</label>
                            <input type="text" id ="asnVal" name=""  required class="form-control mb-1" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">POP</label>
                            <input type="text"  id="popVal"  required class="form-control mb-1"  placeholder="Community" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">PE</label>
                            <input type="text" id="peVal" required class="form-control mb-1"  placeholder="Userinocmon" style=" z-index: 2; background: transparent;"/>
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

            row = document.getElementById(buscarSondaId);

            var asn = row.querySelector('td:nth-child(2)').innerText;
            var pop = row.querySelector('td:nth-child(3)').innerText;
            var pe = row.querySelector('td:nth-child(4)').innerText;

            $('#asnVal').val(asn);
            $('#popVal').val(pop);
            $('#peVal').val(pe);
        }

        let saveData = () => {

            var asnVal  = $('#asnVal').val();
            var popVal  = $('#popVal').val();
            var peVal  = $('#peVal').val();
            var trafficId  = $(row).prop('id');
            if( asnVal == "" || popVal == "" || peVal == "" ) {
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
                type: "PUT",
                url: '{{ route("upstreams.update", 1) }}',
                data: {
                    asnVal : asnVal,
                    popVal : popVal,
                    peVal : peVal,
                    trafficId : trafficId.substring(8, trafficId.lenght),
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(2)').innerText = asnVal;
                    row.querySelector('td:nth-child(3)').innerText = popVal;
                    row.querySelector('td:nth-child(4)').innerText = peVal;
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully updated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                } else {
                    $.NotificationApp.send("Alarm!"
                        ,'The operation failed!'
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('body');
            });
        }
        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }

        function deleteTraffic(current, trafficId) {
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
                                    url: '{{ route("upstreams.destroy", 1) }}',
                                    data: {
                                        clientId : '{{$clientId}}',
                                        toDeleteId : trafficId,
                                        _token : '{{ csrf_token() }}'
                                    }
                                }).done(function( msg ) {
                                    if(msg?.status === 'success')
                                    {
                                        datatable.row($(current).parents('tr')).remove().draw();
                                        $.NotificationApp.send("Alert!"
                                            ,"Successfully updated!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"success",
                                        );
                                    } else {
                                        $.NotificationApp.send("Alert!"
                                            ,"Failed updated!"
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