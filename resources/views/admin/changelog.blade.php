@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>

    tr {
        cursor: pointer;
    }
    .backcolor {
        background-color: #eee;
    }
    .selected {
      background-color: rgba(233, 229, 229, 0.835);
    }
    .nodisplay  {
        display: none;
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
                            <li class="breadcrumb-item active">Changelog</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Change Log</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3">
            <div class="card-box">
                <p class="font-15 text-blue">
                    Chang Log
                </p>
                <table class="table nowrap">
                    <thead>
                        <tr>
                            <th>Verso</th>
                            <th>Data</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( array_reverse($changelogData) as $indexChangeLog => $valueChangeLog)
                            <tr id="{{$indexChangeLog}}">
                                <td>{{$valueChangeLog['versao']}}</td>
                                <td>{{$valueChangeLog['date']}}</td>
                                <td><p class="btn-success btn" style="padding : 1.3px !important;font-size:0.575rem">Update</p></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-xl-9">
            @foreach ( array_reverse($changelogData) as $index => $value )
                <div class="card-box nodisplay" id="box{{$index}}">
                    <p class="font-14">Versão</p>
                    <span>First update is the most simplified and includes urgent bug fixes of core components</span><br>
                    <span class="text-muted heading-text m-r-5">
                        {{$value['date']}}
                    </span><br>
                    <label>
                        v{{$value['versao']}}
                    </label>
                    <div class="backcolor">
                        {!! $value['content'] !!}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('admin_assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/pages/datatables.init.js') }}"></script>

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
        $(document).ready(function() {
             // Add 'selected' class to the first <tr> by default
            $('tbody tr:first').addClass('selected');
            $('.col-xl-9:first').removeClass('nodisplay');
            var firstId =  $('tbody tr:first').attr('id');
            $('#box' + firstId).show();

            // Attach click event handler to the <tr> elements within the <tbody>
            $('tbody tr').click(function() {
                // Remove the 'selected' class from all <tr> elements
                $('tbody tr').removeClass('selected');
                // Add the 'selected' class to the clicked <tr> element
                $(this).addClass('selected');
                $('.nodisplay').hide();
                var targetId = $(this).attr('id');
                $('#box' + targetId).show();

            });
        });
    </script>


@endsection