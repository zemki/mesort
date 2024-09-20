<!--Modal-->
<div class="fixed top-0 left-0 flex items-center justify-center w-full h-full opacity-0 pointer-events-none modal">
    <div class="absolute w-full h-full bg-gray-900 opacity-50" @click="toggleModalChangeEmail()"></div>

    <div class="z-50 w-1/2 mx-auto overflow-y-auto bg-white rounded shadow-lg modal-container md:max-w-md">
        <div @click="toggleModalChangeEmail()"
             class="absolute top-0 right-0 z-50 flex flex-col items-center mt-4 mr-4 text-sm text-white cursor-pointer">
            <svg class="text-white fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                 viewBox="0 0 18 18">
                <path
                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                </path>
            </svg>
            <span class="text-sm">(Esc)</span>
        </div>

        <!-- Add margin if you want to see some of the overlay behind the modal-->
        <div class="px-6 py-4 text-left modal-content">
            <!--Title-->
            <div class="flex items-center justify-between pb-3">
                <p class="text-2xl font-bold">
                    {{ trans("Enter the new email") }}
                </p>
                <div @click="toggleModalChangeEmail()" class="z-50 cursor-pointer">
                    <svg class="text-black fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         viewBox="0 0 18 18">
                        <path
                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z">
                        </path>
                    </svg>
                </div>
            </div>

            <!--Body-->
            <input
                class="block w-full px-4 py-2 leading-normal bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring"
                type="email" v-model="newemail.email" id="newemail"/>

            <!--Footer-->
            <div class="flex justify-end pt-2">
                <button
                    class="inline-flex items-center justify-center px-4 py-2 mr-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm cursor-pointer hover:bg-blue-700 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 xl:w-full"
                    @click="sendEmail()">
                    {{ trans("Submit") }}
                </button>
                <button
                    class="inline-flex items-center justify-center px-4 py-2 ml-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md shadow-sm cursor-pointer hover:bg-blue-700 hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 xl:w-full"
                    @click="toggleModalChangeEmail()">
                    {{ trans("Close") }}
                </button>
            </div>
        </div>
        <div class="p-4 m-2 bg-green-100 rounded-md" v-if="newemail.message">
            <div class="flex">
                <div class="flex-shrink-0">
                    <!-- Heroicon name: mini/check-circle -->
                    <svg class="w-5 h-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                         fill="currentColor"
                         aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">@{{trans('Email Changed')}}</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>@{{newemail.message}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- END Modal-->

<div class="relative flex flex-col min-h-full print:hidden">
    <!-- Navbar -->
    <nav class="flex-shrink-0 bg-blue-500">
        <div class="px-2 mx-auto max-w-7xl sm:px-4 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <!-- Logo section -->
                <div class="flex items-center px-2 lg:px-0 xl:w-64">
                    <div class="flex-shrink-0">
                        <a class="flex items-center justify-center text-white align-middle hover:text-gray-200"
                           href="{{url('/')}}"
                           title="Home button">
                            <img class="w-auto h-10" src="{{config('utilities.base64logo')}}" alt="MeSort Logo">
                            <p class="px-3 py-2 text-sm font-medium text-gray-200 rounded-md hover:text-white">Home</p>
                        </a>
                    </div>
                </div>
                <div class="hidden lg:block lg:w-80">
                    <div class="flex items-center justify-end">
                        @if(Auth::user()->hasRole('admin') || in_array(auth()->user()->email, config('lingua.translator')))
                            <a target="_blank"
                               class="px-3 py-2 text-sm font-medium text-gray-200 rounded-md hover:text-white"
                               href="{{url('translations')}}">
                                {{ __('Translations') }}

                            </a>
                        @endif
                        <div class="flex">
                            <a title="{{__('MeSort User Manuals')}}" href="https://mesoftware.org/index.php/mesort/"
                               class="px-3 py-2 text-sm font-medium text-gray-200 rounded-md hover:text-white">{{__('Manuals')}}</a>
                        </div>

                        <div class="flex shrink-0">
                            <span class="sr-only">{{__('Your Email')}}</span>
                            <span
                                class="px-3 py-2 text-sm font-medium text-gray-200 rounded-md cursor-pointer pointer-events-none hover:text-gray-200">{{
                Auth::user()->email }}</span>
                        </div>
                        <div class="relative z-50 flex-shrink-0 ml-4">
                            <div>

                                <button ref="usermenu" @click="showdropdown('dropdownLogout')"
                                        @mouseover="showdropdown('dropdownLogout')" type="button"
                                        class="flex text-sm rounded-full bg-sky-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-sky-500 focus:ring-white"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="{{\Gravatar::get(Auth::user()->email)}}"
                                         alt="">
                                </button>
                            </div>

                            <div id="dropdownLogout"
                                 class="absolute right-0 hidden w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                 tabindex="-1">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                                <a title="Logout" class="flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                   role="menuitem"
                                   tabindex="-1" id="user-menu-item-1" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">{{__('Log out')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</nav>
