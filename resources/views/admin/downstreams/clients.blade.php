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
            text-align:  center
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
                            <li class="breadcrumb-item active">Asset management</li>
                            <li class="breadcrumb-item active">ACN Clients</li>
                        </ol>
                    </div>
                    <h4 class="page-title">ACN Clients</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Detalhes do cliente</p>
                    <p class="mb-0">ID : {{$toSendData['clientId']}}</p>
                    <p class="mb-0">Nome:{{$toSendData['clientName']}}</p>
                    <p class="mb-0">Senha Inocomon : {{$toSendData['senhainocmon']}}</p>
                    <p class="mb-0">ASN : {{$toSendData['asn']}}</p>
                    <p class="mb-0">Nome do grupo : {{$toSendData['community']}}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table nowrap">
                        <thead>
                        <tr>
                            <th width=50>Id</th>
                            <th> Nome do cliente</th>
                            <th> ASN</th>
                            <th> POP </th>
                            <th> PE </th>
                            <th> IPV4 LOCAL </th>
                            <th> IPV4 REMOTO </th>
                            <th> IPV6 LOCAL </th>
                            <th> IPV6 REMOTO </th>
                            <th> Gerenciar </th>
                            <th> Edit </th>
                            <th> Delete </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($toSendData['clientTransito'] as $index => $value )
                            <tr id="{{$index}}">
                                    <td> {{$index}} </td>
                                    <td> {{$value['nomedoclientebgp'] ?? ''}} </td>
                                    <td> {{$value['remoteas'] ?? ''}} </td>
                                    <td> {{$value['pop'] ?? ''}} </td>
                                    <td>
                                        <a>
                                            @if (array_key_exists('idperemoto', $value))
                                                {{$toSendData['equipment'][$value['idperemoto']]['hostname'] ?? ''}}
                                            @endif
                                        </a>
                                    </td>
                                    <td> {{$value['ipv4-01'] ?? ''}} </td>
                                    <td> {{$value['ipv4-02'] ?? ''}} </td>
                                    <td> {{$value['ipv6-01'] ?? ''}} </td>
                                    <td> {{$value['ipv6-02'] ?? ''}} </td>
                                    <td>
                                        <a>
                                            <i class="fe-user" data-tippy data-original-title="I'm a Tippy tooltip!"></i>
                                        </a>
                                    </td>
                                    <td onclick="showEdit('{{$index}}')">
                                        <i class="fe-edit"></i>
                                    </td>
                                    <td>
                                        <i class="fe-trash" onclick="deleteClent(this, '{{$index}}')"></i>
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
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">Nome Do Client</label>
                                <input type="text" id ="clientnome"   required class="form-control mb-1" />
                                <label class="mb-1 font-weight-bold text-muted">ASN</label>
                                <input type="text"  id="asn"  required class="form-control mb-1"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">POP</label>
                                <input type="text"  id="pop"  required class="form-control mb-1"/>
                                <label class="mb-1 font-weight-bold text-muted">PE</label>
                                <input type="text"  id="pe"  required class="form-control mb-1"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">IPV4LOCAL</label>
                                <input type="text" id="v4local" required class="form-control mb-1"/>
                                <label class="mb-1 font-weight-bold text-muted">IPV4REMOTE</label>
                                <input type="text"  id="v4remote"  required class="form-control mb-1"/>
                            </div>
                            <div class="col-md-3">
                                <label class="mb-1 font-weight-bold text-muted">IPV6LOCAL</label>
                                <input type="text" id="v6local" required class="form-control mb-1"/>
                                <label class="mb-1 font-weight-bold text-muted">IPV6REMOTE</label>
                                <input type="text"  id="v6remote"  required class="form-control mb-1"/>
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

            console.log(row);

            var clientName = row.querySelector('td:nth-child(2)').textContent;
            var asn = row.querySelector('td:nth-child(3)').textContent;
            var pop = row.querySelector('td:nth-child(4)').textContent;
            var pe = row.querySelector('td:nth-child(5)').textContent;
            var ipv4Local = row.querySelector('td:nth-child(6)').textContent;
            var ipv4Remote = row.querySelector('td:nth-child(7)').textContent;
            var ipv6Local = row.querySelector('td:nth-child(8)').textContent;
            var ipv6Remote = row.querySelector('td:nth-child(9)').textContent;

            $('#clientnome').val(clientName);
            $('#asn').val(asn);
            $('#pop').val(pop);
            $('#pe').val(pe);
            $('#v4local').val(ipv4Local);
            $('#v4remote').val(ipv4Remote);
            $('#v6local').val(ipv6Local);
            $('#v6remote').val(ipv6Remote);

        }

        function saveData() {

            var clientName =$('#clientnome').val();
            var asn = $('#asn').val();
            var pop = $('#pop').val();
            var pe = $('#pe').val();
            var ipv4Local = $('#v4local').val();
            var ipv4Remote = $('#v4remote').val();
            var ipv6Local = $('#v6local').val();
            var ipv6Remote = $('#v6remote').val();

            var id  = $(row).prop('id');

            if(clientName == "" || asn == "" || pop == "" || pe == "" || ipv4Local == "" || ipv4Remote == "" ||
                ipv6Local == "" || ipv6Remote == ""
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
                type: "PUT",
                url: "downstreams-clients/1",
                data: {
                    clientName : clientName,
                    asn : asn,
                    pop : pop,
                    pe : pe,
                    ipv4Local : ipv4Local,
                    ipv4Remote : ipv4Remote,
                    ipv6Local : ipv6Local,
                    ipv6Remote : ipv6Remote,
                    dataId : id,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(2)').textContent = clientName;
                    row.querySelector('td:nth-child(3)').textContent = asn;
                    row.querySelector('td:nth-child(4)').textContent = pop;
                    row.querySelector('td:nth-child(5)').textContent = pe;
                    row.querySelector('td:nth-child(6)').textContent = ipv4Local;
                    row.querySelector('td:nth-child(7)').textContent = ipv4Remote;
                    row.querySelector('td:nth-child(8)').textContent = ipv6Local;
                    row.querySelector('td:nth-child(9)').textContent = ipv6Remote;
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
            });
        }
        function deleteClent(current, cdnId) {
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
                                    url: '{{ route("downstreams-clients.destroy", 1) }}',
                                    data: {
                                        clientId : '{{$clientId}}',
                                        toDeleteId : cdnId,
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
        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }
    </script>

@endsection