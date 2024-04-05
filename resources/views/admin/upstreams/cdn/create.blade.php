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
                            <li class="breadcrumb-item active">Cdn</li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cdn</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <h2 class="header-title text-blue text-center">Novo Cdn</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Nome do Cliente</label>
                            <input type="text" name="nome" required id="nome" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">ASN do cliente</label>
                            <input type="text" name="asn" required id="asn" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">POP DO ACESSO</label>
                            <input type="text" name="pop" required id="pop" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">blocos-ipv4</label>
                            <input type="text" name="cos4" required id="cos4" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">blocos-ipv6</label>
                            <input type="text" name="cos6" required id="cos6" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 lado provedor</label>
                            <input type="text" name="ipv4pro" required id="ipv4pro" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV4 lado cliente</label>
                            <input type="text" name="ipv4client" required id="ipv4client" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">IPV6 lado provedor</label>
                            <input type="text" name="ipv6pro" required id="ipv6pro" class="form-control mb-1"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">IPV6 lado cliente</label>
                            <input type="text" name="ipv6client" required id="ipv6client" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">ASN de clientes recursivos</label>
                            <input type="text" name="recursivos" required id="recursivos" class="form-control mb-1"/>
                            <label class="mb-1 font-weight-bold text-muted">(insira todos separados por vírgula)</label></label>
                            <input type="text" name="vírgula" required id="vírgula" class="form-control mb-1"/>
                            <select class="form-control" id="equip" required data-toggle="select2">
                                @foreach ($buscaEquipamentos as $index => $value)
                                    <option value="{{$index}}" id="equip">{{$value['hostname']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row ml-2 mt-2">
                            <div class="mt-2"><input type="checkbox" value="check" id="check"> Bloquear</div>
                            <button class="btn btn-primary ml-2" id="btn" type="" onclick="saveData()">Cadastrar</button>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="header-title text-success">Cdn em banco</h3>
                    <ul id="cdn">
                        @foreach ($cdns as $index => $value)
                            @if (!file_exists(public_path("img/".$value['remoteas'].".jpg")))
                                <li>
                                    <div class="p-md-2">
                                        <img style="width : 30px; height : 30px" src="{{ asset('img/undefined.jpg') }}"/>
                                        {{$value['nomedogrupo'].'-'.$index}}
                                    </div>
                                @else
                                    <div class="p-md-2">
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
        var communityArray = [];
        function saveData(){
            $('input:checkbox[name=equip]').each(function()
            {
                if($(this).is(':checked'))
                    checkedEquipArray.push($(this).val());
            });
            $.ajax({
                type: "POST",
                url: '{{ route("bgpconnection-client.store") }}',
                data: {
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    console.log(msg);
                }
            });
        }
    </script>
@endsection
