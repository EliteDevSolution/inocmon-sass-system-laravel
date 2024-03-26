@extends('layouts.admin')
@section('content')
<div class="column">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="/">iNOCmon</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                            <li class="breadcrumb-item active">Summary</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card-box">
                <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                <h4 class="mt-0 font-16">Upstreams</h4>
                <h2 class="text-primary my-3 text-center">$<span data-plugin="counterup">31,570</span></h2>
                <p class="text-muted mb-0">Total income: $22506 <span class="float-right"><i class="fa fa-caret-up text-success mr-1"></i>10.25%</span></p>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card-box">
                <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                <h4 class="mt-0 font-16">Sales Status</h4>
                <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">683</span></h2>
                <p class="text-muted mb-0">Total sales: 2398 <span class="float-right"><i class="fa fa-caret-down text-danger mr-1"></i>7.85%</span></p>
            </div>
        </div>

        <div class="col-md-6 col-xl-4">
            <div class="card-box">
                <i class="fa fa-info-circle text-muted float-right" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="More Info"></i>
                <h4 class="mt-0 font-16">Recent Users</h4>
                <h2 class="text-primary my-3 text-center"><span data-plugin="counterup">3.2</span>M</h2>
                <p class="text-muted mb-0">Total users: 121 M <span class="float-right"><i class="fa fa-caret-up text-success mr-1"></i>3.64%</span></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box ribbon-box">

                    <div class="card-box ribbon-box">
                        <div class="ribbon ribbon-success float-left"><i class="mdi mdi-access-point mr-1"></i> MPLS Network</div>
                        <div class="ribbon-content" style="text-align: center">
                            <h4 class="text-success  mt-0">1 Registered PEs</h4>
                            <h5 class="mt-3 text-dark"> <span> XXX file </span></h5>
                            <h5 class="text-muted"> <span> There is no file yet </span></h5>
                            <h5 class="text-muted">  <span> To generate </span></h5>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Localhost<i class="mdi mdi-chevron-down"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Separated link</a>
                                </div>
                            </div><!-- /btn-group -->
                            <button type="button" class="btn btn-info dropdown-toggle">Generate</button>
                        </div>
                    </div>


            </div> <!-- end card-box-->
        </div> <!-- end col -->
    </div>

</div>
@endsection
