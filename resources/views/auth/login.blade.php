<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="{{ asset('user_assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{ asset('login_assets/style.css') }}">
<body>
        <div class="container-fluid bg-layer" id="userLogSection">
            <div class="row">
                <div class="col-12">
                    <form class="jumbotron mt-5 text-center" role="form" method="POST" action="{{ url('login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
{{--                        <h2 class="text-center mb-4">Soccer Compare</h2>--}}
                        @if (count($errors) > 0)
                            <div class="alert alert-danger" id="alertMessage" role="alert">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger" id="alertMessage" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <h2 class="">iNOCmon</h2>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        {{-- <a href="{{ url("register") }}" class="text-white-50" style="font-size: 13px;">Don't you have account?</a> --}}
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="{{ asset('user_assets/js/jquery_3.3.1_jquery.min.js') }}"></script>
    <script>
        $(".alert").fadeTo(4000, 500).slideUp(500, function(){
            $(".alert").slideUp(500);
        });
    </script>

</html>