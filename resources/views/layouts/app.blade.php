<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ url('/assets/img/hilab.ico') }}" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ url('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/izitoast/css/iziToast.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ url('assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/prism/prism.css') }}">
    <link rel="stylesheet" href="{{ url('assets/modules/izitoast/css/iziToast.min.css') }}">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ url('/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ url('/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ url('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ url('/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link href="{{ url('/assets/modules/dataTables/datatables.min.css') }}" rel="stylesheet" />
    <!-- JS Libraies -->
    <script src="{{ url('assets/modules/izitoast/js/iziToast.min.js') }}"></script>

    <!-- Helpers -->
    <script src="{{ url('/assets/vendor/js/helpers.js') }}"></script>

    <script src="{{ url('/assets/js/config.js') }}"></script>
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.css">

</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('layouts.header')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        {{ $slot }}

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                Lab Information System
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>

    <script src="{{ url('/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ url('/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ url('/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ url('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ url('/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->
    <script src="{{ url('assets/modules/dataTables/datatables.min.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ url('/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ url('/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ url('/assets/js/dashboards-analytics.js') }}"></script>


</body>

</html>
