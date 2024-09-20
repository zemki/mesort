@extends('layouts.app')

@section('content')


<!-- Pricing with four tiers and toggle -->
<div class="bg-gradient-to-b from-white to-gray-50">
  <div class="px-4 pt-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="sm:align-center sm:flex sm:flex-col">
      <h1 class="text-5xl font-bold tracking-tight text-gray-900 sm:text-center">Pricing Plans</h1>
      <p class="mt-5 text-xl text-gray-500 sm:text-center">Start building for free, then add a site plan to go live.
        Account plans unlock additional features.</p>
    </div>
    <div
      class="mt-12 space-y-4 sm:mt-16 sm:grid sm:grid-cols-2 sm:gap-6 sm:space-y-0 lg:mx-auto lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-3">
      <div class="border border-gray-200 divide-y divide-gray-200 rounded-lg shadow-sm">
        <div class="p-6">
          <h2 class="text-lg font-medium leading-6 text-gray-900">Mesort</h2>
          <p class="mt-4 text-sm text-gray-500">This is why you should buy MeSort</p>
          <p class="mt-8">
            <span class="text-4xl font-bold tracking-tight text-gray-900">20€</span>
            <span class="text-base font-medium text-gray-500">/year</span>
          </p>
          <a href="#"
            class="block w-full py-2 mt-8 text-sm font-semibold text-center text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-700">Subscribe to
            MeSort</a>
        </div>
        <div class="px-6 pt-6 pb-8">
          <h3 class="text-sm font-medium text-gray-900">What's included</h3>
          <ul role="list" class="mt-6 space-y-4">
            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Potenti felis, in cras at at ligula nunc.</span>
            </li>

            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Orci neque eget pellentesque.</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="border border-gray-200 divide-y divide-gray-200 rounded-lg shadow-sm">
        <div class="p-6">
          <h2 class="text-lg font-medium leading-6 text-gray-900">MeTag Analyze</h2>
          <p class="mt-4 text-sm text-gray-500">You should definitely do Media D</p>
          <p class="mt-8">
            <span class="text-4xl font-bold tracking-tight text-gray-900">20€</span>
            <span class="text-base font-medium text-gray-500">/year</span>
          </p>
          <a href="{{config('utilities.metag_payment_link').auth()->user()->email}}" target="_blank"
            class="block w-full py-2 mt-8 text-sm font-semibold text-center text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-700">Subscribe to
            MeTag Analyze</a>
        </div>
        <div class="px-6 pt-6 pb-8">
          <h3 class="text-sm font-medium text-gray-900">What's included</h3>
          <ul role="list" class="mt-6 space-y-4">
            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Potenti felis, in cras at at ligula nunc. </span>
            </li>

            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Orci neque eget pellentesque.</span>
            </li>

            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Donec mauris sit in eu tincidunt etiam.</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="border border-gray-200 divide-y divide-gray-200 rounded-lg shadow-sm">
        <div class="p-6">
          <h2 class="text-lg font-medium leading-6 text-gray-900">MeResearch</h2>
          <p class="mt-4 text-sm text-gray-500">This is why MeResearch is cool</p>
          <p class="mt-8">
            <span class="text-4xl font-bold tracking-tight text-gray-900">20€</span>
            <span class="text-base font-medium text-gray-500">/year</span>
          </p>
          <a href="https://buy.stripe.com/test_bIY2bgbYl1x7gNidQU" target="_blank"
            class="block w-full py-2 mt-8 text-sm font-semibold text-center text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-700">Subscribe to
            MeResearch</a>
        </div>
        <div class="px-6 pt-6 pb-8">
          <h3 class="text-sm font-medium text-gray-900">What's included</h3>
          <ul role="list" class="mt-6 space-y-4">
            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Potenti felis, in cras at at ligula nunc. </span>
            </li>

            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Orci neque eget pellentesque.</span>
            </li>

            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Donec mauris sit in eu tincidunt etiam.</span>
            </li>

            <li class="flex space-x-3">
              <!-- Heroicon name: mini/check -->
              <svg class="flex-shrink-0 w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                  clip-rule="evenodd" />
              </svg>
              <span class="text-sm text-gray-500">Faucibus volutpat magna.</span>
            </li>
          </ul>
        </div>
      </div>

      
    </div>
  </div>

  <!-- Feature list -->
  <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:py-24 lg:px-8">
    <div class="max-w-3xl mx-auto text-center">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900">What MeSoftware gives to people 💪</h2>
      <p class="mt-4 text-lg text-gray-500">And something to underline here.</p>
    </div>
    <dl class="mt-12 space-y-10 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:gap-y-12 sm:space-y-0 lg:grid-cols-4 lg:gap-x-8">
      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Invite team members</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Tempor tellus in aliquet eu et sit nulla
          tellus. Suspendisse est, molestie blandit quis ac. Lacus.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Notifications</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Ornare donec rhoncus vitae nisl velit, neque,
          mauris dictum duis. Nibh urna non parturient.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">List view</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Etiam cras augue ornare pretium sit malesuada
          morbi orci, venenatis. Dictum lacus.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Boards</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Interdum quam pulvinar turpis tortor, egestas
          quis diam amet, natoque. Mauris sagittis.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Keyboard shortcuts</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Ullamcorper in ipsum ac feugiat. Senectus at
          aliquam vulputate mollis nec. In at risus odio.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Reporting</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Magna a vel sagittis aliquam eu amet. Et
          lorem auctor quam nunc odio. Sed bibendum.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Calendars</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Sed mi, dapibus turpis orci posuere integer.
          A porta viverra posuere adipiscing turpis.</dd>
      </div>

      <div class="relative">
        <dt>
          <!-- Heroicon name: outline/check -->
          <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
          </svg>
          <p class="text-lg font-medium leading-6 text-gray-900 ml-9">Mobile app</p>
        </dt>
        <dd class="flex mt-2 text-base text-gray-500 ml-9 lg:py-0 lg:pb-4">Quisque sapien nunc nisl eros. Facilisis
          sagittis maecenas id dignissim tristique proin sed.</dd>
      </div>
    </dl>
  </div>
