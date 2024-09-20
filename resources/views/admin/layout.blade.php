@include('layouts.header')

<body class="font-sans leading-normal tracking-normal bg-black-alt">
<div id="app">
    <nav id="header" class="top-0 z-10 w-full bg-gray-900 shadow">
        <?php
        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim(Auth::user()->email))) . "&s=40";
        ?>
        <div class="container flex flex-wrap items-center justify-between w-2/3 py-3 mx-auto">
            <div class="flex items-center">
                <a class="flex items-center text-xl font-bold text-gray-100 no-underline hover:no-underline" href="#">
                    <img class="w-8 h-8 mr-4 rounded-full" src="{{ config('utilities.base64logo') }}" alt="Logo">
                    Mesort Admin
                </a>
            </div>

            <div class="flex items-center">
                <div class="relative">
                    <button id="userButton"
                            @click="showdropdown('dropdownLogout')"
                            @mouseover="showdropdown('dropdownLogout')"
                            class="flex items-center text-sm text-gray-100 focus:outline-none">
                        <img class="w-8 h-8 mr-4 rounded-full" src="{{ $grav_url }}" alt="User Avatar">
                        <span>{{ Auth::user()->email }}</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </button>

                    <div id="dropdownLogout"
                         class="absolute right-0 z-30 hidden mt-2 bg-gray-900 rounded shadow-md"
                         @mouseleave="showdropdown('dropdownLogout')">
                        <ul class="list-reset">
                            <li>
                                <hr class="mx-2 border-t border-gray-400">
                            </li>
                            <li>
                                <a href="#"
                                   class="block px-4 py-2 text-gray-100 hover:bg-gray-800 hover:no-underline"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="lg:hidden">
                    <button id="nav-toggle"
                            class="flex items-center px-3 py-2 text-gray-500 border border-gray-600 rounded hover:text-gray-100 hover:border-teal-500 focus:outline-none">
                        <svg class="w-3 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <title>Menu</title>
                            <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="nav-content" class="hidden w-full mt-2 bg-gray-900 lg:flex lg:items-center lg:w-auto justify-center">
            <ul class="flex flex-col items-center justify-center list-reset lg:flex-row">
                <li class="my-2 lg:my-0 lg:mx-6">
                    <a :class="url == 'admin' ? 'text-blue-400' : 'text-gray-500'"
                       href="{{ url('admin/') }}"
                       class="block py-1 border-b-2 border-transparent md:py-3 hover:text-gray-100 hover:border-blue-400">
                        <i class="mr-3 fas fa-home"></i>Dashboard
                    </a>
                </li>
                <li class="my-2 lg:my-0 lg:mx-6">
                    <a :class="url == '' ? 'text-blue-400' : 'text-gray-500'"
                       href="{{ url('admin/studies') }}"
                       class="block py-1 border-b-2 border-transparent md:py-3 hover:text-gray-100 hover:border-purple-400">
                        <i class="mr-3 fa fa-envelope"></i>Projects
                    </a>
                </li>
                <li class="my-2 lg:my-0 lg:mx-6">
                    <a :class="url == 'users' ? 'text-blue-400' : 'text-gray-500'"
                       href="{{ url('admin/users') }}"
                       class="block py-1 border-b-2 border-transparent md:py-3 hover:text-gray-100 hover:border-green-400">
                        <i class="mr-3 fas fa-chart-area"></i>Users
                    </a>
                </li>
                <li class="my-2 lg:my-0 lg:mx-6">
                    <a :class="url == 'notifications' ? 'text-blue-400' : 'text-gray-500'"
                       href="{{ url('admin/notifications') }}"
                       class="block py-1 border-b-2 border-transparent md:py-3 hover:text-gray-100 hover:border-red-400">
                        <i class="mr-3 fa fa-wallet"></i>Notifications
                    </a>
                </li>
                <li class="my-2 lg:my-0 lg:mx-6">
                    <a :class="url == 'newsletter' ? 'text-blue-400' : 'text-gray-500'"
                       href="{{ url('admin/newsletter') }}"
                       class="block py-1 border-b-2 border-transparent md:py-3 hover:text-gray-100 hover:border-red-400">
                        <i class="mr-3 fa fa-wallet"></i>Newsletter
                    </a>
                </li>
                <li class="my-2 lg:my-0 lg:mx-6">
                    <a href="{{ url('') }}"
                       class="block py-1 text-gray-500 border-b-2 border-transparent md:py-3 hover:text-gray-100 hover:border-red-400">
                        <i class="mr-3 fa fa-wallet"></i>Mesort Home
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="w-2/3 mx-auto mt-2">
        @if(session()->has('message') || !empty($message))
            <div
                class="w-full px-4 py-3 mx-auto text-center text-blue-700 bg-yellow-100 border-t border-b border-blue-500"
                role="alert">
                <p class="font-bold">
                        <span class="relative w-3 h-3">
                            <span
                                class="absolute inline-flex w-full h-full bg-pink-400 rounded-full opacity-75 animate-ping"></span>
                            <span class="relative inline-flex w-3 h-3 bg-pink-500 rounded-full"></span>
                        </span>
                    {{ session()->get('message') ?: $message }}
                </p>
            </div>
        @endif

        <div class="w-full">
            @yield('content')
        </div>
    </div>
</div>

@yield('pagespecificscripts')

</body>
