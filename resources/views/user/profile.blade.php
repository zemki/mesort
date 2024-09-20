@extends('layouts.app')

@section('content')


<div
  class="max-w-3xl px-4 mx-auto sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
  <div class="flex items-center space-x-5">
    <div class="flex-shrink-0">
      <div class="relative">
        <img class="w-16 h-16 rounded-full" src="{{\Gravatar::get(Auth::user()->email)}}" alt="User Image">
        <span class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></span>
      </div>
    </div>
    <div>
      <h1 class="text-2xl font-bold text-gray-900">{{Auth::user()->email}}</h1>
      <p class="text-sm font-medium text-gray-500">Level <a href="#" class="text-gray-900">Basic - 15 projects</a> since
        <time datetime="{{auth()->user()->created_at}}">{{auth()->user()->created_at}}</time>
      </p>
      <p class="text-sm font-medium text-gray-500">{{__('You can change the Profile picture on')}} <a
          class="text-blue-500 underline" href="https://gravatar.com/" target="_blank"
          title="change you profile picture on Gravatar">Gravatar</a></p>
    </div>
  </div>
  <div class="items-center space-x-5 ">
  </div>
  <div
    class="flex flex-col-reverse mt-6 space-y-4 space-y-reverse justify-stretch sm:flex-row-reverse sm:justify-end sm:space-x-reverse sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
    <button type="button"
      class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">{{__('Contact
      Support')}}</button>

  </div>
</div>

