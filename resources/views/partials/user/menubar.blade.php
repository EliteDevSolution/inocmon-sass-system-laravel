<div class="container-fluid">
    <div id="navigation">
        <!-- Navigation Menu-->
        <?php
            $segment = Request::segment(2);
            $sub_segment = Request::segment(3);
        ?>
        <ul class="navigation-menu">
            <li class="has-submenu {{$segment=='compare'?'active':''}}">
                <a href="#">
                    @lang(trans('cruds.data_analysis.title')) <div class="arrow-down"></div>
                </a>
                <ul class="submenu">
                    <li class="{{($segment=='mcm-data')?'active':''}}">
                        <a href="{{ route('user.mcm-data.index') }}">
                            @lang('MCM Data')
                        </a>
                    </li>
                    <li class="{{($segment=='corners-methodology')?'active':''}}">
                        <a href="{{ route('user.corners-methodology.index') }}">
                            @lang('Corners Methodology')
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- End navigation menu -->

        <div class="clearfix"></div>
    </div>
    <!-- end #navigation -->
</div>