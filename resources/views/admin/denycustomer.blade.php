@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>

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
                            <li class="breadcrumb-item active">Template config</li>
                            <li class="breadcrumb-item active">Deny-customer-in</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Template config: deny-customer-in</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="font-22 font-italic text-center form-control-range text-blue">
                    Ativação
                </p>
                @foreach ($buscaTemplateActivate as $indexVendor => $family)
                    @foreach ($family as $indexFamily => $x)
                        <div class="card-box col-12">
                            <h4 class="text-center font-family-secondary">
                                {{$indexVendor}} / {{$indexFamily}}
                            </h4>
                            <div class="">
                                <p class="text-primary font-17 font-family-secondary text-center">
                                    Aspath
                                </p>
                                <textarea class="form-control" id="header{{$indexFamily}}" rows= 5 cols=120>{{$x['header']}}</textarea>
                                <button class="btn btn-success mt-2" onclick="saveData('header','activity', 'header{{$indexFamily}}', '{{$indexVendor}}', '{{$indexFamily}}')">
                                    gravar
                                </button>
                            </div>
                        </div>
                        <div class="card-box col-12">
                            <p class="text-primary font-17 font-family-secondary text-center">
                                Aspath
                            </p>
                            <textarea class="form-control" id="aspath{{$indexFamily}}" rows= 5 cols=120>{{$x['aspath']}}</textarea>
                            <button class="btn btn-success mt-2" onclick="saveData('aspath', 'activity', 'aspath{{$indexFamily}}', '{{$indexVendor}}', '{{$indexFamily}}')">
                                gravar
                            </button>
                        </div>
                        <div class="card-box col-12">
                            <p class="text-primary font-17 font-family-secondary text-center">
                                Policy
                            </p>
                            <textarea class="form-control" id="policy{{$indexFamily}}" rows= 5 cols=120>{{$x['policy']}}</textarea>
                            <button class="btn btn-success mt-2" onclick="saveData('policy', 'activity', 'policy{{$indexFamily}}', '{{$indexVendor}}', '{{$indexFamily}}')">
                                gravar
                            </button>
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="col-12">
                <p class="font-22 font-italic text-center form-control-range text-blue">
                    Desativação
                </p>
                @foreach ($buscaTemplateDeactivate as $indexVendor => $family)
                    @foreach ($family as $indexFamily => $x)
                        <div class="card-box col-12">
                            <h4 class="text-center font-family-secondary">
                                {{$indexVendor}} / {{$indexFamily}}
                            </h4>
                            <div class="card-box col-12">
                                <p class="text-primary font-17 font-family-secondary text-center">
                                    Aspath
                                </p>
                                <textarea class="form-control" id="dis{{$indexFamily}}" rows= 5 cols=120>{{$x['aspath']}}</textarea>
                                <button class="btn btn-success mt-2" onclick="saveData('aspath', 'deactivity', 'dis{{$indexFamily}}', '{{$indexVendor}}', '{{$indexFamily}}')">
                                    gravar
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endforeach
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
        function saveData(toPlace, direction, textareaId, dir1, dir2){

            if( $(`#${textareaId}`).val() == '' ) {
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
                type: "put",
                url: "deny-customer/1",
                data: {
                    toPlace : toPlace,
                    direction : direction,
                    textAreaVal : $(`#${textareaId}`).val(),
                    dir1: dir1,
                    dir2 : dir2,
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                if(msg['status'] == 'ok') {
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
                elementUnBlock('body');
            }).fail(function(xhr, textStatus, errorThrown) {
                $.NotificationApp.send("Alarm!"
                    ,"Failed updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
                elementUnBlock('body');
            });
        }
    </script>
@endsection