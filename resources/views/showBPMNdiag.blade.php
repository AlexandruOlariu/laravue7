@extends('layouts.app')

@section('content')
<div>

</div>
<div class="diagram-container">
    <vue_mybpmn
    url="http://localhost/laravue7/storage/app/process/PullRequestProcess.bpmn"
    process="{{$pr}}"
    style="width: 1200px;height: 600px"
    >
    </vue_mybpmn>
</div>

@endsection