</div>

<!-- Logo cloud on brand -->
<div class="bg-blue-500">
  <div class="px-4 py-16 mx-auto max-w-7xl sm:py-20 sm:px-6 lg:px-8">
    <div class="lg:space-y-10">
      <h2 class="text-3xl font-bold tracking-tight text-white">No one uses our software</h2>
      <h2 class="text-3xl font-bold tracking-tight text-white">Be the first crazy MF</h2>
      <div class="flow-root mt-8 lg:mt-0">
        <div class="flex flex-wrap justify-between -mt-4 -ml-8 lg:-ml-4">
          <div class="flex flex-grow flex-shrink-0 mt-4 ml-8 lg:ml-4 lg:flex-grow-0">
            <img class="h-12" src="https://tailwindui.com/img/logos/tuple-logo-blue-200.svg" alt="Tuple">
          </div>

          <div class="flex flex-grow flex-shrink-0 mt-4 ml-8 lg:ml-4 lg:flex-grow-0">
            <img class="h-12" src="https://tailwindui.com/img/logos/mirage-logo-blue-200.svg" alt="Mirage">
          </div>

          <div class="flex flex-grow flex-shrink-0 mt-4 ml-8 lg:ml-4 lg:flex-grow-0">
            <img class="h-12" src="https://tailwindui.com/img/logos/statickit-logo-blue-200.svg" alt="StaticKit">
          </div>

          <div class="flex flex-grow flex-shrink-0 mt-4 ml-8 lg:ml-4 lg:flex-grow-0">
            <img class="h-12" src="https://tailwindui.com/img/logos/transistor-logo-blue-200.svg" alt="Transistor">
          </div>

          <div class="flex flex-grow flex-shrink-0 mt-4 ml-8 lg:ml-4 lg:flex-grow-0">
            <img class="h-12" src="https://tailwindui.com/img/logos/workcation-logo-blue-200.svg" alt="Workcation">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FAQ offset -->
<div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:py-20 lg:px-8">
  <div class="lg:grid lg:grid-cols-3 lg:gap-8">
    <div class="space-y-4">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900">Frequently asked questions</h2>
      
    </div>
    <div class="mt-12 lg:col-span-2 lg:mt-0">
      <dl class="space-y-12">
        <div>
          <dt class="text-lg font-medium leading-6 text-gray-900">How do you make holy water?</dt>
          <dd class="mt-2 text-base text-gray-500">You boil the hell out of it. Lorem ipsum dolor sit amet consectetur
            adipisicing elit. Quas cupiditate laboriosam fugiat.</dd>
        </div>

        <div>
          <dt class="text-lg font-medium leading-6 text-gray-900">What&#039;s the best thing about Switzerland?</dt>
          <dd class="mt-2 text-base text-gray-500">I don&#039;t know, but the flag is a big plus. Lorem ipsum dolor sit
            amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.</dd>
        </div>

        <div>
          <dt class="text-lg font-medium leading-6 text-gray-900">What do you call someone with no body and no nose?
          </dt>
          <dd class="mt-2 text-base text-gray-500">Nobody knows. Lorem ipsum dolor sit amet consectetur adipisicing
            elit. Quas cupiditate laboriosam fugiat.</dd>
        </div>

        <div>
          <dt class="text-lg font-medium leading-6 text-gray-900">Why do you never see elephants hiding in trees?</dt>
          <dd class="mt-2 text-base text-gray-500">Because they&#039;re so good at it. Lorem ipsum dolor sit amet
            consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.</dd>
        </div>
      </dl>
    </div>
  </div>
</div>

@endsection