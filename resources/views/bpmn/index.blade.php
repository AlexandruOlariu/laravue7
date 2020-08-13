@extends('layouts.app')

@section('content')
    <div class="container">




            <div style="margin: 30px 0px">
                <h1>List de pullrequest</h1>
            </div>

            <div class="card mb-5">

                <div class="card-body">
                    <bpmn_table :prs='{{$pullRequests}}'></bpmn_table>
                </div>
            </div>
        </div>
@endsection
