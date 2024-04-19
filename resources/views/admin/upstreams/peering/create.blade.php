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
                            <li class="breadcrumb-item active">Peer</li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Peer</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card-box p-1">
                <div class="card-body">
                    <h2 class="header-title text-blue text-center">Novo Peer</h2>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Nome peering</label>
                            <input type="text" name="nome" required id="nome" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">POP de acesso</label>
                            <input type="text" name="pop" required id="pop" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">ASN do peering</label>
                            <input type="text" name="asn" required id="asn" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Ipv4 remoto bgp 01</label>
                            <input type="text" name="ipv401" required id="ipv401" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv4 remoto bgp 02</label>
                            <input type="text" name="ipv402" required id="ipv402" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Ipv6 remoto bgp 01</label>
                            <input type="text" name="ipv601" required id="ipv601" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                        </div>
                        <div class="col-md-4">
                            <label class="mb-1 font-weight-bold text-muted">Ipv6 remoto bgp 02</label>
                            <input type="text" name="ipv602" required id="ipv602" class="form-control mb-1"  style="z-index: 2; background: transparent;"/>
                            <label class="mb-1 font-weight-bold text-muted">Equipamento PE:</label>
                            <select class="form-control" id="equip" required data-toggle="select2">
                                @if(is_array($buscaEquipamentos))
                                    @foreach ($buscaEquipamentos as $index => $value)
                                        <option value="{{$index}}">{{$value['hostname']}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="row ml-2 mt-2">
                            <div class="mt-2"><input type="checkbox" value="check" id="check"> Bloquear</div>
                            <button class="btn btn-primary ml-2" id="btn" type="" onclick="saveData()">Cadastrar</button>
                            <button class="btn btn-blue ml-2" id="btn" type="" onclick="goBack()">Cadastrar</button>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="header-title text-success">Ixs em banco</h3>
                    <ul id="cdn">
                        @foreach ($cdns as $index => $value)
                            @if (!file_exists(public_path("img/".$value['remoteas'].".jpg")))
                                <li>
                                    <div class="p-md-2">
                                        <img style="width : 30px; height : 30px" src="{{ asset('img/undefined.jpg') }}"/>
                                        {{$value['nomedogrupo']}}
                                    </div>
                                @else
                                    <div class="p-md-2">
                                        <img style="width : 30px; height : 30px" src="{{ asset("img/".$value['remoteas'].".jpg") }}" />
                                        {{$value['nomedogrupo']}}
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
        function goBack() {
            location.href = "/upstreams-peer?client_id={{$clientId}}";
        }

        function saveData(){
            if( $('#nome').val() == "" || $('#pop').val()  == "" || $('#ipv401').val() == "" || $('#ipv402').val() == "" || $('#ipv601').val() == "" || $('#ipv602').val() == "" ) {
                $.NotificationApp.send("Alarm!"
                    ,"This is required field!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                return;
            }
            elementBlock('square1', '.p-1');
            $.ajax({
                type: "POST",
                url: '{{ route("upstreams-peer.store") }}',
                data: {
                    nome : $('#nome').val(),
                    pop : $('#pop').val(),
                    asn : $('#asn').val(),
                    ipv401 : $('#ipv401').val(),
                    ipv402 : $('#ipv402').val(),
                    ipv601 : $('#ipv601').val(),
                    ipv602 : $('#ipv602').val(),
                    equip : $('#equip').val(),
                    check : $('#check').val(),
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    console.log(msg);
                    var id = msg['addedData']['id'];
                    var imgSrc = msg['addedData']['remoteas'];
                    var text1 = msg['addedData']['nomedogrupo'];
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully added!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                    var myList = document.getElementById('cdn');
                    var newItem1 = document.createElement('li');
                    var div1 = document.createElement('div');
                    div1.className = 'p-md-2';
                    var img1 = document.createElement('img');

                    img1.src = '{{ asset('img/undefined.jpg') }}';
                    img1.style.width = '30px';
                    img1.style.height = '30px';
                    var text = document.createTextNode(text1);
                    div1.appendChild(img1);
                    div1.appendChild(text);
                    newItem1.appendChild(div1);
                    myList.appendChild(newItem1);
                } else {
                    $.NotificationApp.send("Alarm!"
                        ,"Failed added!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('.p-1');
            }).fail(function(xhr, textStatus, errorThrown) {
                $.NotificationApp.send("Alarm!"
                    ,"Failed updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                elementUnBlock('.p-1');
            });
        }
    </script>
@endsection
