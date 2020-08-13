@component('mail::message')
Mesaj de la {{$data['numesender']}} cu adresa de email {{$data['dela']}}
<br><br>
Subiect:{{$data['subiect']}}<br>
Mesaj:<br>
{{$data['mesaj']}}




Thanks,<br>
{{$data['numesender']}}
@endcomponent
