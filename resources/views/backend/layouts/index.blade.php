<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="title" content="E-Library SMP Negeri 28 Bandung">
    <meta name="description" content="Website E-Library SMP Negeri 28 Bandung">
    <meta name="keywords" content="SMP Negeri 28 Bandung, E-Library, Perpustakaan">
    <meta name="author" content="Salman">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Erke Book Store</title>

    <!-- Core -->
    <link rel="stylesheet" href="{{ asset('assets/layouts/backend/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/input-form-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/button-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-custom.css') }}">

    {{-- <link rel="icon" href="{{ asset('assets/images/logo.png') }}"> --}}

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> --}}

    @stack('css-libraries')

</head>
<body class="sb-nav-fixed">
    @include('backend.layouts.navbar')

    <div id="layoutSidenav">
        @include('backend.layouts.sidebar')
        
        <div id="layoutSidenav_content">
            <main>
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-breadcrumb mt-3">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    </ol>
                </nav> --}}
                <div class="container-fluid">
                    @yield('main-content')
                </div>
            </main>

            @include('backend.layouts.footer')
        </div>
    </div>
    
    <!-- Core -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/layouts/backend/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/layouts/backend/js/helpers.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>

    <!-- Sweetalert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap datepicker -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> --}}
    
    <script>
        const APP_URL= {!! json_encode(url('/')) !!};
    </script>

    @stack('script-libraries')
    @yield('script')

</body>
</html>