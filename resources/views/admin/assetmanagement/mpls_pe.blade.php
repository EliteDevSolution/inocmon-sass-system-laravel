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
                            <li class="breadcrumb-item active">MPLS PE's and P's</li>
                        </ol>
                    </div>
                    <h4 class="page-title">MPLS PE's and P's</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title text-success mt-0 mb-2">P's e PEs
                        <a href="{{ route('mpls_pe.create', array('client_id' =>$clientId)) }}" class="btn-success ml-2 btn" >
                        Adicionar Novo
                        </a>
                    </h4>
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Hostname</th>
                                <th>RouterId</th>
                                <th>Vendor</th>
                                <th>Family</th>
                                <th>Protocolo</th>
                                <th>Porta</th>
                                <th>User</th>
                                <th>Senha</th>
                                <th>Gerenciar</th>
                                <th>Editar</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(is_array($toSendData['equipments']))
                                @foreach ($toSendData['equipments'] as $index => $value)
                                    <tr id="equipId{{$index}}" group="{{ $value['grupo-ibgp'] ?? '' }}">
                                        <td>{{$index}}</td>
                                        <td>{{$value['hostname'] ?? ""}}</td>
                                        <td>{{$value['routerid'] ?? ""}}</td>
                                        <td>{{$value['template-vendor'] ?? ""}}</td>
                                        <td>{{$value['template-family'] ?? ""}}</td>
                                        <td>{{$value['protocolo'] ?? ""}}</td>
                                        <td>{{$value['porta'] ?? ""}}</td>
                                        <td>{{$value['user'] ?? ""}}</td>
                                        <td>{{$value['pwd'] ?? ""}}</td>
                                        <td>
                                            <a href="{{ route("mpls-detail.index", array('client_id' =>
                                            request()->query()['client_id'], 'equip_id' => $index ) ) }}">
                                                <i class="fe-user" data-tippy data-original-title="I'm a Tippy tooltip!"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a  onclick="showEdit('equipId{{$index}}')" class="getRow">
                                                <i class="fe-edit"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <i class="fe-trash" onclick="deleteEquip(this, '{{$index}}')"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12" id="edit">
            <div class="card-box p-1">
                <label class="mt-2 ml-3 mb-1 font-weight-bold text-muted">Edit</label>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">HostName</label>
                            <input type="text" id ="hostVal" name=""  required class="form-control mb-1" />
                            <label class="mb-1 font-weight-bold text-muted">RouterId</label>
                            <input type="text" id="routerVal"   class="form-control mb-1" required />
                            <label class="mb-1 font-weight-bold text-muted">Protocolo</label>
                            <input type="text" id="protocoloVal" required class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Vendor</label>
                            <select class="form-control mb-1" id="vendorVal">
                                @foreach ( $buscaTemplateVendor as $index => $value )
                                    <option value="{{$index}}">
                                        {{$index}}
                                    </option>
                                @endforeach
                            </select>
                            <label class="mb-1 font-weight-bold text-muted">Family</label>
                            <select class="form-control mb-1" id="familyVal">
                                @foreach ( $buscaTemplateVendor as $indexFamily => $valueFamily )
                                    @foreach ( $valueFamily as $index => $value )
                                        <option value="{{$index}}">{{$index}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                            <label class="mb-1 font-weight-bold text-muted">Group IBGP</label>
                            <select id="group" class="form-control" >
                                <option value="IBGP-PARCIAL">IBGP-PARCIAL</option>
                                <option value="IBGP-FULL">IBGP-FULL</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Porta</label>
                            <input type="text" id="portaVal" required  class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">User</label>
                            <input type="text" id="useVal" required class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">Senha</label>
                            <input type="text" id="senhaVal" required  class="form-control mb-1"/>
                        </div>
                        <div>
                        <button class="btn btn-primary ml-2 mt-1" onclick="saveData()" >editar</button>
                        <button class="btn btn-primary ml-2 mt-1" onclick="closeEdit()">close</button>
                    </div>
                </div> <!-- end row -->
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
    </script>

     <script>
        var row;
        function showEdit(buscarSondaId) {
            var editPage = document.getElementById("edit");
            editPage.style.display ="block";
            row = document.getElementById(buscarSondaId);

            var hostName = row.querySelector('td:nth-child(2)').innerText;
            var routerId = row.querySelector('td:nth-child(3)').innerText;
            var vendor = row.querySelector('td:nth-child(4)').innerText;
            var family = row.querySelector('td:nth-child(5)').innerText;
            var protocolo = row.querySelector('td:nth-child(6)').innerText;
            var porta = row.querySelector('td:nth-child(7)').innerText;
            var user = row.querySelector('td:nth-child(8)').innerText;
            var pwd = row.querySelector('td:nth-child(9)').innerText;
            var group = $(row).attr('group');

            $('#hostVal').val(hostName);
            $('#routerVal').val(routerId);
            $('#vendorVal').val(vendor);
            $('#familyVal').val(family);
            $('#protocoloVal').val(protocolo);
            $('#portaVal').val(porta);
            $('#useVal').val(user);
            $('#senhaVal').val(pwd);
            $('#group').val(group);
        }

        let saveData = () => {

            var hostName = $('#hostVal').val();
            var routerId = $('#routerVal').val();
            var vendor = $('#vendorVal').val();
            var family = $('#familyVal').val();
            var protocolo = $('#protocoloVal').val();
            var porta = $('#portaVal').val();
            var user =$('#useVal').val();
            var pwd = $('#senhaVal').val();
            var equipId = row.querySelector('td:nth-child(1)').innerText;
            var group = $('#group').val();

            if(hostName == "" || routerId == "" || vendor == "" || family == "" || protocolo == "" || porta == "" || user == "" || equipId == "") {
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
                url: '{{ route("mpls_pe.update", 1) }}',
                data: {
                    hostName : hostName,
                    routerId : routerId,
                    vendor : vendor,
                    family : family,
                    protocolo : protocolo,
                    porta : porta,
                    user : user,
                    pwd : pwd,
                    equipId : equipId,
                    group : group,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
                    console.log(msg);
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully updated!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                    row.querySelector('td:nth-child(2)').innerText = hostName;
                    row.querySelector('td:nth-child(3)').innerText = routerId;
                    row.querySelector('td:nth-child(4)').innerText = vendor;
                    row.querySelector('td:nth-child(5)').innerText = family;
                    row.querySelector('td:nth-child(6)').innerText = protocolo;
                    row.querySelector('td:nth-child(7)').innerText = porta;
                    row.querySelector('td:nth-child(8)').innerText = user;
                    row.querySelector('td:nth-child(9)').innerText = pwd;
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
        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }
    </script>
    <script>
        function deleteEquip(current, equipId) {
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
                                    url: '{{ route("mpls_pe.destroy", 1) }}',
                                    data: {
                                        clientId : '{{$clientId}}',
                                        toDeleteId : equipId,
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
