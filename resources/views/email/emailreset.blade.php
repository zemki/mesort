@component('mail::message')

# {{__('Change email on')}} {{ config('app.name') }}

{{__('You request a change of email, click on the button to change it:')}}

@component('mail::button', ['url' => $route])
{{__('Confirm the change')}}
@endcomponent
@endcomponent