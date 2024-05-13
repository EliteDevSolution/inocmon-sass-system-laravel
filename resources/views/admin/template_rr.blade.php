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
                            <li class="breadcrumb-item active">Route Reflectors</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Template config: deny-customer-in</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if(is_array($buscaTemplateRr))
                    @foreach ($buscaTemplateRr as $indexVendor => $family)
                        @if (is_array($family))
                            @foreach ($family as $indexFamily => $x)
                                <div class="card-box col-12">
                                    <h4 class="font-22 font-italic text-center form-control-range text-blue">
                                        {{$indexVendor}} / {{$indexFamily}}
                                    </h4>
                                    <div class="">
                                        <p class="text-primary font-17 font-family-secondary text-center">
                                            RR base config
                                        </p>
                                        <textarea class="form-control" id="header{{$indexFamily}}" rows= 5 cols=120>{{$x['rr-base-config']}}</textarea>
                                        <button class="btn btn-success mt-2" onclick="saveData('rr-base-config', 'header{{$indexFamily}}', '{{$indexVendor}}', '{{$indexFamily}}')">
                                            gravar
                                        </button>
                                    </div>
                                </div>
                                <div class="card-box col-12">
                                    <h4 class="font-22 font-italic text-center form-control-range text-blue">
                                        {{$indexVendor}} / {{$indexFamily}}
                                    </h4>
                                    <p class="text-primary font-17 font-family-secondary text-center">
                                        RR, novo PE config
                                    </p>
                                    <textarea class="form-control" id="aspath{{$indexFamily}}" rows= 5 cols=120>{{$x['rr-novope-config']}}</textarea>
                                    <button class="btn btn-success mt-2" onclick="saveData('rr-novope-config', 'aspath{{$indexFamily}}', '{{$indexVendor}}', '{{$indexFamily}}')">
                                        gravar
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @endif
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
        function saveData(toPlace, textareaId, dir1, dir2){

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
                url: "template-rr/1",
                data: {
                    toPlace : toPlace,
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