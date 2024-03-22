
<ul class="list-unstyled topnav-menu float-right mb-0">

    <li class="dropdown notification-list">
        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
            <img src="{{ asset('admin_assets/images/users/standard.png') }}" alt="user-image" class="rounded-circle">
            <span class="pro-user-name ml-1">
                {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
            <!-- item-->
            <div class="dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome !</h6>
            </div>

            <!-- item-->
{{--            <a href="javascript:void(0);" class="dropdown-item notify-item">--}}
{{--                <i class="fe-user"></i>--}}
{{--                <span>My Account</span>--}}
{{--            </a>--}}

            <!-- item-->
{{--            <a href="{{ route('auth.change_password') }}" class="dropdown-item notify-item">--}}
{{--                <i class="fas fa-key"></i>--}}
{{--                <span>Change password</span>--}}
{{--            </a>--}}

            <div class="dropdown-divider"></div>

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item notify-item"  onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="fe-log-out"></i>
                <span>Logout</span>
            </a>

        </div>
    </li>

</ul>

<!-- LOGO -->
<div class="logo-box">
    <a href="/" class="logo text-center">   
        <span class="logo-lg">
            <h1 class="text-white">@lang('SoccerAPI')</h1>
            {{-- <img src="{{ asset('admin_assets/images/logo-light.png') }}" alt="" height="50" style="display: flex;height: 65px;width: 239px;margin-top: 2px;margin-left: 2px;"> --}}
            <!-- <span class="logo-lg-text-light">UBold</span> -->
        </span>
        <span class="logo-sm">
            <h3 class="text-white">@lang('SoccerAPI')</h3>
            <!-- <span class="logo-sm-text-dark">U</span> -->
            {{-- <img src="{{ asset('admin_assets/images/logo-sm.png') }}" alt="" height="45"> --}}
        </span>
    </a>
</div>
<ul class="list-unstyled topnav-menu topnav-menu-left m-0">
    <li>
        <button class="button-menu-mobile waves-effect waves-light">
            <i class="fe-menu"></i>
        </button>
    </li>
</ul>