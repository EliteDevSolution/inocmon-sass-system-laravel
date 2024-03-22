@extends('layouts.user')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">UBold</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>     
<!-- end page title -->
@if($trial_version_msg != "")
    <div class="alert alert-info" role="alert">
        <i class="mdi mdi-alert-circle-outline mr-2"></i> {{ $trial_version_msg }}
    </div>
@endif
<div class="row">
</div>
@endsection
