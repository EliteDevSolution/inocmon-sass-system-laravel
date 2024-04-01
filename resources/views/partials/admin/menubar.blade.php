<div id="sidebar-menu" style="background-color: #38414a">
    @if(request()->route()->getName() != "client.index" && $layout)
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
                        <a href="{{ route('dashboard', request()->query()) }}">Summary</a>
                    </li>
                    <li>
                        <a href="{{ route("communities-bgp.index", request()->query()) }}">Communities BGP</a>
                    </li>
                </ul>
            </li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Gerencia de ativos </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="javascript: void(0);" aria-expanded="false">Route Reflectors
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-third-level nav" aria-expanded="false">
                            @foreach ($clients as $index => $buscaRrs)
                                @if ($index == request()->query()['client_id'])
                                    @foreach ($buscaRrs['rr'] as $buscaIndex => $buscaRr )
                                        @if($buscaIndex != null)
                                            <li>
                                                <a href="{{ route("proxy-template.index",array('client_id' =>
                                                request()->query()['client_id'], 'rr_id' =>$buscaIndex))}}"> {{$buscaRr['hostname']}} </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            <li>
                                <a href="{{ route("asset_manage.pr_summary", array('client_id' =>request()->query()['client_id'])) }}">PR sumary</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript: void(0);" aria-expanded="false">Proxies
                            <span class="menu-arrow"></span>
                        </a>
                        <ul class="nav-third-level nav" aria-expanded="false">
                            @foreach ($clients as $index => $proxys)
                                @if ($index == request()->query()['client_id'])
                                    @foreach ($proxys['sondas'] as $proxyIndex => $proxy )
                                        <li>
                                            <a href="{{ route("proxy-localhost.index",array('client_id' =>
                                            request()->query()['client_id'], 'proxy_id' =>$proxyIndex))}}"> {{$proxy['hostname']}} </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                            <li>
                                <a href="{{ route("proxy-summary.index", array('client_id' =>request()->query()['client_id'])) }}">Proxy Summary</a>
                            </li>
                        </ul
                    </li>
                    <li>
                        <a href="{{ route("mpls_pe.index", request()->query())}}">MPLS PE's and P's </a>
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
            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Adming Page </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('client.index') }}">Admin</a>
                    </li>
                </ul>
            </li>

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
                @foreach($clients as $key => $client)
                    <ul class="nav-second-level" aria-expanded="false">
                        <li >
                            <a href="{{ route('dashboard',array( "client_id" => $key )) }}">{{$client['nome']}}</a>
                        </li>
                    </ul>
                @endforeach
            </li>
        </ul>
    @endif
</div>

