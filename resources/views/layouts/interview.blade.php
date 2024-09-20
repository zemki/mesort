@include('layouts.headerInterview')
<style>

    ::-webkit-scrollbar {
        display: none;
    }

    html, body {
        margin: 0;
        max-height: 100%;
        overflow: hidden;
    }
</style>
<body style="-webkit-overflow-scrolling: touch;">
<div id="app">
    @include('layouts.navInterview')

    <main>
        @yield('content')
    </main>
</div>

@include('layouts.footer')

</body>
</html>
