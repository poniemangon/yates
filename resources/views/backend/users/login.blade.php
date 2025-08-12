<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title }}</title>

        <link rel="shortcut icon" href="{{ asset('public/favicon.ico') }}">

        <link rel="stylesheet" href="{{ asset('public/backend/vendors/bootstrap/dist/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('public/backend/vendors/PACE/themes/blue/pace-theme-minimal.css') }}">
        <link rel="stylesheet" href="{{ asset('public/backend/vendors/perfect-scrollbar/css/perfect-scrollbar.min.css') }}">

        <link href="{{ asset('public/backend/css/ei-icon.css') }}" rel="stylesheet">
        <link href="{{ asset('public/backend/css/themify-icons.css') }}" rel="stylesheet">
        <link href="{{ asset('public/backend/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/backend/css/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/backend/css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css">
    </head>
    <body>
        <main class="login-main-page">
            <div class="app">
                <div class="authentication">
                    <div class="sign-in">
                        <div class="row no-mrg-horizon">
                            <div class="col-md-6 no-pdd-horizon d-none d-md-block">
                                <div class="full-height bg" style="background-image: url({{ asset('public/backend/images/background.jpg'); }})">
                                </div>
                            </div>

                            <div class="col-md-6 no-pdd-horizon">
                                <div class="full-height bg-white height-100">
                                    <div class="vertical-align full-height pdd-horizon-70">
                                        <div class="table-cell">
                                            <div class="pdd-horizon-15">

                                                <h2 class="login-title">Login</h2>

                                                <p class="login-text">Enter your information to access the system</p>

                                                <form method="post" action="{{ route('user-login') }}" id="user-login-form">
                                                    
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="email">
                                                    </div>

                                                    <div>
                                                        <label>Password</label>
                                                        <input type="password" class="form-control" name="password">
                                                    </div>

                                                    <button type="submit" class="btn btn-custom mt-3" id="user-login-button">Access</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script src="{{ asset('public/backend/js/vendor.js') }}"></script>
        <script type="text/javascript">
            var url = "{{ url('/ssy-administration') }}";
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
        <script src="{{ asset('public/backend/js/app.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('public/backend/js/functions/users.js') }}"></script>
    </body>
</html>