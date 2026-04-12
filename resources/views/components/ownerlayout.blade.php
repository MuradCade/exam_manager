<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ===== Title ===== --}}
    <title>
        {{ $title ?? "Deafult Title" }}
    </title>

    
    

    {{-- ===== Assets ===== --}}
    <link rel="icon" href="{{ asset('assets/img/logo.ico') }}">

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}"> --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/mdb-uikit.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/mdb.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/toastr/build/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboardlayout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastcolors.css') }}">

    {{-- ===== Fonts ===== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">


    </style>
    
</head>

<body class="font-inter antialiased">

    {{ $slot }}

    {{-- ===== Scripts ===== --}}
   <script src="{{ asset('assets/toastr/build/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/mdb.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script defer src="{{ asset('assets/js/alpine.js') }}"></script>

</body>
</html>
