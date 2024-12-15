<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    @include('layout.head')
</head>

<body class="index-page bg-gray-200">
    <!-- Navbar -->
    @include('layout.navbar')

    @if (isset($showHeader) && $showHeader)
        @include('layout.header')
        @yield('field-content')
    @else
        @yield('field-content')
    @endif

    @yield('scripts')

    @include('layout.footer')
  
    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/countup.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/prism.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/highlight.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/rellax.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/tilt.min.js') }}"></script>
    
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    
    <script src="{{ asset('assets/js/material-kit.min.js?v=3.0.4') }}" type="text/javascript"></script>

    <script type="text/javascript">
        if (document.getElementById('state1')) {
            const countUp = new CountUp('state1', document.getElementById("state1").getAttribute("countTo"));
            if (!countUp.error) {
                countUp.start();
            } else {
                console.error(countUp.error);
            }
        }

        if (document.getElementById('state2')) {
            const countUp1 = new CountUp('state2', document.getElementById("state2").getAttribute("countTo"));
            if (!countUp1.error) {
                countUp1.start();
            } else {
                console.error(countUp1.error);
            }
        }

        if (document.getElementById('state3')) {
            const countUp2 = new CountUp('state3', document.getElementById("state3").getAttribute("countTo"));
            if (!countUp2.error) {
                countUp2.start();
            } else {
                console.error(countUp2.error);
            }
        }
        if (document.getElementById('state4')) {
            const countUp3 = new CountUp('state4', document.getElementById("state4").getAttribute("countTo"));
            if (!countUp3.error) {
                countUp3.start();
            } else {
                console.error(countUp3.error);
            }
        }
        if (document.getElementById('state5')) {
            const countUp4 = new CountUp('state5', document.getElementById("state5").getAttribute("countTo"));
            if (!countUp4.error) {
                countUp4.start();
            } else {
                console.error(countUp4.error);
            }
        }
        if (document.getElementById('state6')) {
            const countUp5 = new CountUp('state6', document.getElementById("state6").getAttribute("countTo"));
            if (!countUp5.error) {
                countUp5.start();
            } else {
                console.error(countUp5.error);
            }
        }
    </script>
</body>

</html>
