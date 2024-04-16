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
        /* .th_td_hide {
            display: none;
        } */
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
                                <th class="th_td_hide">provedor</th>
                                <th class="th_td_hide">ipv4 da sessao 01</th>
                                <th class="th_td_hide">ipv4 da sessao 02</th>
                                <th class="th_td_hide">ipv6 da sessao 01</th>
                                <th class="th_td_hide">ipv6 da sessao 02</th>
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
                                    <td id="{{$value['peid']}}">{{$toSendData['buscaEquip'][$value['peid']]['hostname'] ?? ''}}</td>
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
                                    <td class="th_td_hide">{{$value['provedor']}}</td>
                                    <td class="th_td_hide">{{$value['ipv4-01']}}</td>
                                    <td class="th_td_hide">{{$value['ipv4-02']}}</td>
                                    <td class="th_td_hide">{{$value['ipv6-01']}}</td>
                                    <td class="th_td_hide">{{$value['ipv6-02']}}</td>
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
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">ASN</label>
                            <input type="text" id ="asnVal" name=""  required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">POP</label>
                            <input type="text" id="popVal" required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">PE</label>
                            <select class="form-control mb-1" id="peVal">
                                @foreach ( $toSendData['buscaEquip'] as $index => $value )
                                    <option value="{{$index}}">
                                        {{$value['hostname']}}
                                    </option>
                                @endforeach
                            </select>
                            <label class="mb-1 font-weight-bold text-muted">PROVEDPR</label>
                            <input type="text" id="provedor" required class="form-control mb-1"/>

                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">IPV4 DA SESSA 01</label>
                            <input type="text" id="ipv401" required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 DA SESSA 02</label>
                            <input type="text" id="ipv402" required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">IPV6 DA SESSA 01</label>
                            <input type="text" id="ipv601" required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV6 DA SESSA 02</label>
                            <input type="text" id="ipv602" required class="form-control mb-1"/>
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
            var provedor = row.querySelector('td:nth-child(8)').innerText;
            var ipv401 = row.querySelector('td:nth-child(9)').innerText;
            var ipv402 = row.querySelector('td:nth-child(10)').innerText;
            var ipv601 = row.querySelector('td:nth-child(11)').innerText;
            var ipv602 = row.querySelector('td:nth-child(12)').innerText;

            $('#asnVal').val(asn);
            $('#popVal').val(pop);
            $('#peVal').val(pe);
            $('#provedor').val(provedor);
            $('#ipv401').val(ipv401);
            $('#ipv402').val(ipv402);
            $('#ipv601').val(ipv601);
            $('#ipv602').val(ipv602);
        }

        let saveData = () => {

            var asnVal  = $('#asnVal').val();
            var popVal  = $('#popVal').val();
            var peVal  = $('#peVal').val();
            var provedor = $('#provedor').val();
            var ipv401 = $('#ipv401').val();
            var ipv402 = $('#provedor').val();
            var ipv601 = $('#ipv601').val();
            var ipv602 = $('#ipv602').val();

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
                    asn : asnVal,
                    pop : popVal,
                    pe : peVal,
                    provedor : provedor,
                    ipv401 : ipv401,
                    ipv402 : ipv402,
                    ipv601 : ipv601,
                    ipv602 : ipv602,
                    trafficId : trafficId.substring(8, trafficId.lenght),
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(2)').innerText = asnVal;
                    row.querySelector('td:nth-child(3)').innerText = popVal;
                    row.querySelector('td:nth-child(4)').innerText = $("#peVal option:selected").text();
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
                                }).fail(function(xhr, textStatus, errorThrown) {
                                    $.NotificationApp.send("Alarm!"
                                        ,"Failed updated!"
                                        ,"top-right"
                                        ,"#2ebbdb"
                                        ,"error",
                                    );
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