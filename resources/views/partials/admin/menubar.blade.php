<div id="sidebar-menu" style="background-color: #38414a">
    @if(request()->route()->getName() == "dashboard")
        <ul class="metismenu" id="side-menu">
            <li class="menu-title">Navigation</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-airplay"></i>
                    <span> Dashboards </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('dashboard') }}">Summary</a>
                    </li>
                    <li>
                        <a href="{{ route("communities-bgp.index") }}">Communities BGP</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Asset  management </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="javascript: void(0);" aria-expanded="false">Router Reflectors
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-third-level nav" aria-expanded="false">
                            <li>
                                <a href="{{ route("asset_manage.pr_summary") }}">PR sumary</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" aria-expanded="false">Proxies
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-third-level nav" aria-expanded="false">
                            <li>
                                <a href="{{ route("proxy-localhost.index") }}">Localhost</a>
                            </li>
                            <li>
                                <a href="{{ route("proxy-summary.index") }}">Proxy Summary</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route("mpls_pe.index") }}">MPLS PE's and P's </a>
                    </li>
                </ul>
            </li>

            <li class="menu-title mt-2">BGP</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Upstreams </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('upstreams.index') }}">Traffic</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-ix.index') }}">IX</a>
                    </li>
                    <li>
                        <a href="{{ route("upstreams-peer.index") }}">Peering</a>
                    </li>
                    <li>
                        <a href="{{ route("upstreams-cdn.index") }}">CDN</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> DownStreams </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route("downstreams-clients.index") }}">ACN clients</a>
                    </li>
                    <li>
                        <a href="apps-kanbanboard.html">Dedicated clients</a>
                    </li>
                    <li>
                </ul>
            </li>

            <li class="menu-title mt-2">Register</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> BGP connections </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route("bgpconnection-transit.index") }}">Novo IP Transit</a>
                    </li>
                    <li>
                        <a href="{{ route("bgpconnection-ix.index") }}">Novo IX</a>
                    </li>
                    <li>
                        <a href="{{ route("bgpconnection-peer.index") }}">Novo Peering</a>
                    </li>
                    <li>
                        <a href="{{ route("bgpconnection-cdn.index") }}">Novo CDN</a>
                    </li>
                    <li>
                        <a href="{{ route("bgpconnection-client.index") }}">Novo  BGP client</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Network assets </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route("network-pe.index") }}">Novo PE</a>
                    </li>
                    <li>
                        <a href="{{ route("network-router-reflector.index") }}">Novo Route Refletor</a>
                    </li>
                    <li>
                        <a href="{{ route("network-proxy.index") }}">Novo Proxy</a>
                    </li>
                </ul>
            </li>

            <li class="menu-title mt-2">more</li>


            {{--        <li>--}}
            {{--            <a href="javascript: void(0);">--}}
            {{--                <i class="fe-pocket"></i>--}}
            {{--                <a href=""> ChangeLog </a>--}}
            {{--            </a>--}}
            {{--        </li>--}}

            <li class="menu-title mt-2">admin</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> User management </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('users.index') }}">Users</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Customers </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="apps-kanbanboard.html">test</a>
                    </li>
                </ul>
            </li>

        </ul>
    @else
        <ul class="metismenu" id="side-menu">
            <li class="menu-title">Atalhhos</li>
            <li>
                <a href="javascript: void(0);">
                    <i class="fe-airplay"></i>
                    <span> Client </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('dashboard') }}">Clients</a>
                    </li>
                </ul>
            </li>
        </ul>
    @endif
</div>

