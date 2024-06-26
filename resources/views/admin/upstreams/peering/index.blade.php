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
        td, th {
            text-align: center;
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
                            <li class="breadcrumb-item active">Peer</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Peer</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="header-title text-success mt-0 mb-2">PEER NO BANCO DE DADOS
                        <a class="btn btn-success btn-rounded" href="{{ route('upstreams-peer.create',array('client_id'=>$clientId)) }}">NOVO PEERING</a>
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
                            @if(is_array($toSendData['buscaBgp']))
                                @foreach ($toSendData['buscaBgp'] as $index => $value )
                                    <tr id="peer{{$index}}" provedor="{{ $value['provedor'] ?? "" }}" ipv401="{{ $value['ipv4-01'] ?? "" }}"
                                    ipv402="{{ $value['ipv4-02'] ?? "" }}" ipv601="{{ $value['ipv6-01'] ?? "" }}" ipv602="{{ $value['ipv6-02'] ?? "" }}"
                                    localpref="{{ $value['localpref'] ?? "" }}" medin="{{ $value['medin'] ?? "" }}">
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
                                        <td id="{{$value['peid']}}">
                                            {{$toSendData['buscaEquip'][$value['peid']]['hostname'] ?? ''}}
                                        </td>
                                        <td>
                                            <a href="{{ route('template-generate-config.index',
                                            array('client_id'=>$clientId, 'indexId' => $index, 'key' => "peering", 'groupKey' => '3')) }}">
                                                <i class="fe-user" data-tippy data-original-title="I'm a Tippy tooltip!"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a onclick="showEdit('peer{{$index}}')" class="getRow">
                                                <i class="fe-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <i class="fe-trash" onclick="deletePeer(this, '{{$index}}')"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">Nome Peering</label>
                            <input type="text" id ="provedor" name=""  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">ASN</label>
                            <input type="text" id ="asnVal" name=""  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">POP</label>
                            <input type="text"  id="popVal"  required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">Ipv4 da sessao 01</label>
                            <input type="text"  id="ipv401"  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv4 da sessao 02</label>
                            <input type="text"  id="ipv402"  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv6 da sessao 01</label>
                            <input type="text"  id="ipv601"  required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">Ipv6 da sessao 02</label>
                            <input type="text"  id="ipv602"  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Local Pref</label>
                            <input type="text"  id="localpref"  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Med In</label>
                            <input type="text"  id="medin"  required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">PE</label>
                            <select class="form-control" id="peVal">
                                @if(is_array($toSendData['buscaEquip']))
                                    @foreach ( $toSendData['buscaEquip'] as $index => $value )
                                        <option value="{{$index}}">
                                            {{$value['hostname']}}
                                        </option>
                                    @endforeach
                                @endif
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
            var pe = row.querySelector('td:nth-child(4)').id;
            var provedor = $(row).attr('provedor');
            var ipv401 = $(row).attr('ipv401');
            var ipv402 = $(row).attr('ipv402');
            var ipv601 = $(row).attr('ipv601');
            var ipv602 = $(row).attr('ipv602');
            var localpref = $(row).attr('localpref');
            var medin = $(row).attr('medin');


            $('#provedor').val(provedor);
            $('#asnVal').val(asn);
            $('#popVal').val(pop);
            $('#ipv401').val(ipv401);
            $('#ipv402').val(ipv402);
            $('#ipv601').val(ipv601);
            $('#ipv602').val(ipv602);
            $('#localpref').val(localpref);
            $('#medin').val(medin);
            $('#peVal').val(pe);

        }

        let saveData = () => {

            var asnVal  = $('#asnVal').val();
            var popVal  = $('#popVal').val();
            var peVal  = $('#peVal').val();
            var peerId  = $(row).prop('id');
            var provedor = $('#provedor').val();
            var ipv401 = $('#ipv401').val();
            var ipv402 = $('#ipv402').val();
            var ipv601 = $('#ipv601').val();
            var ipv602 = $('#ipv602').val();
            var localpref = $('#localpref').val();
            var medin = $('#medin').val();

            if(asnVal == "" || popVal == "" || peVal == "") {
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
                url: '{{ route("upstreams-peer.update", 1) }}',
                data: {
                    asnVal : asnVal,
                    popVal : popVal,
                    peVal : peVal,
                    provedor : provedor,
                    localpref : localpref,
                    ipv401 : ipv401,
                    ipv402 : ipv402,
                    ipv601 : ipv601,
                    ipv602 : ipv602,
                    medin : medin,
                    peerId : peerId.substring(4, peerId.lenght),
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(2)').innerText = asnVal;
                    row.querySelector('td:nth-child(3)').innerText = popVal;
                    row.querySelector('td:nth-child(4)').innerText = $("#peVal option:selected").text().trim();
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
        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }

        function deletePeer(current, peerId) {
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
                                    url: '{{ route("upstreams-peer.destroy", 1) }}',
                                    data: {
                                        clientId : '{{$clientId}}',
                                        toDeleteId : peerId,
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