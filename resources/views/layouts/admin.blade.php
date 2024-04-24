<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{ env('APP_NAME', 'SoccerAPI') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('user_assets/images/logo-sm.png') }}">
        <!-- App css -->
        <link href="{{ asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Customize css -->
        <link href="{{ asset('admin_assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets/libs/jquery-toast/jquery.toast.min.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('common_assets/plugin/jquery-confirm/jquery-confirm.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('common_assets/plugin/blockui/blockUI.css') }}" rel="stylesheet" type="text/css" />

        @yield('styles')
        <style>

        </style>
    </head>
    <body>
        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <div class="navbar-custom">
              @include('partials.admin.topbar')
            </div>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="slimscroll-menu">
                    <!--- Sidemenu -->
                    @include('partials.admin.menubar')
                    <!-- End Sidebar -->
                    <div class="clearfix"></div>
                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">
                      @yield('content')
                    </div> <!-- container -->

                </div> <!-- content -->

                <!-- Footer Start -->
                {{-- <footer class="footer">
                  @include('partials.admin.footerbar')
                </footer> --}}
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
          @include('partials.admin.rightbar')
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        <!-- Vendor js -->
        <script src="{{ asset('admin_assets/js/vendor.min.js') }}"></script>
        <!-- App js-->
        <script src="{{ asset('admin_assets/js/app.min.js') }}"></script>

        <script src="{{ asset('admin_assets/libs/jquery-toast/jquery.toast.min.js') }}"></script>
        <script src="{{ asset('user_assets/js/pages/toastr.init.js') }}"></script>
        <script src="{{ asset('common_assets/plugin/jquery-confirm/jquery-confirm.min.js') }}"></script>
        <script src="{{ asset('common_assets/plugin/blockui/blockUI.js') }}"></script>
        @yield('scripts')
        @stack('js')

        <script>
            $(document).ready(function() {
                $('#copyBtn').click(function() {
                    var text = $('#user-password').val();
                    //Create a temporary input element
                    var tempInput = $('<input>');
                    // Set the value of the temporary input to the text to be copied
                    tempInput.val(text);

                    // Append the temporary input to the document
                    $('body').append(tempInput);

                    // Select the text in the temporary input
                    tempInput.select();

                    // Execute the copy command
                    document.execCommand('copy');

                    // Remove the temporary input from the document
                    tempInput.remove();
                    // Provide feedback to the user

                    $.NotificationApp.send("Alert"
                        ,"This client password has been copied to the clipboard!"
                        ,"top-right"
                        ,"#2ebbdb"
                        ,"success",
                    );
                });
            })

            let elementBlock = (type, elements) => {
                $(elements).block({
                    overlayCSS: {
                        opacity: 0.15
                    },
                    message: `
                    <div id="${type}">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>`
                });
            }
            let elementUnBlock = (elements) => {
                $(elements).unblock();
            }
        </script>

    </body>
</html>