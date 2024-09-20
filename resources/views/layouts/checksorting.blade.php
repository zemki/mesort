@include('layouts.headerdompdf')

<body>
<div id="app">

    <main class="py-4">
        @yield('content')
    </main>
</div>

@include('layouts.footer')

</body>
</html>
