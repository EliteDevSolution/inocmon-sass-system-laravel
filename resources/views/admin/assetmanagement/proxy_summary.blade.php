@extends('layouts.admin')

@section('styles')
    <!-- third party css -->
    <style>
        #edit {
            display:none;
        }
        td, th {
            text-align: center;
        }
    </style>

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
                            <li class="breadcrumb-item active">Asset management</li>
                            <li class="breadcrumb-item active">Proxies</li>
                            <li class="breadcrumb-item active">Proxy Summary</li>
                        </ol>
                    </div>
                    <h4 class="page-title">PR sumary</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th width=50>
                                    Id
                                </th>
                                <th>
                                    Hostname
                                </th>
                                <th>
                                    RouterId
                                </th>
                                <th>
                                    Plataforma
                                </th>
                                <th>
                                    SO
                                </th>
                                <th>
                                    &nbsp;PortaSSH
                                </th>
                                <th>
                                    &nbsp;Porta
                                </th>
                                <th>
                                    &nbsp;User
                                </th>
                                <th>
                                    Senha
                                </th>
                                <th>
                                    &nbsp;Gerenciar
                                </th>
                                <th>
                                    &nbsp;Editar
                                </th>
                                <th>
                                    &nbsp;Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buscarSondas as $index => $buscarSonda )
                                <tr id="buscarSondaId{{$index}}">
                                    <td id='index'> {{$index}} </td>
                                    <td id='hostname'> {{$buscarSonda['hostname'] ?? ''}} </td>
                                    <td id='router'> {{$buscarSonda['ipv4'] ?? ''}} </td>
                                    <td id='pltaforma'> pltaforma </td>
                                    <td id='so'> so </td>
                                    <td id='portassh'> {{$buscarSonda['portassh'] ?? ''}} </td>
                                    <td id='portahttp'> {{$buscarSonda['portahttp'] ?? ''}} </td>
                                    <td id='user'> {{$buscarSonda['user'] ?? ''}} </td>
                                    <td id='pwd'> {{$buscarSonda['pwd'] ?? ''}} </td>
                                    <td>
                                        <a href="{{ route("proxy-localhost.index",array('client_id' =>
                                        request()->query()['client_id'], 'proxy_id' => $index ) ) }}">
                                            <i class="fe-user" data-tippy data-original-title="I'm a Tippy tooltip!"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a onclick="showEdit('buscarSondaId{{$index}}')" class="getRow">
                                            <i class="fe-edit"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fe-trash" onclick="deletePR(this, '{{$index}}')"></i>
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
                            <label class="mb-1 font-weight-bold text-muted">HostName</label>
                            <input type="text" id ="hostVal" name=""  required class="form-control mb-1" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">RouterId</label>
                            <input type="text" id="routerVal"   class="form-control mb-1" required placeholder="Asn" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">Plataforma</label>
                            <input type="text"  id="plataFormaVal"  required class="form-control mb-1"  placeholder="Community" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">So</label>
                            <input type="text"  id="soVal" required  class="form-control mb-1"  placeholder="Ipv4 remoto bgp 01" required style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">PortaSsh</label>
                            <input type="text" id="portaSshVal" required class="form-control mb-1"  placeholder="Userinocmon" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Porta</label>
                            <input type="text" id="portaVal" required  class="form-control mb-1"  placeholder="inocmon" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">User</label>
                            <input type="text" id="useVal" required class="form-control mb-1"  placeholder="Community SNMP:	" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Senha</label>
                            <input type="text" id="senhaVal" required  class="form-control mb-1"  placeholder="Nome do group" style=" z-index: 2; background: transparent;"/>
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

        let showEdit = (buscarSondaId) => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="block";
            row = document.getElementById(buscarSondaId);

            var hostName = row.querySelector('td:nth-child(2)').textContent;
            var routerId = row.querySelector('td:nth-child(3)').textContent;
            var plataforma = row.querySelector('td:nth-child(4)').textContent;
            var so = row.querySelector('td:nth-child(5)').textContent;
            var portassh = row.querySelector('td:nth-child(6)').textContent;
            var portahttp = row.querySelector('td:nth-child(7)').textContent;
            var user = row.querySelector('td:nth-child(8)').textContent;
            var pwd = row.querySelector('td:nth-child(9)').textContent;

            $('#hostVal').val(hostName);
            $('#routerVal').val(routerId);
            $('#plataFormaVal').val(plataforma);
            $('#soVal').val(so);
            $('#portaSshVal').val(portassh);
            $('#portaVal').val(portahttp);
            $('#useVal').val(user);
            $('#senhaVal').val(pwd);
        }

        let saveData = () => {

            var hostVal  = $('#hostVal').val();
            var routerVal  = $('#routerVal').val();
            var plataFormaVal  = $('#plataFormaVal').val();
            var soVal  = $('#soVal').val();
            var portaSshVal  = $('#portaSshVal').val();
            var portaVal  = $('#portaVal').val();
            var userVal  = $('#useVal').val();
            var pwdVal  = $('#senhaVal').val();
            var proxyId = row.querySelector('td:nth-child(1)').textContent;

            if(hostVal == "" || routerVal == "" || plataFormaVal == "" || soVal == "" || portaSshVal == "" || portaVal == "" || userVal == "" || userVal == "" || pwdVal == ""
            )   {
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
                url: '{{ route("proxy-summary.update", 1) }}',
                data: {
                    proxyId : proxyId,
                    hostVal : hostVal,
                    routerVal : routerVal,
                    plataFormaVal : plataFormaVal,
                    soVal : soVal,
                    portaSshVal : portaSshVal,
                    portaVal : portaVal,
                    userVal : userVal,
                    pwdVal : pwdVal,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(2)').textContent = hostVal;
                    row.querySelector('td:nth-child(3)').textContent = routerVal;
                    row.querySelector('td:nth-child(4)').textContent = plataFormaVal;
                    row.querySelector('td:nth-child(5)').textContent = soVal;
                    row.querySelector('td:nth-child(6)').textContent = portaSshVal;
                    row.querySelector('td:nth-child(7)').textContent = portaVal;
                    row.querySelector('td:nth-child(8)').textContent = userVal;
                    row.querySelector('td:nth-child(9)').textContent = pwdVal;
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
                elementUnBlock('body');
            });
        }

        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }

        function deletePR(current, prId) {
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
                                    url: '{{ route("proxy-summary.destroy", 1) }}',
                                    data: {
                                        clientId : '{{$clientId}}',
                                        toDeleteId : prId,
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