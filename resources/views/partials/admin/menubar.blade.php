<div id="sidebar-menu">

    @php
        if(!isset($clients)) {
            $clients = getClient();
        }
    @endphp

    <ul class="metismenu" id="side-menu">
    @if(isset(request()->query()['client_id']) || !(auth()->user()->hasRole("administrator") || auth()->user()->hasRole("master")) )
        @php
            $clientId;
            if(isset(request()->query()['client_id'])) {
                $clientId = request()->query()['client_id'];
            } else {
                $clientId = auth()->user()->client_id;
            }
            $clientIdQueryParam = array("client_id" => $clientId);
        @endphp
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
                                @if ($index == $clientIdQueryParam)
                                    @foreach ($buscaRrs['rr'] ?? [] as $buscaIndex => $buscaRr )
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
                                @if ($index == $clientIdQueryParam)
                                    @if(array_key_exists('sondas', $proxys))
                                        @if(is_array($proxys['sondas']))
                                            @foreach ($proxys['sondas'] as $proxyIndex => $proxy )
                                                <li>
                                                    <a href="{{ route('proxy-localhost.index', array('client_id' => request()->query()['client_id'], 'proxy_id' => $proxyIndex))}}"> {{$proxy['hostname']}} </a>
                                                </li>
                                            @endforeach
                                        @endif
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
                    <i class="fe-message-square"></i>
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
                    <i class="fe-toggle-left"></i>
                    <span> DownStreams </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li>
                        <a href="{{ route('downstreams-clients.index', $clientIdQueryParam) }}">Clientes ASN</a>
                    </li>
                    {{-- <li>
                        <a href="apps-kanbanboard.html">Dedicated clients</a>
                    </li> --}}
                </ul>
            </li>



            <li class="menu-title mt-2">Cadastro</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-pocket"></i>
                    <span> Conexão BGP</span>
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
                    <i class="fe-paperclip"></i>
                    <span> Ativo de rede </span>
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
            <li class="menu-title mt-2">Sistema</li>
            <li>
                <a href="javascript: void(0);">
                    <i class="fe-file"></i>
                    <span> ChangeLogs </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('changelog.index') }}">Change Log</a>
                    </li>
                </ul>
            </li>
            @if( auth()->user()->hasRole("administrator") || auth()->user()->hasRole("master") )
                <li class="menu-title mt-2">Clinetes</li>
                <li>
                    <a href="javascript: void(0);">
                        <i class="fe-user-check"></i>
                        <span> Clientes </span>
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
                <li class="menu-title mt-2">Admin</li>
                <li>
                    <a href="{{route('client.index')}}">
                        <i class="fe-layers"></i>
                        <span> Admin Page </span>
                        <span class="menu-arrow"></span>
                    </a>
                </li>
            @endif
            <li>
                <a onclick="location.reload()" style="cursor:pointer">
                    <i class="fe-refresh-ccw"></i>
                    <span> Refresh </span>
                    <span class="menu-arrow"></span>
                </a>
            </li>

        @else
            <li class="menu-title">Gestão de clientes</li>
            <li>
                <a href="javascript: void(0);">
                    <i class="fe-airplay"></i>
                    <span> Gestão de clientes </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('client.index') }}">Gerenciar</a>
                    </li>
                    <li >
                        <a href="{{ route('client.create') }}">Adicionar</a>
                    </li>
                </ul>
            </li>

            <li class="menu-title">Atalhhos</li>
            <li>
                <a href="javascript: void(0);">
                    <i class="fe-users"></i>
                    <span> Clientes </span>
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

            @if( auth()->user()->hasRole("master"))
                <li class="menu-title">Template Transitor</li>

                <li>
                    <a href="javascript: void(0);">
                        <i class="fe-settings"></i>
                        <span> Template Edit </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li >
                            <a href="{{ route('temp-edit.index') }}">Template Editor PE</a>
                        </li>
                        <li >
                            <a href="{{ route('temp-edit-bgp.index') }}">BGP TRANSITO</a>
                        </li>
                        <li >
                            <a href="{{ route('tempix-edit-bgp.index') }}">BPG IX</a>
                        </li>
                        <li >
                            <a href="{{ route('tempeer-edit-bgp.index') }}">BPG PEERING</a>
                        </li>
                        <li >
                            <a href="{{ route('tempcdn-edit-bgp.index') }}">BGP CDN</a>
                        </li>
                        <li >
                            <a href="{{ route('deny-customer.index') }}">Deny Customer-In</a>
                        </li>
                        <li >
                            <a href="{{ route('template-rr.index') }}">Template Reflectors</a>
                        </li>
                    </ul>
                </li>
            @endif
            
            <li class="menu-title">IXBR</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-book-open"></i>
                    <span> IXBR </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('ixbr.index') }}">Ixbr</a>
                    </li>
                </ul>
            </li>

            <li class="menu-title mt-2">Sistema</li>

            <li>
                <a href="javascript: void(0);">
                    <i class="fe-file"></i>
                    <span> ChangeLogs </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="nav-second-level" aria-expanded="false">
                    <li >
                        <a href="{{ route('changelog.index') }}">Change Log</a>
                    </li>
                </ul>
            </li>
            @if( auth()->user()->hasRole("administrator") || auth()->user()->hasRole("master") )
                <li class="menu-title mt-2">admin</li>

                <li>
                    <a href="javascript: void(0);">
                        <i class="fe-codepen"></i>
                        <span> User management </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li >
                            <a href="{{ route('users.index') }}">Users</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li>
                <a onclick="location.reload()" style="cursor:pointer">
                    <i class="fe-refresh-ccw"></i>
                    <span> Refresh </span>
                    <span class="menu-arrow"></span>
                </a>
            </li>
        </ul>
    @endif
</div>

