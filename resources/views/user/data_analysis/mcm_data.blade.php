@extends('layouts.user')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('user_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/libs/datatables/buttons.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('user_assets/libs/datatables/select.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <style>
        .text-font-width-900 {
            font-size: 12px;
            font-weight: 900;
        }
        thead th {
            font-weight: 800;
            font-size: 12px;
            color: black;
        }
        .tbl-header-bg-soft-dark {
            background: rgb(9 10 10 / 25%);
        }
        .table th, .table td
        {
            padding: 0.5rem !important;
            text-align: center;
        }
        table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
            top: 0;
        }

        div.dataTables_wrapper div.dataTables_processing
        {
            width: 60px;
            margin-left: 0;
            margin-top: 0;
            padding: 1em;
        }
        #lblpause
        {
            height: 5px;
            width: 20px;
        }

    </style>
@endsection
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">@lang('soccer')</a></li>
                        <li class="breadcrumb-item">@lang('Corners Methodology')</li>
                    </ol>
                </div>
                <h4 class="page-title">@lang('MCM Data')</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <label id="lblpause"></label>
                <div class="card-body">
                    <table class="table small table-bordered table-hover table-responsive" id="datatable">
                        <thead class="tbl-header-bg-soft-dark">
                            <tr>
                                <th style="background-color: #D9D9D9">TEAMS</th>
                                <th style="background-color: #D9D9D9">MCM SEASON</th>
                                <th style="background-color: #D9D9D9">I P</th>
                                <th style="background-color: #D9D9D9;width: 30px;font-size: 15px;"> 0 </th>
                                <th style="background-color: #D9E1F2;">H_G_ATT_H</th>
                                <th style="background-color: #D9E1F2;">H_G_ATT_D</th>
                                <th style="background-color: #8EA9DB;">H_C_ATT_H</th>
                                <th style="background-color: #8EA9DB;">H_C_ATT_D</th>
                                <th style="background-color: #D9E1F2;">H_T.S_ATT_H</th>
                                <th style="background-color: #D9E1F2;">H_T.S_ATT_D</th>
                                <th style="background-color: #8EA9DB;">H_T_ATT_H</th>
                                <th style="background-color: #8EA9DB;">H_T_ATT_D</th>
                                <th style="background-color: #D9E1F2;">H_P_ATT_H</th>
                                <th style="background-color: #D9E1F2;">H_P_ATT_D</th>
                                <th style="background-color: #8EA9DB;">H_C_ATT_H</th>
                                <th style="background-color: #8EA9DB;">H_C_ATT_D</th>
                                <th style="background-color: #FCE4D6">A_G_ATT_H</th>
                                <th style="background-color: #FCE4D6">A_G_ATT_D</th>
                                <th style="background-color: #F4B084;">A_C_ATT_H</th>
                                <th style="background-color: #F4B084;">A_C_ATT_D</th>
                                <th style="background-color: #FCE4D6">A_T.S_ATT_H</th>
                                <th style="background-color: #FCE4D6">A_T.S_ATT_D</th>
                                <th style="background-color: #F4B084;">A_T_ATT_H</th>
                                <th style="background-color: #F4B084;">A_T_ATT_D</th>
                                <th style="background-color: #FCE4D6;">A_P_H</th>
                                <th style="background-color: #FCE4D6;">A_P_D</th>
                                <th style="background-color: #F4B084;">A_C_H</th>
                                <th style="background-color: #F4B084;">A_C_D</th>
                           </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                                <td>2342</td>
                            </tr>
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <!-- third party js -->
    <script src="{{ asset('user_assets/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('user_assets/libs/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('user_assets/libs/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('user_assets/libs/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('user_assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <!-- third party js ends -->
    <!-- Datatables init -->
    <script>
        let dataTable;
        let refreshIntervalId;
        let clickIndex = 0;
        $(document).ready(function () {
            dataTable = $('#datatable').DataTable({
                responsive: false,
                stateSave: true,
                stateDuration: 60 * 60 * 24 * 60 * 60,
                autoWidth: false,
                bProcessing: true,
                lengthMenu: [
                    [ 10, 50, 100, 500, 1000],
                    [ '10', '50', '100', '500', '1000' ]
                ],
                columnDefs: [
                    { "width": "10%", "targets": 1 }
                ],
                pageLength: 500,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only"></span>',
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    },
                    zeroRecords: "There are currently no matches in live."
                },
                order: [[ 0, "asc" ]]
            });
            $('td.dataTables_empty').html('');
            //$('#datatable_processing').show();
            //refreshIntervalId = setInterval(fetchData, 60000);
            // window.onfocus = function () {
            //     refreshIntervalId = setInterval(fetchData, 20000);
            //     console.warn('Start Timer', Date());
            // };
            //
            // window.onblur = function () {
            //     clearInterval(refreshIntervalId)
            //     console.warn('Pause Timer', Date());
            // };
        });
    </script>

@endsection