<div id="sidebar-menu" style="background-color: #38414a">
    @php
        if(!isset($clients)) {
            $clients = getClient();
        }
    @endphp

    @if(isset(request()->query()['client_id']))
        @php
            $clientId = request()->query()['client_id'];
            $clientIdQueryParam = array("client_id" => $clientId);
        @endphp

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
                        <a href="{{ route('dashboard', $clientIdQueryParam) }}">Summary</a>
                    </li>
                    <li>
                        <a href="{{ route('communities-bgp.index', $clientIdQueryParam) }}">Communities BGP</a>
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
                                                <a href="{{ route('proxy-template.index', array('client_id' => request()->query()['client_id'], 'rr_id' =>$buscaIndex))}}"> {{$buscaRr['hostname']}} </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            <li>
                                <a href="{{ route('asset_manage.pr_summary', $clientIdQueryParam) }}">RR Sumary</a>
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
                                    @if(array_key_exists('sondas', $proxys))
                                        @foreach ($proxys['sondas'] as $proxyIndex => $proxy )
                                            <li>
                                                <a href="{{ route('proxy-localhost.index', array('client_id' => request()->query()['client_id'], 'proxy_id' => $proxyIndex))}}"> {{$proxy['hostname']}} </a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                            <li>
                                <a href="{{ route('proxy-summary.index', $clientIdQueryParam) }}">Proxy Summary</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('mpls_pe.index', $clientIdQueryParam)}}">MPLS PE's and P's </a>
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
                        <a href="{{ route('upstreams.index', $clientIdQueryParam) }}">Transito</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-ix.index', $clientIdQueryParam) }}">IX</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-peer.index', $clientIdQueryParam) }}">Peering</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-cdn.index', $clientIdQueryParam) }}">CDN</a>
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
                        <a href="{{ route('downstreams-clients.index', $clientIdQueryParam) }}">Clientes ASN</a>
                    </li>
                    <li>
                        <a href="apps-kanbanboard.html">Dedicated clients</a>
                    </li>
                    <li>
                </ul>
            </li>

            <li class="menu-title mt-2">Cadastro</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Cadastro BGP</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('upstreams.create', $clientIdQueryParam) }}">Novo IP Transito</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-ix.create', $clientIdQueryParam) }}">Novo IX</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-peer.create', $clientIdQueryParam) }}">Novo Peering</a>
                    </li>
                    <li>
                        <a href="{{ route('upstreams-cdn.create', $clientIdQueryParam) }}">Novo CDN</a>
                    </li>
                    <li>
                        <a href="{{ route('bgpconnection-client.index', $clientIdQueryParam) }}">Novo  BGP client</a>
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
                        <a href="{{ route('network-pe.index' ,$clientIdQueryParam) }}">Novo PE</a>
                    </li>
                    <li>
                        <a href="{{ route('network-router-reflector.index' ,$clientIdQueryParam) }}">Novo Route Refletor</a>
                    </li>
                    <li>
                        <a href="{{ route('network-proxy.index' ,$clientIdQueryParam) }}">Novo Proxy</a>
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

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> ChangeLogs </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('changelog.index',$clientIdQueryParam) }}">Change Log</a>
                    </li>
                </ul>
            </li>

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
                            <a href="{{ route('dashboard', array('client_id' => $key)) }}">{{$client['nome']}}</a>
                        </li>
                    </ul>
                @endforeach
            </li>

            <li class="menu-title">Template Transitor</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-airplay"></i>
                    <span> Template Edit </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('temp-edit-bgp.index') }}">BGP TRANSITO</a>
                    </li>
                    <li >
                        <a href="{{ route('tempix-edit-bgp.index') }}">BPG IX</a>
                    </li>
                    <li >
                        <a href="{{ route('tempeer-edit-bgp.index') }}">BPG PEERING</a>
                    </li>
                    {{-- <li >
                        <a href="{{ route('tempcdn-edit-bgp.index') }}">BGP CDN</a>
                    </li> --}}
                    <li >
                        <a href="{{ route('deny-customer.index') }}">Deny Customer-In</a>
                    </li>
                </ul>
            </li>
        </ul>

    @endif
</div>

