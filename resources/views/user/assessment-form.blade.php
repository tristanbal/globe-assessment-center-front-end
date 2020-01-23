@extends('layouts.app')

@section('content')

<div class="main-panel">
    <div id="hello-user"  class="content">
        <div class="panel-header" style="background-color:#37474F;">
            <div  class="row pt-4 text-white">
                <div class="col-12">
                    <ul class="breadcrumbs ">
                        <li class="nav-home">
                            <a href="{{route('home')}}">
                                <i class="flaticon-home text-white"></i>
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
                            <a href="#" class="text-white">{{$assessmentTypeUser->name}}</a>
                        </li>
                        <li class="separator">
                            <i class="flaticon-right-arrow"></i>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="text-white">{{$assesseeEmployeeID->employeeID}} - {{$assesseeEmployeeID->firstname}} {{$assesseeEmployeeID->lastname}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="page-inner py-5">
                <div  class="d-flex align-items-left align-items-md-center flex-column flex-md-row ">
                    <div>
                        <h1 class="text-white pb-2 fw-bold text-uppercase" style="letter-spacing:7px">{{$assessmentTypeUser->name}}</h1>
                        <h5 class="text-white pb-4 op-7 mb-2 mr-5">{{$assessmentTypeUser->definition}}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div  class="page-inner mt--5 ">
            <div class="row mt--2 ">
                <div id="ready-user" class="col-md-6">
                    <div  class="row">
                        <div class="col-sm-12">
                            <div class="card ">
                                <div class="card-body text-center">
                                    <div class="card-title text-center text-uppercase py-2">
                                        You are assessing 
                                        @if ( $assesseeEmployeeID->employeeID == $assessorEmployeeID->employeeID)
                                            yourself.
                                        @else
                                            <br>
                                            <b>{{$assesseeEmployeeID->firstname}} {{$assesseeEmployeeID->lastname}}.</b>
                                        @endif
                                    </div>
                                    <div class="card-category pb-4">
                                        @if ($assesseeRole->name != "N/A" && $assesseeRole->name != "#N/A")
                                        <div class="avatar avatar-md my-2">
                                                <img src="{{asset('assessment-images/data.svg')}}" alt="..." class="avatar-img">
                                            </div>
                                            <br>
                                            Your current role is {{$assesseeRole->name}}.<br><br>
                                            <b><u>NOTE:</u><br>  In selecting a behavior set, make certain that you are demonstrating at least 80% of the outlined behaviors at work</b><br><br>
                                            @if ($modelStart)
                                                <a href="{{$modelStart->id}}" class="btn btn-success"><span class="btn-label"><i class="fas fa-arrow-circle-right"></i></span> Start Assessment</a>    
                                            @else
                                            <a href="#" class="btn btn-danger">No Assessment</a>    
                                            @endif
                                            
                                        @else
                                            No role was assigned to you.
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="load-content" class="col-md-6">
                    <div class="card ">
                        <div class="card-body text-center"><!--
                            <div class="card-title text-center text-uppercase py-2">
                                Instructional Video Guide
                            </div>-->
                            <div class="card-category pb-4">
                                <img src="{{asset('assessment-images/iphone laptop.png')}}" style="width:100%;">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#hello-user').hide(0).delay(500).fadeIn(1000);
        $('#ready-user').hide(0).delay(2000).fadeIn(1000);
        $('#load-content').hide(0).delay(3500).fadeIn(1000);
    });
</script>

@endsection