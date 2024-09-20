@include('layouts.header')

<body class="font-sans">
<div id="app" class="">
    @include('layouts.nav')
    <main>


        @yield('content')


    </main>
</div>

@include('layouts.footer')

</body>
</html>
