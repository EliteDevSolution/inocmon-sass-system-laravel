@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>
        .select2-container{
            width: 100% !important;
        }
        .select2-selection--single{
            height: 32px !important;
            border-color: #ced4da !important;
        }
        .select2-selection__rendered{
            /*line-height: 32px !important;*/
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
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Create Traffic</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <h2 class="header-title text-blue text-center">Novo transito</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Nome transito</label>
                            <input type="text" required ="nome" id="nome" class="form-control mb-1" placeholder="Nome transito" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">PoP de acesso</label>
                            <input type="text" required name="pop" id="pop" class="form-control mb-1" placeholder="PoP de accesso" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv6 remoto bgp 01:</label>
                            <input type="text" required name="bgp01" id="bgp01" class="form-control mb-1" placeholder="Ipv6 remoto bgp 01" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">ASN do transito</label>
                            <input type="text" name="asn" required id="asn" class="form-control mb-1" placeholder="ASN do transito" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv4 remoto bgp 01:</label>
                            <input type="text" name="bgp1" id="bgp1" required class="form-control mb-1" placeholder="Ipv4 remoto bgp 01" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv4 remoto bgp 02:</label>
                            <input type="text" name="bgp2" id="bgp2" required class="form-control mb-1" placeholder="Ipv4 remoto bgp 02" style=" z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Ipv6 remoto bgp 02:</label>
                            <input type="text" name="bgp02" id="bgp02" required class="form-control mb-1" placeholder="Ipv6 remoto bgp 02" style=" z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Equipamento PE:</label>
                            <select class="form-control" id="equip" required data-toggle="select2">
                                @foreach ($buscaEquipamentos as $index => $value)
                                    <option value="{{$index}}">{{$value['hostname']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row ml-2 mt-2">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox form-check">
                                    <input type="checkbox" class="custom-control-input" id="check">
                                    <label class="custom-control-label" for="invalidCheck">Bloquear clientes de trânsito</label>
                                    <div class="invalid-feedback">
                                        You must agree before submitting.
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary ml-2" id="btn" type="" onclick="saveData()">Cadastrar</button>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul>
                        @foreach ($cdns as $index => $value)
                            @if (!file_exists(public_path("img/".$value['remoteas'].".jpg")))
                                <li>
                                    <div class="p-md-1">
                                        <img style="width : 30px; height : 30px" src="{{ asset('img/undefined.jpg') }}"/>
                                        {{$value['nomedogrupo'].'-'.$index}}
                                    </div>
                                @else
                                    <div class="p-md-1">
                                        <img style="width : 30px; height : 30px" src="{{ asset("img/".$value['remoteas'].".jpg") }}" />
                                        {{$value['nomedogrupo'].'-'.$index}}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>

    <script>

        function saveData(){

            var nomeVal = $('#nome').val();
            var popVal = $('#pop').val();
            var asnVal = $('#asn').val();
            var bgp1Val = $('#bgp1').val();
            var bgp2Val = $('#bgp2').val();
            var bgp01Val = $('#bgp01').val();
            var bgp02Val = $('#bgp02').val();
            var equipVal = $('#equip').val();
            var checkVal = $('#check').val();
            $.ajax({
                type: "POST",
                url: '{{ route("upstreams.store") }}',
                data: {
                    nomeVal :nomeVal,
                    popVal :popVal,
                    asnVal :asnVal,
                    bgp1Val :bgp1Val,
                    bgp2Val :bgp2Val,
                    bgp01Val :bgp01Val,
                    bgp02Val :bgp02Val,
                    equipVal :equipVal,
                    checkVal :checkVal,
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    $('#nome').val("");
                    $('#pop').val("");
                    $('#asn').val("");
                    $('#bgp1').val("");
                    $('#bgp2').val("");
                    $('#bgp01').val("");
                    $('#bgp02').val("");
                    $('#equip').val("");
                    $('#check').val("");
                }
            });
        }
    </script>
@endsection