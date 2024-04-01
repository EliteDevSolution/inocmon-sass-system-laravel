@extends('layouts.admin')

@section('styles')
    <!-- third party css -->
    <style>
        #edit {
            display:none;
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
                                <tr>
                                    <td id='index'> {{$index}} </td>
                                    <td id='hostname'> {{$buscarSonda['hostname']}} </td>
                                    <td id='router'> {{$buscarSonda['ipv4']}} </td>
                                    <td id='pltaforma'> pltaforma </td>
                                    <td id='so'> so </td>
                                    <td id='portassh'> {{$buscarSonda['portassh']}} </td>
                                    <td id='portahttp'> {{$buscarSonda['portahttp']}} </td>
                                    <td id='user'> {{$buscarSonda['user']}} </td>
                                    <td id='pwd'> {{$buscarSonda['pwd']}} </td>
                                    <td> <a href="{{ route("proxy-localhost.index",array('client_id' =>
                                        request()->query()['client_id'], 'proxy_id' => $index ) ) }}">GERENCIAR CONFIG</a></td>
                                    <td> <a onclick="showEdit()">Edit</a></td>
                                    <td> Delete </td>
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
        function showEdit() {
            var editPage = document.getElementById("edit");
            editPage.style.display ="block";

            var hostname = $('#hostname').text();
            var router = $('#router').text();
            var pltaforma  = $('#pltaforma').text();
            var so  = $('#so').text();
            var portassh  = $('#portassh').text();
            var portahttp  = $('#portahttp').text();
            var user  = $('#user').text();
            var pwd  = $('#pwd').text();

            $('#hostVal').val(hostname);
            $('#routerVal').val(router);
            $('#plataFormaVal').val(pltaforma);
            $('#soVal').val(so);
            $('#portaSshVal').val(portassh);
            $('#portaVal').val(portahttp);
            $('#useVal').val(user);
            $('#senhaVal').val(pwd);
            // $('#hostname').html(hostVal);
            // $('#pltaforma').html(routerVal);
            // $('#so').html(plataFormaVal);
            // $('#portassh').html(soVal);
            // $('#portahttp').html(portaSshVal);
            // $('#user').html(portaVal);
            // $('#pwd').html(userVal);
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
            var proxyId = $('#index').text();
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
                   $('#hostname').html(hostVal);
                   $('#router').html(routerVal);
                   $('#pltaforma').html(plataFormaVal);
                   $('#so').html(soVal);
                   $('#portassh').html(portaSshVal);
                   $('#portahttp').html(portaVal);
                   $('#user').html(userVal);
                   $('#pwd').html(pwdVal);
                }
            });
        }
        let closeEdit = () => {
            var editPage = document.getElementById("edit");
            editPage.style.display ="none";
        }
    </script>

@endsection