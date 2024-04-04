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
                            <li class="breadcrumb-item active">Router Reflectors</li>
                            <li class="breadcrumb-item active">PR sumary</li>
                        </ol>
                    </div>
                    <h4 class="page-title">PR sumary</h4>
        			<span class="">Lets say you want to sort the fourth column (3) descending and the first column (0) ascending: your order: would look like this: order: [[ 3, 'desc' ], [ 0, 'asc' ]]</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mt-1">
                <div class="card-body">
                    <table id="datatable" class="table nowrap">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>HostName</th>
                                <th>RouterId</th>
                                <th>Vendor</th>
                                <th>Family</th>
                                <th>Protocolo</th>
                                <th>User</th>
                                <th>Senha</th>
                                <th>Gerencia</th>
                                <th>Editar</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buscarRr as $index => $value)
                                @if ($value != null)
                                    <tr id="prSummaryId{{$index}}">
                                        <td>{{$index}}</td>
                                        <td>{{$value['hostname']}}</td>
                                        <td>{{$value['routerid']}}</td>
                                        <td>{{$value['template-vendor']}}</td>
                                        <td>{{$value['template-family']}}</td>
                                        <td>{{$value['protocolo']}}</td>
                                        <td>{{$value['porta']}}</td>
                                        <td>{{$value['user']}}</td>
                                        <td>
                                            <a href={{ route("proxy-template.index",array('client_id' =>
                                        request()->query()['client_id'], 'rr_id' => $index ) ) }}>gerenciar config</a>
                                        </td>
                                        <td>
                                            <a onclick="showEdit('prSummaryId{{$index}}')" class="getRow">editar</a>
                                        </td>
                                        <td>
                                            <a href="">delete</a>
                                        </td>
                                    </tr>
                                @endif
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
                            <label class="mb-1 font-weight-bold text-muted">Vendar</label>
                            <input type="text"  id="vendarVal"  required class="form-control mb-1"  placeholder="Community" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Family</label>
                            <input type="text"  id="familyVal" required  class="form-control mb-1"  placeholder="Ipv4 remoto bgp 01" required style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">Protocolo</label>
                            <input type="text" id="protocoloVal" required class="form-control mb-1"  placeholder="Userinocmon" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">User</label>
                            <input type="text" id="userVal" required  class="form-control mb-1"  placeholder="inocmon" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1 font-weight-bold text-muted">Senha</label>
                            <input type="text" id="senhaVal" required class="form-control mb-1"  placeholder="Community SNMP:	" style=" z-index: 2; background: transparent;"/>
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
        var row;
        function showEdit(buscarId) {
            var editPage = document.getElementById("edit");
            editPage.style.display ="block";
            row = document.getElementById(buscarId);
            var hostName = row.querySelector('td:nth-child(2)').textContent;
            var routerId = row.querySelector('td:nth-child(3)').textContent;
            var vendor = row.querySelector('td:nth-child(4)').textContent;
            var family = row.querySelector('td:nth-child(5)').textContent;
            var protocol = row.querySelector('td:nth-child(6)').textContent;
            var user = row.querySelector('td:nth-child(7)').textContent;
            var pwd = row.querySelector('td:nth-child(8)').textContent;

            $('#hostVal').val(hostName);
            $('#routerVal').val(routerId);
            $('#familyVal').val(family);
            $('#vendarVal').val(vendor);
            $('#protocoloVal').val(protocol);
            $('#userVal').val(user);
            $('#senhaVal').val(pwd);

        }
        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }
        let saveData = () => {
            var rrId = row.querySelector('td:nth-child(1)').textContent;
            var hostName = $('#hostVal').val();
            var routerId = $('#routerVal').val();
            var family= $('#familyVal').val();
            var vendor = $('#vendarVal').val();
            var protocol = $('#protocoloVal').val();
            var user = $('#userVal').val();
            var pwd = $('#senhaVal').val();

            $.ajax({
                type: "post",
                url: '{{ route("pr-summary.update", 1) }}',
                data: {
                    rrId : rrId,
                    hostName : hostName,
                    routerId : routerId,
                    family : family,
                    vendor : vendor,
                    protocol : protocol,
                    user : user,
                    pwd : pwd,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    row.querySelector('td:nth-child(2)').textContent = hostName;
                    row.querySelector('td:nth-child(3)').textContent = routerId;
                    row.querySelector('td:nth-child(4)').textContent = vendor;
                    row.querySelector('td:nth-child(5)').textContent = family;
                    row.querySelector('td:nth-child(6)').textContent = protocol;
                    row.querySelector('td:nth-child(7)').textContent = user;
                    row.querySelector('td:nth-child(8)').textContent = pwd;
                }
            });
        }
    </script>

@endsection