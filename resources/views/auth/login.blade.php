<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>E Kinerja</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/backend/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}">
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}">
    <link rel="manifest" href="{{ asset('assets/backend/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/backend/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/backend/vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/config.js') }}"></script>


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{ asset('assets/backend/vendors/choices/choices.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/vendors/dhtmlx-gantt/dhtmlxgantt.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/backend/vendors/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <link href="{{ asset('assets/backend/vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="{{ asset('assets/backend/css/theme-rtl.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/backend/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/backend/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet"
        id="user-style-rtl">
    <link href="{{ asset('assets/backend/css/user.min.css') }}" type="text/css" rel="stylesheet"
        id="user-style-default">
    <script>
    var phoenixIsRTL = window.config.config.phoenixIsRTL;
    if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
    }
    </script>
</head>


<body>
    <div class="container-fluid bg-body-tertiary dark__bg-gray-1200">
        <div class="bg-holder bg-auth-card-overlay" style="background-image:url(/assets/backend/img/bg/37.png);">
        </div>

        <div class="row flex-center position-relative min-vh-100 g-0 py-5">
            <div class="col-11 col-sm-10 col-xl-8">
                <div class="card border border-translucent auth-card">
                    <div class="card-body pe-md-0">
                        <div class="row align-items-center gx-0 gy-7">
                            <div
                                class="col-auto bg-body-highlight dark__bg-gray-1100 rounded-3 position-relative overflow-hidden auth-title-box">
                                <div class="bg-holder" style="background-image:url(/assets/backend/img/bg/38.png);">
                                </div>
                                <div
                                    class="position-relative px-4 px-lg-7 pt-7 pb-7 pb-sm-5 text-center text-md-start pb-lg-7 pb-md-7">
                                    <h3 class="mb-3 text-body-emphasis fs-7">{{ $settings->name }}</h3>
                                    <p class="text-body-tertiary">{{ $settings->description }}</p>
                                    <ul class="list-unstyled mb-0 w-max-content w-md-auto">
                                        <li class="d-flex align-items-center"><span
                                                class="uil uil-check-circle text-success me-2"></span><span
                                                class="text-body-tertiary fw-semibold">Fast</span></li>
                                        <li class="d-flex align-items-center"><span
                                                class="uil uil-check-circle text-success me-2"></span><span
                                                class="text-body-tertiary fw-semibold">Simple</span></li>
                                        <li class="d-flex align-items-center"><span
                                                class="uil uil-check-circle text-success me-2"></span><span
                                                class="text-body-tertiary fw-semibold">Responsive</span></li>
                                    </ul>
                                </div>
                                <div class="position-relative z-n1 mb-6 d-none d-md-block text-center mt-md-15"><img
                                        class="auth-title-box-img d-dark-none"
                                        src="/assets/backend/img/spot-illustrations/auth.png" alt="" /><img
                                        class="auth-title-box-img d-light-none"
                                        src="/assets/backend/img/spot-illustrations/auth-dark.png" alt="" /></div>
                            </div>

                            <div class="col mx-auto">
                                <div class="auth-form-box">
                                    <div class="text-center mb-7"><a
                                            class="d-flex flex-center text-decoration-none mb-4" href="/">
                                            <div class="d-flex align-items-center fw-bolder fs-3 d-inline-block">
                                                <img src="{{ $settings->image ? url('storage/' . $settings->image) : url('storage/images/pengaturan.png') }}"
                                                    alt="" width="50" />
                                            </div>
                                        </a>
                                        <h3 class="text-body-highlight">Selamat Datang</h3>
                                        <p class="text-body-tertiary">Silakan masuk ke akun Anda dan mulai lakukan
                                            kinerja Anda.</p>
                                    </div>
                                    <div class="position-relative">
                                        <hr class="bg-body-secondary mt-5 mb-4" />
                                        <div class="divider-content-center bg-body-emphasis">Use Nip</div>
                                    </div>
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf

                                        <div class="mb-3 text-start">
                                            <label class="form-label" for="nip">Nip </label>
                                            <div class="form-icon-container">
                                                <input class="form-control form-icon-input" id="nip" type="text"
                                                    name="nip" />
                                                <span class="fas fa-user text-body fs-9 form-icon"></span>
                                            </div>
                                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                                        </div>
                                        <div class="mb-3 text-start">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="form-icon-container" data-password="data-password">
                                                <input class="form-control form-icon-input pe-6" id="password"
                                                    type="password" placeholder="Password"
                                                    data-password-input="data-password-input" name="password" />
                                                <span class="fas fa-key text-body fs-9 form-icon"></span>
                                                <button
                                                    class="btn px-3 py-0 h-100 position-absolute top-0 end-0 fs-7 text-body-tertiary"
                                                    data-password-toggle="data-password-toggle" type="button"><span
                                                        class="uil uil-eye show"></span><span
                                                        class="uil uil-eye-slash hide"></span></button>
                                            </div>

                                        </div>
                                        <div class="row flex-between-center mb-7">
                                            <div class="col-auto">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" id="basic-checkbox" type="checkbox"
                                                        checked="checked" />
                                                    <label class="form-check-label mb-0" for="basic-checkbox">Remember
                                                        me</label>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary w-100 mb-3">Sign In</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('assets/backend/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/list.js/list.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/choices/choices.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/dhtmlx-gantt/dhtmlxgantt.js') }}"></script>
    <script src="{{ asset('assets/backend/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/phoenix.js') }}"></script>
    <script src="{{ asset('assets/backend/js/projectmanagement-dashboard.js') }}"></script>

</body>

</html>