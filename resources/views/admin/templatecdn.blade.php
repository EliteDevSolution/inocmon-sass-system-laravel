@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>
        .group-input {
            display: flex;
            align-items: center;
            gap : 10px;
        }
        .custom-lable {
            width: 150px;
        }
        /* .custom-input {
            float: left;
            width: 400px;
        } */
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
                            <li class="breadcrumb-item active">Template Edit</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Template Edit</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p class="header-title mb-2">Nova Family</p>
                <div class="group-input m-2">
                    <label class="mb-1 custom-lable font-weight-bold text-muted"> Vendor </label>
                    <select class="form-control custom-input" id="vendor" required >
                        @foreach ($templates as $indexTemp => $valueTemp)
                            <option value="{{$indexTemp}}">
                                {{$indexTemp}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="group-input m-2">
                    <label class="mb-1 custom-lable font-weight-bold text-muted">Nova Family</label>
                    <input type="text" name="novafamily" required id="novafamily" class="custom-input form-control mb-1"/>
                </div>
                <button class="ml-1 btn btn-primary mt-2" onclick="saveData('family-update')">
                    Cadastrar-Family
                </button>
            </div>
            <div class="col-6">
                <p class="header-title mb-2">Nova Config section</p>
                <div class="group-input m-2">
                    <label class="mb-1 custom-lable font-weight-bold text-muted">Family</label>
                    <input type="text" name="family" required id="family" class="custom-input form-control mb-1"/>
                </div>
                <div class="group-input m-2">
                    <label class="mb-1 custom-lable font-weight-bold text-muted">Nova config section</label>
                    <select class="custom-input form-control" id="configsection" required >
                        @foreach ($templates as $indexTemp => $valueTemp)
                            @foreach ($templates[$indexTemp] as $index => $value)
                                <option value="{{$indexTemp.'/'.$index}}">
                                    {{$indexTemp.'/'.$index}}
                                </option>
                            @endforeach
                        @endforeach

                    </select>
                </div>
                <button class="ml-1 btn btn-primary mt-2" onclick="saveData('config-section')">
                    Cadastrar-Section
                </button>
            </div>
            <div class="col-12">
                <div class="card-box mt-3">
                    @foreach ($templates as $indexTemp => $valueTemp)
                        @if (is_array($valueTemp))
                            @foreach ($valueTemp as $indexFamily => $valueFamily)
                                <p class="header-title">
                                    {{$indexTemp}}
                                </p>
                                <p>
                                    {{$indexFamily}}
                                </p>
                                @if (is_array($valueFamily))
                                    @foreach ($valueFamily as $index => $value)
                                            <div id="accordion">
                                                <div class="card mb-1">
                                                    <div class="card-header" id="headingOne{{$index}}">
                                                        <h5 class="m-0">
                                                            <a class="text-dark" data-toggle="collapse" href="#collapseOne{{$index}}" aria-expanded="true">
                                                                <i class="mdi mdi-help-circle mr-1 text-primary"></i>
                                                                Mostrar/ocultar config {{$index}}
                                                            </a>
                                                        </h5>
                                                    </div>

                                                    <div id="collapseOne{{$index}}" class="collapse hide" aria-labelledby="headingOne{{$index}}" data-parent="#accordion">
                                                        <div class="card-body">
                                                            <textarea rows = 10 cols = 100 id="{{$index}}">
                                                                {{$value}}
                                                            </textarea>
                                                            <button class="btn btn-primary mb-3" onclick="saveData('{{$index}}','{{$indexFamily}}','{{$indexTemp}}')">
                                                                Garava
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="select2"]').select2()
        });
    </script>

    @parent
    <!-- third party js -->
    <script src="{{ asset('admin_assets/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <!-- third party js ends -->
    <!-- Datatables init -->
    <script>
        function saveData(todo, firstId = null, secondId = null, thirdId = null){
            var vendor = $("#vendor").val();
            var novaFamily = $("#novafamily").val();
            var family = $("#family").val();
            var configSection = $("#configsection").val();
            var textVal = $(`#${todo}`).val();
            console.log(textVal);
            $.ajax({
                type: "put",
                url: "tempcdn-edit-bgp/1",
                data: {
                    todo : todo,
                    vendor : vendor,
                    novaFamily : novaFamily,
                    family : family,
                    firstId : firstId,
                    secondId : secondId,
                    thirdId : thirdId,
                    textVal : textVal,
                    configSection : configSection,
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    console.log(msg);
                    $.NotificationApp.send("Alarm!"
                        ,"Successfully added!"
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
            });
        }
    </script>
@endsection