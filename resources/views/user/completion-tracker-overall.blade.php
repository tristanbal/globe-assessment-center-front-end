@extends('layouts.app')

@section('content')
<div  class="main-panel">
    <div id="start-up" class="content">
        <div class="row pt-4">
            <div class="col-12">
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{route('home')}}" class="">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="">Completion Tracker</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container pt-4">
            <h1 class="text-uppercase"><b>{{$completionTrackerAssignment->gpgas->name}}</b> - {{$group->name}} </h1>
        </div>
        
    </div>
</div>
@endsection
@section('scripts')

    
@endsection