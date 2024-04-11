@extends('layouts.admin')
@section('styles')
    <!-- third party css -->
    <link href="{{ asset('admin_assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/libs/datatables/responsive.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <!-- third party css end -->
@endsection

@section('content')
    <div class="columns">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Client</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Client Home</h4>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-10 m-auto">
                <div class="card">
                    <div class="card-body">
                        <a class="btn btn-success mb-3" href="{{ route("client.create") }}">
                            Novo Cliente
                        </a>
                        <table id="datatable" class="table nowrap">
                            <thead>
                            <tr>
                                <th> Id</th>
                                <th> Nomes</th>
                                <th> Status</th>
                                <th> Gerenciar</th>
                                <th> Edit</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $key => $client)
                                    <tr data-entry-id="{{ $key }}" id="{{$key}}">
                                        <td>
                                            {{$key}}
                                        </td>
                                        <td>
                                            {{ $client['nome'] }}
                                        </td>
                                        <td>
                                            {{ $client['status'] }}
                                        </td>
                                        <td width="20" style="text-align: center">
                                            <a href="{{ route("dashboard", array( "client_id" => $key )) }}" >
                                                <i class="fe-user" data-tippy data-original-title="I'm a Tippy tooltip!"></i>
                                            </a>
                                        </td>
                                        <td width="20" style="text-align: center">
                                            <a href="{{ route("client.edit", $key) }}">
                                                <i class="fe-edit"></i>
                                            </a>
                                        </td>
                                        <td width="20" style="text-align: center">
                                            <i class="fe-trash" onclick="deleteClient(this, '{{$key}}')"></i>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
        let datatable;
        $(document).ready(function(){
            datatable = $('#datatable').DataTable({
                responsive: false,
                stateSave: true,
                stateDuration: 60 * 60 * 24 * 60 * 60,
                autoWidth: false,
                scrollCollapse: true,
                scrollX: true,
                bProcessing: true,
                lengthMenu: [
                    [ 10, 50, 100, 500],
                    [ '10', '50', '100', '500' ]
                ],
                columnDefs: [
                    { "width": "10%", "targets": 1 }
                ],
                pageLength: 50,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only"></span>',
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                order: [[ 0, "asc" ]]
            });

            "@if($status == 'success')"
                $.NotificationApp.send("Alarm!"
                    ,"Successfully updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"success",
                );
            "@elseif ($status == 'failed')"
                $.NotificationApp.send("Alarm!"
                    ,"Failed updated!"
                    ,"top-right"
                    ,"#2ebbdb"
                    ,"error",
                );
            "@endif"

        });

        function deleteClient(current, proxyId) {
            console.log(current);
             $.confirm({
                    title: 'Alert',
                    content: 'Are you sure to delete?',
                    draggable: true,
                    type: 'red',
                    closeIcon: false,
                    icon: 'fa fa-exclamation-triangle',
                    closeAnimation: 'top',
                    buttons: {
                        somethingElse: {
                            text: "Ok",
                            btnClass: 'btn-danger',
                            keys: ['shift'],
                            action: function()
                            {
                                $.ajax({
                                    type: "DELETE",
                                    url: '{{ route("client.destroy", 1) }}',
                                    data: {
                                        toDeleteId : proxyId,
                                        _token : '{{ csrf_token() }}'
                                    }
                                }).done(function( msg ) {
                                    if(msg?.status === 'success')
                                    {
                                        datatable.row($(current).parents('tr')).remove().draw();
                                        $.NotificationApp.send("Alert!"
                                            ,"Successfully updated!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"success",
                                        );
                                    } else {
                                        $.NotificationApp.send("Alert!"
                                            ,"Failed updated!"
                                            ,"top-right"
                                            ,"#2ebbdb"
                                            ,"error",
                                        );
                                    }
                                }).fail(function(xhr, textStatus, errorThrown) {
                                   $.NotificationApp.send("Alert!"
                                        ,"Failed updated!"
                                        ,"top-right"
                                        ,"#2ebbdb"
                                        ,"error",
                                    );
                                });
                            }
                        },
                        cancel: function () {
                            return true;
                        },
                    }
                });
        }

    </script>
@endsection