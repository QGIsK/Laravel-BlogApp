@component('mail::message')
Hello **{{$name}}**,  {{-- use double space for line break --}}

@foreach($posts as $post)

{{$post}}

@component('mail::button', ['url' => 'http://localhost:8080/' ])
Read post now
@endcomponent
@endforeach

@endcomponent