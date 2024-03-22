<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" >
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('login_assets/style.css') }}">
        <style>
            .register-form{
                height: auto;
                top: 0px !important;
                padding: 2rem;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid bg-layer" id="userLogSection">
            <div class="row">
                <div class="col-12">
                    <form class="jumbotron mt-5 text-center register-form" role="form" method="POST" action="{{ url('register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger" id="alertMessage" role="alert">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                        <img src="{{ asset('user_assets/images/logo-light.png') }}" class="heading-avatar-icon mb-4" />
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="occupation" name="occupation">
                                <option value="" disabled selected>Occupation</option>
                                <option value="Club Director/Member">Club Director/Member</option>
                                <option value="School or academy director/member">School or academy director/member</option>
                                <option value="Coach">Coach</option>
                                <option value="Analyst">Analyst</option>
                                <option value="Scout">Scout</option>
                                <option value="Journalist">Journalist</option>
                                <option value="Player">Player</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="other_occupation" name="other_occupation" placeholder="Input occupation">
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="country" name="country">
                                <option value="" disabled selected>Country</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="team_or_academy" name="team_or_academy" value="{{ old('team') }}" placeholder="TEAM/SCHOOL OR ACACEMY">
                        </div>
                        <button type="submit" class="btn btn-primary">Register</button>
                        <a href="{{ url("login") }}" class="text-white-50" style="font-size: 13px;">Go to Sign in</a>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
                integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
                crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
                crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
                $('#other_occupation').hide()
                let settings = {
                    "async": true,
                    "crossDomain": true,
                    "url": "https://ajayakv-rest-countries-v1.p.rapidapi.com/rest/v1/all",
                    "method": "GET",
                    "headers": {
                        "x-rapidapi-host": "ajayakv-rest-countries-v1.p.rapidapi.com",
                        "x-rapidapi-key": "596585807fmsh94116d249e0cd64p1d139cjsn6b7b5407af9b"
                    }
                }
                $.ajax(settings).done(function (response) {
                    for(ind in response)
                    {
                        $('#country').append($("<option></option>").text(response[ind].name).attr("value", response[ind].name));
                    }
                });
            })
            $('#occupation').on('change',function () {
                let val = $(this).val();
                if(val=="Other"){
                    $('#other_occupation').show();
                }else{
                    $('#other_occupation').hide();
                }
            })
    </script>
    </body>
</html>