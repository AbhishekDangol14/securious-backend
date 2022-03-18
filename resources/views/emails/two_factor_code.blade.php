@component('mail::message')

    Your code is {{$code}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
