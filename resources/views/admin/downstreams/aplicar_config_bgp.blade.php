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
                        <li class="breadcrumb-item active">Aplicar Config Bgp</li>
                    </ol>
                </div>
                <h4 class="page-title">Aplicar Config Bgp</h4>
            </div>
        </div>
        <div class="col-6 p-2 card-box">
            <p>degub : {{$firstPanelData['debug']}}</p>
            <p>o token é : {{$firstPanelData['token']}}</p>
            <p id="configbgp">o status do teste : {{$firstPanelData['statusConfigBgpClientePe']}}</p>
            <p>cliente id : {{$clientId}}</p>
            <p>cliente bgp id : {{$bgpclienteId}}</p>
            <p>ip da sonda : {{$firstPanelData['sondaIpv4']}}</p>
            <p>id da sonda : {{$firstPanelData['sondaId']}}</p>
            <p>ip da sonda : {{$firstPanelData['sondaIpv4']}}</p>
        </div>

        <div class="col-6 p-2 card-box">
            <p class="text-center font-15 text-danger">Aplicando conifog para</p>
            <p>ID : {{$clientId}}</p>
            <p>Nome : {{$secondPanelData['nomeClient']}}</p>

            <p class="text-center font-14 text-blue">Dados do cliente BGP</p>
            <p> Nome do cliente: $nomeDoClienteBgp </p>
            <p> PE destino ID: {{$secondPanelData['targetpeid']}} </p>
            <p> PE destino ipv4:  {{$secondPanelData['targetPeIpv4']}} </p>
            <p> SenhaInoc:  {{$secondPanelData['senhaInocmon']}} </p>
            <p> porta no PE destino:  {{$secondPanelData['targetPePort']}} </p>

            <p class="text-center font-14 text-blue"> Dados da sonda </p>
            <p> Sonda ipv4: {{$firstPanelData['sondaIpv4']}} </p>
            <p> Sonda Id: {{$firstPanelData['sondaId']}}  </p>
            <P>Sonda Porta SSH: {{$firstPanelData['sondaPortaSsh']}} </p>
            <P> Sonda User: {{$secondPanelData['sondaUser']}} </p>
            <p> Sonda Pwd:{{$secondPanelData['sondaPwd']}}  </p>

            <p class="text-center font-14 text-blue">Dados dos arquivos </p>
            <p> configFileNamePe:{{$secondPanelData['configFileNamePe']}} </p>
            <P> configFileNameRr: {{$secondPanelData['configFileNameRr']}} </p>
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
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-database.js"></script>
    <script src="{{ asset('admin_assets/js/firebase_config.js') }}"></script>
    <!-- third party js ends -->
    <!-- Datatables init -->


  <script>

        $(document).ready(function(){
            firebase.initializeApp(firebaseConfig);
            var ref = firebase.database().ref("/clientes/{{$clientId}}/bgp/interconexoes/clientesbgp/{{$bgpclienteId}}/configs/peconfigstatus");
            ref.on("value", function (snapshot) {
                const data = snapshot.val();
                console.log(data);
                $('#configbgp').text('o status do teste :' data);
            }, function (error) {
                console.log("Error: " + error.code);
            });
        });


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

@endsection