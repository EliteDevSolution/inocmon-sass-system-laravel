@extends('layouts.admin')
@section('content')
    <div>
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Gerencia de activos</li>
                            <li class="breadcrumb-item active">Proxies</li>
                            <li class="breadcrumb-item active">Localhost</li>

                        </ol>
                    </div>
                    <h4 class="page-title">Localhost</h4>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4 text-success  mt-0">Console</h4>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4 text-success  mt-0">Gerenciar</h4>
                        <div class="col-6">
                            <h5 class="header-title mb-2 text-blue mt-0">Dados do equip</h5>
                            <p class="mb-0">Equip Id: -NtdEkoQLPCHD_jZy7J7</p>
                            <p class="mb-0">Hostname: LOCALHOST</p>
                            <p class="mb-0">RouterId: -127.0.0.1</p>
                            <p class="mb-0">Plataforma: </p>
                            <p class="mb-0">Sistema Operacional</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="card-box">

                    <ul class="nav nav-tabs nav-bordered">
                        <li class="nav-item">
                            <a href="#configuration" data-toggle="tab" aria-expanded="false" class="nav-link">
                                Gonfiguracao Base
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#relator" data-toggle="tab" aria-expanded="true" class="nav-link active">
                                Relators
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="configuration">
                            <p class="mb-3">Essa é a configuração obrigatória para este RR. É mandatório que seja aplicada antes te estebalecerem sessões com os routers PE
                            </p>

                            <div class="row">
                                <div class="col-6">
                                    <div class="card-box p-2">
                                        <h4 class="header-title mb-2">A configuração candidata pode ser revisada no botão abaixo:</h4>
                                        <!-- sample modal content -->
                                        <div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">PR Hostname : Localhost</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>
                                                    <div class="modal-body p-3">
                                                        <div class="col">
                                                            <p class="header-title mb-2">Config global</p>
                                                            <p class="mb-1">#base config</p>
                                                            <p class="mb-1">sudo su</p>
                                                            <p class="mb-1">%pwd%</p>
                                                            <p class="mb-1">timedata ctrl set-timezone America/Sao_Paulo</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-info waves-effect waves-light">Fetchar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.modal -->
                                        <div class="button-list">
                                            <!-- Responsive modal -->
                                            <button type="button" class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#con-close-modal">Visual Config Base</button>
                                        </div>
                                    </div>
                                    <div class="card-box mb-0">
                                        <table class="table nowrap mb-0">
                                            <thead>
                                            <tr>
                                                <th>Configuração base</th>
                                                <th><button class="btn btn-info waves-effect waves-light">aplicar config base</button></th>
                                            </tr>
                                            <tr>
                                                <th>Atualizar inoc-config</th>
                                                <th><button class="btn btn-info waves-effect waves-light">autalizar inoc-config</button></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div> <!-- end card-box-->
                            </div>
                        </div>
                        <div class="tab-pane show active" id="relator">
                                <h4 class="header-title mb-2">A configuração candidata pode ser revisada no botão abaixo:</h4>
                            <div class="card-box col-6">
                                sem dados
                            </div>
                        </div>

                    </div>
                </div> <!-- end card-box-->
            </div> <!-- end col -->

        </div>
    </div>
@endsection
