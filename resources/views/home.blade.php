@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ url('flowers') }}">DB</a>
    <a href="{{url('bpmn')}}">BPMN</a>
</div>
@endsection
