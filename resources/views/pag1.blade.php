@extends('layouts.app')

@section('content')
<div class="container">


@include('auth/usercheck/checkifadmin')

    <pricing :can_update=<?php  global $user1; echo $user1 ?? 0;?>  :can_delete=<?php global $user2;echo $user2 ??  0;?> :can_insert=<?php global $user3;echo $user3 ??  0;?> :erori="{{$errors}}" :flori='{{$flowers}}'>

    </pricing>

    <messenger :utilizatori='{{$utilizatoriInreg}}' :utilizatorcurent='{{$currUtil}}'></messenger>
</div>
@endsection
