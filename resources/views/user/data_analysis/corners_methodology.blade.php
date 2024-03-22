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
        .tbl-header-bg-soft-dark {
            background: rgb(9 10 10 / 25%);
        }
        .table th, .table td
        {
            padding: 0.5rem !important;
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
            <h4 class="page-title">@lang('MCM Marshall') @lang('Corners Methodology')</h4>
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
                        <tr class="text-center">
                            <th colspan="23"></th>
                            <th colspan="16" class="text-font-width-900">MCM_TIME (2 Minutes)</th>
                            <th colspan="37"></th>
                        </tr>
                        <tr class="text-center">
                            <th colspan="7"></th>
                            <th colspan="3" class="text-font-width-900">Convert</th>
                            <th colspan="7" class="text-font-width-900">Last Corner</th>
                            <th colspan="16" class="text-font-width-900">Last Minutes</th>
                            <th colspan="13" class="text-font-width-900">Last Goal </th>
                            <th colspan="16" class="text-font-width-900">Half-Time</th>
                            <th colspan="14" class="text-font-width-900">Speed Calculator</th>
                        </tr>
                        <tr class="text-center">
                            <th>Fixture<br/>ID</th>
                            <th>Home Team</th>
                            <th>Away Team</th>
                            <th>GH</th>
                            <th>GA</th>
                            <th>CH</th>
                            <th>CA</th>
                            <th>Att -> D. Att</th>
                            <th>H</th>
                            <th>A</th>
                            <th>T</th>
                            
                
                    <!-- LAST CORNER (Marcel 13_09) -->
                
                            <th>Poss</th>
                            <th>Att H</th>
                            <th>Att A</th>
                            <th>Att</th>
                            <th>DA H</th>
                            <th>DA A</th>
                            <th>DA </th>
                            <th>SH</th>
                            <th>SA</th>
                            <th>VAR</th>
                            <th>CR H</th>
                            <th>CR A</th>
                            
                           
                        <!-- LAST MINUTE (Marcel 13_09) -->
                
                            <th>Poss</th>
                            <th>Att H</th>
                            <th>Att A</th>
                            <th>Att</th>
                            <th>DA H</th>
                            <th>DA A</th>
                            <th>DA </th>
                            <th>SH</th>
                            <th>SA</th>
                            <th>A </th>
                            <th>OT H</th>
                            <th>OT A</th>
                            <th>O</th>
                            <th>VAR</th>
                            <th>CR H</th>
                            <th>CR A</th>
                
                        <!-- LAST GOAL (Marcel 13_09) -->
                
                            
                            <th>T</th>
                            <th>Poss</th>
                            <th>Att H</th>
                            <th>Att A</th>
                            <th>Att</th>
                            <th>DA H</th>
                            <th>DA A</th>
                            <th>DA </th>
                            <th>SH</th>
                            <th>SA</th>
                            <th>VAR</th>
                            <th>CR H</th>
                            <th>CR A</th>
                
                        <!-- HALF TIME (Marcel 13_09) -->
                
                            <th>Poss</th>
                            <th>Att H</th>
                            <th>Att A</th>
                            <th>Att</th>
                            <th>DA H</th>
                            <th>DA A</th>
                            <th>DA </th>
                            <th>SH</th>
                            <th>SA</th>
                            <th>A </th>
                            <th>OT H</th>
                            <th>OT A</th>
                            <th>O</th>
                            <th>VAR</th>
                            <th>CR H</th>
                            <th>CR A</th>
                
                        <!-- SPEED CALCULATOR (Marcel 13_09) -->
                
                            <th>Att H</th>
                            <th>Att A</th>
                            <th>DA H</th>
                            <th>DA A</th>
                            <th>SH</th>
                            <th>SA</th>
                            <th>CR H</th>
                            <th>CR A</th>
                            
                        </tr>
                    </thead>
                    <tbody></tbody>
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
                    { "width": "10%", "targets": 2 }
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
            $('#datatable_processing').show();
            fetchData();
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

            $('#lblpause').dblclick(function () {
                clickIndex ++;
                if(clickIndex%2 === 1)
                {
                    clearInterval(refreshIntervalId)
                    console.warn('Pause Timer', Date());
                }
                else
                {
                    refreshIntervalId = setInterval(fetchData, 60000);
                    console.warn('Start Timer', Date());
                }
            })
        });

        const getScore = (scoreslist) => {
            let homegoals = 0, awaygoals = 0;
            for (let i = 0; i < scoreslist.length; i++) {
                let eachScore = scoreslist[i];
                if (eachScore['description'] === "CURRENT") {
                    if (eachScore['score']['participant'] === "home") {
                        homegoals = eachScore['score']['goals'];
                    } else {
                        awaygoals = eachScore['score']['goals'];
                    }
                }
            }
            return [homegoals, awaygoals];
        }

        const fetchData = () => {
            console.log(Date());
            $.ajax({
                method: "POST",
                url: "{{ route('user.corners_methodology.fetch_data') }}",
                data: {key: '3485984375893'}
            }).done(function (result) {
                $('#datatable_processing').hide();
                dataTable.clear().draw();
                dataTable.rows().remove().draw();
                if(result.data)
                {
                    let analysisRecords = procData(result?.data);
                    analysisRecords.map((item) =>{
                        dataTable.row.add([
                            item.fixtureID,
                            `<strong class="text-success">${item.homeTeam}</strong>`,
                            `<strong class="text-blue">${item.awayTeam}</strong>`,
                            item.homeGoals,
                            item.awayGolas,
                            item.homeCorners,
                            item.awayCorners,
                            'Conv.',
                            parseFloat(item.conversionHome).toFixed(0),
                            parseFloat(item.conversionAway).toFixed(0),
                            item.elapsedTime,
                            item.ballPossession,
                            item.attackHome,
                            item.attackAway,
                            item.attack,
                            item.dangAttackHome,
                            item.dangAttackAway,
                            item.dangAttackTotal,
                            item.shotsHome,
                            item.shotsAway,
                            item.videoAssistantReference,
                            item.homeCrosses,
                            item.awayCorners,
                            item.ballPossession,
                            item.attackHome,
                            item.attackAway,
                            item.attack,
                            item.dangAttackHome,
                            item.dangAttackAway,
                            item.dangAttackTotal,
                            item.shotsHome,
                            item.shotsAway,
                            item.aggressionLevel,
                            item.shotsOnTargetHome,
                            item.shotsOnTargetAway,
                            0,
                            item.videoAssistantReference,
                            item.homeCrosses,
                            item.awayCrosses,
                            40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,
                            parseFloat(item.speedCalcAttackHome).toFixed(2),
                            parseFloat(item.speedCalcAttackAway).toFixed(2),
                            parseFloat(item.speedCalcDangAttackHome).toFixed(2),
                            parseFloat(item.speedCalcDangAttackAway).toFixed(2),
                            parseFloat(item.speedCalcShotsHome).toFixed(2),
                            parseFloat(item.speedCalcShotsAway).toFixed(2),
                            parseFloat(item.speedCalcHomeCrosses).toFixed(2),
                            parseFloat(item.speedCalcAwayCrosses).toFixed(2),
                        ]);
                    });
                    dataTable.rows().draw();
                } else
                {
                    $('td.dataTables_empty').html('There are currently no matches in live.');
                    $.NotificationApp.send(
                        "Warning",
                        "There are currently no matches in live.",
                        "top-right",
                        "#da8609",
                        "info");
                }
            });
        }

        const procData = (items) => {
            if (items.length > 0) {
                return items.reduce((res, item) => {
                    const [homeTeam, awayTeam] = item.name.split('vs');

                    const [homeGoals, awayGolas] = item.scores ? getScore(item.scores) : [0, 0];
                    let homeCorners = 0, awayCorners = 0;
                    let aggressionLevel = 0, homeCrosses = 0, awayCrosses = 0;
                    let ballPossessionHome = 0, ballPossessionAway = 0, ballPossession = 0;
                    let shotsOnTargetHome = 0, shotsOnTargetAway = 0;
                    let attack = 0, attackHome = 0, attackAway = 0;
                    let dangAttackTotal = 0, dangAttackHome = 0, dangAttackAway = 0;
                    let shotsAway = 0, shotsHome = 0;
                    let videoAssistantReference = 0, elapsedTime = 0;
                    let conversionHome = 0, conversionAway = 0;
                    let speedCalcAttackHome = 0, speedCalcAttackAway = 0;
                    let speedCalcDangAttackHome = 0, speedCalcDangAttackAway = 0;
                    let speedCalcShotsHome = 0, speedCalcShotsAway = 0, speedCalcHomeCrosses = 0, speedCalcAwayCrosses = 0;
                    if(item.statistics)
                    {
                        item.statistics.map((eachStatline) => {
                            if (eachStatline.data.value === null) {
                                eachStatline.data.value = 0;
                            }
                            if (eachStatline.type_id === 34) {
                                if (eachStatline.location === "home") {
                                    homeCorners = eachStatline.data.value;
                                } else {
                                    awayCorners = eachStatline.data.value;
                                }
                            }
                            if (eachStatline.type_id === 42) {
                                if (eachStatline.location === "home") {
                                    shotsHome = eachStatline.data.value;
                                } else {
                                    shotsAway = eachStatline.data.value;
                                }
                            }
                            if (eachStatline.type_id === 43) {
                                if (eachStatline.location === "home") {
                                    attackHome = eachStatline.data.value;
                                } else {
                                    attackAway = eachStatline.data.value * -1;
                                }
                            }
                            if (eachStatline.type_id === 44) {
                                if (eachStatline.location === "home") {
                                    dangAttackHome = eachStatline.data.value;
                                } else {
                                    dangAttackAway = eachStatline.data.value * -1;
                                }
                            }
                            if (eachStatline.type_id === 45) {
                                if (eachStatline.location === "home") {
                                    ballPossessionHome = eachStatline.data.value;
                                } else {
                                    ballPossessionAway = eachStatline.data.value;
                                }
                            }
                            if (eachStatline.type_id === 98) {
                                if (eachStatline.location === "home") {
                                    homeCorners = eachStatline.data.value;
                                } else {
                                    awayCorners = eachStatline.data.value;
                                }
                            }
                        });

                        aggressionLevel = shotsHome - shotsAway;
                        attack = attackHome - attackAway;
                        dangAttackTotal = dangAttackHome - dangAttackAway;
                        ballPossession = ballPossessionHome - ballPossessionAway;
                        videoAssistantReference = dangAttackHome * (shotsOnTargetHome + shotsHome) + (dangAttackAway * ((shotsOnTargetAway + shotsAway) * -1));

                        if (attackHome !== 0) {
                            conversionHome = dangAttackHome / attackHome * 100;
                        }

                        if (attackAway !== 0) {
                            conversionAway = dangAttackAway / attackAway * 100;
                        }

                        if (item.periods) {
                            item.periods.map((eachline) => {
                                elapsedTime = 0;

                                if (eachline.ticking === false) {
                                    if (eachline.description === "1st-half") {
                                        elapsedTime = "HT";
                                    } else {
                                        elapsedTime = "FT";
                                    }
                                } else {
                                    elapsedTime = eachline["minutes"];
                                }

                                if (![0, 'HT', 'FT'].includes(elapsedTime)) {
                                    speedCalcAttackHome = attackHome / elapsedTime;
                                    speedCalcAttackAway = attackAway / elapsedTime;
                                    speedCalcDangAttackHome = dangAttackHome / elapsedTime;
                                    speedCalcDangAttackAway = dangAttackAway / elapsedTime;
                                    speedCalcShotsHome = shotsHome / elapsedTime;
                                    speedCalcShotsAway = shotsAway / elapsedTime;
                                    speedCalcHomeCrosses = homeCrosses / elapsedTime;
                                    speedCalcAwayCrosses = awayCrosses / elapsedTime;
                                }
                            })
                        }
                    }

                    res.push({
                        homeTeam: homeTeam.trim(),
                        awayTeam: awayTeam.trim(),
                        fixtureID: item.id,
                        length: item.length,
                        homeGoals,
                        awayGolas,
                        homeCorners,
                        awayCorners,
                        aggressionLevel,
                        homeCrosses,
                        awayCrosses,
                        ballPossessionHome,
                        ballPossessionAway,
                        ballPossession,
                        shotsOnTargetHome,
                        shotsOnTargetAway,
                        attack,
                        attackHome,
                        attackAway,
                        dangAttackTotal,
                        dangAttackHome,
                        dangAttackAway,
                        shotsAway,
                        shotsHome,
                        videoAssistantReference,
                        elapsedTime,
                        conversionHome,
                        conversionAway,
                        speedCalcAttackHome,
                        speedCalcAttackAway,
                        speedCalcDangAttackHome,
                        speedCalcDangAttackAway,
                        speedCalcShotsHome,
                        speedCalcShotsAway,
                        speedCalcHomeCrosses,
                        speedCalcAwayCrosses,
                    });
                    return res;
                }, []);
            } else {
                return [];
            }
        }

    </script>

@endsection