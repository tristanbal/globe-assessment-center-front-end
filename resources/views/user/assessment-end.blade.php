@extends('layouts.app')

@section('content')
<div class="main-panel bg-globe-light-blue " style="height:100%">
    <div class="content">
        <div class="row pt-4 text-white">
            <div class="col-12">
                <ul class="breadcrumbs text-white">
                    <li class="nav-home text-white">
                        <a href="{{route('home')}}" class="text-white">
                            <i class="flaticon-home " ></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item ">
                        <a href="{{route('assessments.index')}}" class="text-white">My Assessment</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="text-white" >{{$assessmentTypeUser->name}}</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="text-white" >{{$assesseeEmployeeID->employeeID}} - {{$assesseeEmployeeID->firstname}} {{$assesseeEmployeeID->lastname}}</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container bg-globe-light-blue">
            <div class=" d-flex flex-column bd-highlight mb-3 align-items-center">
            <div class=" m-4 bd-highlight avatar avatar-xxl">
                <img src="{{ asset('icons/finish.png')}}" alt="..." class="avatar-img ">
            </div>
            <div class="p-2 bd-highlight">
                <h3 class="text-center text-white text-uppercase">You've reached the end of the assessment.</h3>

                <h5 class="text-center text-white ">
                    @if (count($completionChekerArray)==0)
                        There were no competencies answered.
                    @elseif(count($completionChekerArray)==1)
                        There was only one competency answered.
                    @elseif(count($completionChekerArray)<count($model))
                        There were only {{ count($completionChekerArray)}} out of {{count($model)}} competencies answered.
                    @elseif(count($completionChekerArray)>=count($model))
                        All competencies were answered.
                    @endif

                </h5>
                
                
            </div>
            <div class="p-2 bd-highlight">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Assessment Survey and Feedback</h5>
                        <p class="card-text">Thank you for taking part in this assessment. To further improve the experience in this system, may we ask time from you to answer some questions.</p>
                        <a target = "_blank" href="https://docs.google.com/forms/d/e/1FAIpQLSea1liuEJC8XPiOYpC7OULK7OGGm64y8qwBPYHvq3fZyj3lIw/viewform?usp=sf_link" class="btn btn-success text-uppercase ">Take Survey</a>
                    </div>
                </div>
               
            </div>
            <a href="{{route('assessments.index')}}" class="btn btn-primary btn-round">Return to My Assessment</a>
            </div>
            
            
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        $('#hello-user').hide(0).delay(500).fadeIn(1000);
    });
    </script>

@endsection