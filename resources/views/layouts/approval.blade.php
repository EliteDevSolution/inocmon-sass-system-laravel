@extends('layouts.user')
@section('styles')

@endsection
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Waiting for Approval</div>

                    <div class="card-body">
                        @if(Auth::user()->status == "Pending")
{{--                            Your account is waiting for our administrator approval.--}}
                            Thanks for your registration, we will process it as soon as possible.<br/>
                            You will receive an email when your trial access is ready. Please, don´t forget to check also your spam email folder.<br/>
                            This message should stay there so the user could read all and understand that he has to wait a confirmation.<br/>
                            This is because now are some people registering 2-3 times thinking that there was some mistake.
                        @else
                            Your account is rejected by administrator.
                        @endif
                        <br />
                        <a href="javascript:void(0);" class="text-white-50" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                            Please check back.
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent

@endsection