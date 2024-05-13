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
                        <li class="breadcrumb-item active">Client Bgp</li>
                    </ol>
                </div>
                <h4 class="page-title">Client Bgp</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card-body">
            <div class="card-box">
                <p class="font-17 text-md-center text-danger">
                    Cliente BGP : {{$toSendData['nomeDoClienteBgp']}} | ASN : {{$toSendData['remoteAs']}} remoteAs
                </p>
                <p class="font-14 mt-2 text-blue">Blocos Ipv4 : {{$toSendData['blocosIpv4']}}</p>
                <p class="font-14 text-blue">Blocos Ipv6 : {{$toSendData['blocosIpv6']}}</p>
                <p class="font-14 text-blue">Nome de Grupo : {{$toSendData['nomeDoGrupo']}}</p>

            </div>
        </div>
        <div id="accordion" class="m-1">
            <div class="card mb-1">
                <div class="card-header" id="headingOne">
                    <h5 class="m-0">
                        <a class="text-dark" data-toggle="collapse" href="#collapseOne" aria-expanded="true">
                            <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                            Mostrar/ocultar config para PE
                        </a>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse hide p-2" aria-labelledby="headingOne" data-parent="#accordion">
                    <p class="text-center p-2">
                        <pre>
                            #############################################
                            # CONFIGURAÇÕES PARA O PE -> <a >(Baixar config)</a>#
                            #############################################
                        </pre>
                        {!! $toSendData['configPeFirebase'] !!}
                    </p>
                </div>
            </div>
        </div>

        <div id="accordion1" class="m-1">
            <div class="card mb-1">
                <div class="card-header" id="headingTwo">
                    <h5 class="m-0">
                        <a class="text-dark" data-toggle="collapse" href="#collapseTwo" aria-expanded="true">
                            <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                            Mostrar/ocultar config para PE
                        </a>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse hide p-2" aria-labelledby="headingTwo" data-parent="#accordion1">
                    <pre>
                            ###########################################################
                            # CONFIGURAÇÕES PARA O RR -> <a> download>(Baixar config)</a> #
                            ###########################################################
                    </pre>
                        {!! $toSendData['configRrFirebase'] !!}
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('aplicar-config-bgp-client.aplicarConfig', array('client_id' => $toSendData['clientId'])) }}" method="post">
            @csrf
            @method('POST')
            <label class="font-weight-bold text-muted">Proxy</label>
            <input type="hidden" id="clientid" name="clientid" value={{$toSendData['clientId']}}>
            <input type="hidden" id="bgpclienteid" name="bgpclienteid" value={{$toSendData['bgpClientId']}}>
            <select class="form-control" name="sondaId">
                @if(is_array($toSendData['buscaSondas']))
                    @foreach ($toSendData['buscaSondas'] as $sondaIndex => $sondaValue )
                        <option value="{{$sondaIndex}}">
                            {{$sondaValue['hostname']}}
                        </option>
                    @endforeach
                @endif
            </select>
            <div class="mt-2">
                <input type="checkbox" id="targetpeid" name="targetpeid" value="{{$toSendData['idDoPe']}}">
                <lable For="targetpeid">Aplicar config em {{$toSendData['nomeDoPe']}} : {{$toSendData['ipDoPe']}}</lable>
            </div>
            <div class="mt-2">
                <label class="font-weight-bold text-muted">Router Reflectors</label>
                    @if(is_array($toSendData['buscaRr']))
                        @foreach ( $toSendData['buscaRr'] as $rrIndex => $rrValue )
                            @if(isset($rrIndex) && $rrIndex != 0)
                                <div>
                                    <input type="checkbox" id="rr{{$rrIndex}}" name = "rr{{$rrIndex}}" value="true">
                                    <lable For="rr{{$rrIndex}}">Aplicar em RR {{$rrIndex ?? ''}} : {{$rrValue['hostname'] ?? ''}}</lable>
                                </div>
                            @endif
                        @endforeach
                    @endif
            </div>
            <input class="btn btn-blue mt-2" type="submit" value="aplicar config"></input>
        </form>
    </div>

</div>
 <div>
    </p class='font-17 text-md-center text-danger'> Email de ativação para o cliente  {{$toSendData['nomeDoClienteBgp']}} </p>
    <div id="accordion2">
        <div class="card mb-1">
            <div class="card-header" id="headingThree">
                <h5 class="m-0">
                    <a class="text-dark" data-toggle="collapse" href="#collapseThree" aria-expanded="true">
                        <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                        Mostrar/ocultar email para cliente
                    </a>
                </h5>
            </div>
            <div id="collapseThree" class="collapse hide p-2" aria-labelledby="headingThree" data-parent="#accordion2">
                <iframe src="{{ 'https://www.ultradox.com/preview?id=eHzcIGzIhTR3UojE5b1R8FBE54aSPp&actor=rinaldopvaz@gmail.com&iid=IxXkuoxiSngM3A1l1rGR53744727&nomecliente='
                        . $toSendData['nomeCliente']
                        . '&asnlocal='. $toSendData['asn']
                        . '&ipv4local='. $toSendData['ipv4remoto01']
                        . '&ipv6local=' . $toSendData['ipv6remoto01']
                        . '&asnremoto='. $toSendData['remoteAs']
                        . '&nomebgpcliente=' . $toSendData['nomeDoClienteBgp']
                        . '&ipv4remoto='. $toSendData['ipv4remoto02']
                        . '&ipv6remoto='. $toSendData['ipv6remoto02']
                        . '&ipv4bgpmultihop='. $toSendData['ipv4bgpmultihop']
                        . '&ipv6bgpmultihop='. $toSendData['ipv6bgpmultihop'] }}"
                        title="Email Preview" width="1100" height="600" >
                </iframe>
                <button class="btn btn-primary" onclick="sendData()">
                    enviar config para cliente
                </button>
            </div>
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

        function sendData() {
             $.confirm({
                    title: 'Alert',
                    content: 'Are you sure to send that file?',
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
                                    type: "POST",
                                    url: '{{ route("send-guider.sendData", 1) }}',
                                    data: {
                                        nomeClient : "{{$toSendData['nomeCliente']}}",
                                        asnlocal : "{{$toSendData['asn']}}",
                                        ipv4local : "{{$toSendData['ipv4remoto01']}}",
                                        ipv6local : "{{$toSendData['ipv6remoto01']}}",
                                        asnremoto : "{{$toSendData['remoteAs']}}",
                                        nomebgpcliente : "{{$toSendData['nomeDoClienteBgp']}}",
                                        ipv4remoto : "{{$toSendData['ipv4remoto02']}}",
                                        ipv6remoto : "{{$toSendData['ipv6remoto02']}}",
                                        ipv4bgpmultihop : "{{$toSendData['ipv4bgpmultihop']}}",
                                        ipv6bgpmultihop : "{{$toSendData['ipv6bgpmultihop']}}",
                                        clientId : '{{$clientId}}',
                                        _token : '{{ csrf_token() }}'
                                    }
                                }).done(function( msg ) {
                                    if(msg?.status === 'ok')
                                    {
                                        $.NotificationApp.send("Alert!"
                                            ,"Successfully sended!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"success",
                                        );
                                    } else {
                                        $.NotificationApp.send("Alert!"
                                            ,"Failed sended!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"error",
                                        );
                                    }
                                }).fail(function(xhr, textStatus, errorThrown) {
                                    $.NotificationApp.send("Alarm!"
                                        ,"Failed sended !"
                                        ,"top-right"
                                        ,"#2ebbdb"
                                        ,"error",
                                    );
                                    elementUnBlock('div.card-box'');
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