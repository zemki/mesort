<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }


        .code {
            border-right: 2px solid;
            font-size: 26px;
            padding: 0 15px 0 15px;
            text-align: center;
        }

        .message-error {
            font-size: 18px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="relative flex items-center justify-center w-full h-screen min-h-screen text-center align-middle">
    <div class="block w-full">
        <div class="w-full shrink">
            <div class="inline code">
                @yield('code')
            </div>
            <div class="inline message-error" style="padding: 10px;">
                @yield('message')
                @yield('url')

            </div>
        </div>

        <a href="{{url('/')}}"
           class="@yield('button home') mt-4 inline-flex relative bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shrink w-64 justify-center items-center text-center">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 20 20"
                 fill="currentColor">
                <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            {{__("Return Home")}}
        </a>
        <a href="{{url()->previous()}}"
           class="@yield('button back') mt-4 inline-flex relative bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shrink w-64 justify-center items-center text-center">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2 fill-current" viewBox="0 0 20 20"
                 fill="currentColor">
                <path fillRule="evenodd"
                      d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                      clipRule="evenodd"/>
            </svg>
            {{__("Return Back")}}
        </a>

        <div>
        </div>
    </div>

</div>
</body>
</html>
