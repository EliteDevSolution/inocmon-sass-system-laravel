@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>
        #changelog {
            min-height: 300px;
        }

  </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                    <li class="breadcrumb-item active">Changelog</li>
                </ol>
            </div>
            <h4 class="page-title">Change Log</h4>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card-box">
            <button class="btn btn-outline-secondary float-right mb-2" onclick="saveData()">
                save
            </button>
            @if($todo == 'update')
                <div class="form-group mb-3">
                    <label for="product-name">Changelog Date<span class="text-danger">*</span></label>
                    <input type="date" id="changelogdata" class="form-control" value="{{$changelogData['date']}}" />
                </div>
                <div class="form-group mb-3">
                    <label for="product-reference">Versão<span class="text-danger">*</span></label>
                    <input type="text" id="version" class="form-control" value="{{$changelogData['versao']}}" />
                </div>
                <div class="form-group mb-3">
                    <label for="changelog">ChangeLog<span class="text-danger">*</span></label>
                    <div id="changelog">
                        {!!$changelogData['content']!!}
                    </div>
                    {{-- <textarea class="form-control" id="product-description" rows="5" placeholder="Please enter description"></textarea> --}}
                </div>
            @else
                <div class="form-group mb-3">
                    <label for="product-name">Changelog Date<span class="text-danger">*</span></label>
                    <input type="date" id="changelogdata" class="form-control"/>
                </div>
                <div class="form-group mb-3">
                    <label for="product-reference">Versão<span class="text-danger">*</span></label>
                    <input type="text" id="version" class="form-control" />
                </div>
                <div class="form-group mb-3">
                    <label for="changelog">ChangeLog<span class="text-danger">*</span></label>
                    <div id="changelog">
                    </div>
                    {{-- <textarea class="form-control" id="product-description" rows="5" placeholder="Please enter description"></textarea> --}}
                </div>
            @endif
        </div> <!-- end card-box -->
    </div> <!-- end col -->
</div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pages/datatables.init.js') }}"></script>

    <script>
        $(document).ready(function(){
            // $('#product-description').summernote();
            $('#changelog').summernote({
            });
        });
    </script>

    @parent
    <!-- third party js -->
    <script src="{{ asset('admin_assets/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/summernote/summernote-bs4.min.js') }}"></script>
    <script>
        function saveData() {
            var changelog = $('#changelog').summernote('code');
            console.log(changelog);
            elementBlock('square1', 'body');
            $.ajax({
                type: "POST",
                url: '{{ route("changelog.$todo") }}',
                data: {
                    changelogdata : $("#changelogdata").val(),
                    version : $("#version").val(),
                    changelog : changelog,
                    changeId : '{{$changeId}}',
                    clientId : '{{$clientId}}',
                    _token : '{{ csrf_token() }}'
                }
            }).done(function( msg ) {
                console.log(msg);
                if(msg['status'] == 'ok') {
                    if( '{{$todo}}' == "store" ) {
                        document.getElementById('changelog').innerHTML = "";
                        $("#version").val("");
                        $("#changelogdata").val("");
                        $.NotificationApp.send("Alarm!"
                            ,"Successfully added!"
                            ,"top-right"
                            ,"#2ebbdb"
                            ,"success",
                        );
                    } else {
                        $.NotificationApp.send("Alarm!"
                            ,"Successfully updated!"
                            ,"top-right"
                            ,"#2ebbdb"
                            ,"success",
                        );
                    }
                }else {
                    $.NotificationApp.send("Alarm!"
                        ,"Failed operting!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"error",
                    );
                }
                elementUnBlock('body');
            });
        }
    </script>
    <!-- third party js ends -->
@endsection