<div class="grid max-w-3xl grid-cols-1 gap-6 mx-auto mt-8 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
  <div class="space-y-6 lg:col-start-1 lg:col-span-2">
    <!-- Description list-->
    <section aria-labelledby="information-title">
      <div class="pt-8 space-y-6 sm:pt-10 sm:space-y-5">
        <div>
          <h3 class="text-lg font-medium leading-6 text-gray-900">{{__('Personal Information')}}</h3>
        </div>
        <div class="space-y-6 sm:space-y-5">
          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="first-name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('Name')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input type="text" name="name" id="name" autocomplete="given-name"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs sm:text-sm">
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="email" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('Email address')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input id="email" name="email" type="email" autocomplete="email" value="{{auth()->user()->email}}"
                disabled
                class="block w-full max-w-lg bg-gray-300 border-gray-200 rounded-md shadow-sm cursor-not-allowed disabled focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="country" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> Country </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <select id="country" name="country" autocomplete="country-name"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs sm:text-sm">
                @foreach (config('utilities.countries') as $country)
                <option>{{$country}}</option>

                @endforeach
              </select>
            </div>
          </div>
          <div class=" sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="phone-number" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
              {{__('Birthday')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input datepicker type="date"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 active:border-blue-500 sm:text-sm "
                placeholder="{{__('Select date')}}" :max="moment().subtract(13,'years').format('YYYY-MM-DD')">
            </div>
          </div>
          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="phone-number" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('Phone
              Number')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input type="text" name="phone-number" id="phone-number" autocomplete="phone-number"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="street-address" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('Street
              address')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input type="text" name="street-address" id="street-address" autocomplete="street-address"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="city" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('City')}} </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input type="text" name="city" id="city" autocomplete="address-level2"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs sm:text-sm">
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="region" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('State /
              Province')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input type="text" name="region" id="region" autocomplete="address-level1"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs sm:text-sm">
            </div>
          </div>

          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
            <label for="postal-code" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"> {{__('ZIP /
              Postal code')}}
            </label>
            <div class="mt-1 sm:mt-0 sm:col-span-2">
              <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code"
                class="block w-full max-w-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs sm:text-sm">
            </div>
          </div>
        </div>
      </div>
      <div
        class="flex flex-col mt-6 space-y-4 space-y justify-stretch sm:flex-row sm:justify-start sm:space-x sm:space-y-0 sm:space-x-3 md:mt-0 md:flex-row md:space-x-3">
        <button type="button"
          class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">{{__('Save')}}</button>
      </div>
    </section>

    <!-- Comments-->
    <section aria-labelledby="notes-title">
      <div class="bg-white shadow sm:rounded-lg sm:overflow-hidden">
        <div class="divide-y divide-gray-200">
          <div class="px-4 py-5 sm:px-6">
            <h2 id="notes-title" class="text-lg font-medium text-gray-900">Notes</h2>
          </div>
          <div class="px-4 py-6 sm:px-6">
            <ul role="list" class="space-y-8">
              <li>
                <div class="flex space-x-3">
                  <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-full"
                      src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                      alt="">
                  </div>
                  <div>
                    <div class="text-sm">
                      <a href="#" class="font-medium text-gray-900">Leslie Alexander</a>
                    </div>
                    <div class="mt-1 text-sm text-gray-700">
                      <p>Ducimus quas delectus ad maxime totam doloribus reiciendis ex. Tempore dolorem maiores.
                        Similique voluptatibus tempore non ut.</p>
                    </div>
                    <div class="mt-2 space-x-2 text-sm">
                      <span class="font-medium text-gray-500">4d ago</span>
                      <span class="font-medium text-gray-500">&middot;</span>
                      <button type="button" class="font-medium text-gray-900">Reply</button>
                    </div>
                  </div>
                </div>
              </li>

              <li>
                <div class="flex space-x-3">
                  <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-full"
                      src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                      alt="">
                  </div>
                  <div>
                    <div class="text-sm">
                      <a href="#" class="font-medium text-gray-900">Michael Foster</a>
                    </div>
                    <div class="mt-1 text-sm text-gray-700">
                      <p>Et ut autem. Voluptatem eum dolores sint necessitatibus quos. Quis eum qui dolorem
                        accusantium voluptas voluptatem ipsum. Quo facere iusto quia accusamus veniam id explicabo
                        et aut.</p>
                    </div>
                    <div class="mt-2 space-x-2 text-sm">
                      <span class="font-medium text-gray-500">4d ago</span>
                      <span class="font-medium text-gray-500">&middot;</span>
                      <button type="button" class="font-medium text-gray-900">Reply</button>
                    </div>
                  </div>
                </div>
              </li>

              <li>
                <div class="flex space-x-3">
                  <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-full"
                      src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                      alt="">
                  </div>
                  <div>
                    <div class="text-sm">
                      <a href="#" class="font-medium text-gray-900">Dries Vincent</a>
                    </div>
                    <div class="mt-1 text-sm text-gray-700">
                      <p>Expedita consequatur sit ea voluptas quo ipsam recusandae. Ab sint et voluptatem
                        repudiandae voluptatem et eveniet. Nihil quas consequatur autem. Perferendis rerum et.</p>
                    </div>
                    <div class="mt-2 space-x-2 text-sm">
                      <span class="font-medium text-gray-500">4d ago</span>
                      <span class="font-medium text-gray-500">&middot;</span>
                      <button type="button" class="font-medium text-gray-900">Reply</button>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="px-4 py-6 bg-gray-50 sm:px-6">
          <div class="flex space-x-3">
            <div class="flex-shrink-0">
              <img class="w-10 h-10 rounded-full"
                src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80"
                alt="">
            </div>
            <div class="flex-1 min-w-0">
              <form action="#">
                <div>
                  <label for="comment" class="sr-only">About</label>
                  <textarea id="comment" name="comment" rows="3"
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    placeholder="Add a note"></textarea>
                </div>
                <div class="flex items-center justify-between mt-3">
                  <a href="#" class="inline-flex items-start space-x-2 text-sm text-gray-500 group hover:text-gray-900">
                    <!-- Heroicon name: solid/question-mark-circle -->
                    <svg class="flex-shrink-0 w-5 h-5 text-gray-400 group-hover:text-gray-500"
                      xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                    </svg>
                    <span> Some HTML is okay. </span>
                  </a>
                  <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Comment</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <section aria-labelledby="timeline-title" class="lg:col-start-3 lg:col-span-1">
    <div class="px-4 py-5 bg-white shadow sm:rounded-lg sm:px-6">
      <h2 id="timeline-title" class="text-lg font-medium text-gray-900">Timeline</h2>

      <div class="flow-root mt-6">
        <ul role="list" class="-mb-8">
          @foreach($actions as $action)
          <li>
            <div class="relative pb-8">
              <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
              <div class="relative flex space-x-3">
                <div>
                  <span class="flex items-center justify-center w-8 h-8 bg-gray-400 rounded-full ring-8 ring-white">
                    <!-- Heroicon name: solid/user -->
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                      fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                        clip-rule="evenodd" />
                    </svg>
                  </span>
                </div>
                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                  <div>
                    <p class="text-base font-bold text-black">{{$action->name}}</p>
                    <p class="text-sm text-gray-500">{{$action->description}}</p>
                  </div>
                  <div class="text-sm text-right text-gray-500 whitespace-nowrap">
                    <time datetime="{{$action->time}}">{{$action->time}}</time>
                  </div>
                </div>
              </div>
            </div>
          </li>

          @endforeach
          <div class="justify-center w-full text-center">
            {{ $actions->links('layouts.pagination') }}
          </div>
      </div>
    </div>
  </section>
</div>

@endsection
@section('pagespecificscripts')

@endsection